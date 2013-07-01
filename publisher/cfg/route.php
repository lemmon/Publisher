<?php
/**
* 
*/
class Route extends \Lemmon\Route
{
    private $_extend;
    private $_site;
    private $_page;


    protected function __init()
    {
        $this->_extended = new RouteExtended;
        //
        // site
        //
        if ($site = Site::findCurrent()) {
            $this->_site = $site;
            define('SITE_ID', $site->id);
        } else {
            // site not found
            die('Route/ Site not found');
        }
        //
        // backend
        //
        if ($this->getParam(1) == 'admin') {
            if ($this->match('$controller(/$page)(/$action(/$id)(/$hash))', ['controller' => 'admin\/[\w\-]+', 'action' => '[\w\-]+', 'id' => '\d+', 'hash' => '[\w\-]+', 'page' => '\d+'])) {
                // general CRUD
            } else {
                Application::setController('admin/index');
            }

            $this->register('home', 'admin');
            $this->register('site', '/');

            $this->register('section', 'admin/@%1/%2/%3');
            $this->register('action', 'admin/@/%1/%2');
            $this->register('page', 'admin/@/%1');

            $this->register('index', 'admin/@$type/$id');
            $this->register('create', 'admin/@/create');
            $this->register('update', 'admin/@$type/update/$id');
            $this->register('delete', 'admin/@$_section/delete/$id');
            $this->register('crud', 'admin/@$_section/$action/$id');

            $this->register('login', 'admin/login');
            $this->register('logout', 'admin/logout');
        }
        //
        // services
        //
        elseif ($this->match('user/(?P<file>(?P<fileBase>*).$action)', ['action' => 'css'])/* and substr($this->getSelf(), -4) == '.css'*/) {
            Application::setController('templates');
        }
        elseif (substr($this->getSelf(), -4) == '.css') {
            Application::setController('templates');
            Application::setAction('cssBase');
        }
        //
        // uploads
        //
        elseif ($this->match('*/uploads(/0$dim)(/$image)$', ['dim' => '\d*x\d*[\w]*', 'image' => '.*\.(jpe?g|gif|png)'])) {
            Application::setController('uploads');
            Application::setAction('image');
        }
        //
        // frontend
        //
        else {
            // frontend autoloader
            $loader = new \Lemmon\Autoloader;
            $loader->add('$root/app/models_template/$file.php');
            $loader->register(\Lemmon\Autoloader::PREPEND);
            //
            if ($this->_extended->match($this)) {
                // user extended
            }
            elseif ($this->match('p/$id(/$paginate)(/$action)', ['id' => '\d+', 'paginate' => '\d+', 'action' => '[\w\-]+']) and $page = Page::find(['id' => $this->id]))
            {
                // load page
                $this->_page = $page;
                // subpages
                if ($page->type) {
                    // special page
                    Application::setController($page->type);
                } else {
                    // generic subpage
                    Application::setController('pages');
                    Application::setAction('subpage');
                }
            }
            //
            // module item detail
            elseif ($this->match('^$id/in/$page_id', ['id' => '\d+', 'page_id' => '\d+']) and $page = Page::find(['id' => $this->page_id, '!type' => null]))
            {
                // load page
                $this->_page = $page;
                Application::setController($page->type);
                Application::setAction('detail');
            }
            elseif ($this->match('a/$id', ['id' => '\d+']))
            {
                // posts
                Application::setController('posts');
                Application::setAction('detail');
            }
            elseif ($this->match('c/$id(/$paginate)', ['id' => '\d+', 'paginate' => '\d+']))
            {
                // categories
                Application::setController('posts');
                Application::setAction('category');
            }
            elseif (!$this->getParam(1))
            {
                // frontpage
                Application::setController('pages');
                $this->_page = Page::find(['locale_id' => $this->_site->locale_id, 'parent_id' => null]);
            }
            else
            {
                die('Route: 404');
            }

            $this->register('home', '/');
            $this->register('page', 'p/$id');
            $this->register('page_id', 'p/%1');
            $this->register('post', 'a/$id');
            $this->register('category', 'c/$id');
            $this->register('paginate', '@/@/%1');
            $this->register('module_item', '$id/in/$page.id');
        }
        //
        // user defined routes
        //
        $this->_extended->register($this);
        //
        // routes for database items
        //
        AbstractRow::setRoute($this);
    }


    function getSite()
    {
        return $this->_site;
    }


    function getPage()
    {
        return $this->_page;
    }


    function getSection($page)
    {
        if ($page->type) {
            return $this->to(':section', $page->type, $page->id);
        } else {
            return $this->to(':update', $page);
        }
    }


    function getHome()
    {
        return '/';
    }


    function getPublisher($link)
    {
        return '/publisher/public/' . $link;
    }


    function getVendor($link, $params=null)
    {
        return $this->to('vendor/' . $link, $params);
    }


    function getTemplate($link)
    {
        return $this->to('user/template/' . $link);
    }


    function getUpload($file, $dim = null)
    {
        return '/user/uploads/' . ($dim ? "0{$dim}/" : '') . $file;
    }


    function getJQuery()
    {
        return $this->getVendor('jquery/jquery-1.8.2.min.js');
    }
}
