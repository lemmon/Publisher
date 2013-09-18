<?php
/**
* 
*/
class Route extends \Lemmon\Route
{
    private $_extend;
    private $_site;
    private $_page;


    protected function __initAdmin()
    {
        if ($this->match('$controller(/$page)(/$action(/$id)(/$hash))', ['controller' => 'admin\/[\w\-]+', 'action' => '[\w\-]+', 'id' => '\d+', 'hash' => '[\w\-]+', 'page' => '\d+'])) {
            // general CRUD
        } else {
            Application::setController('admin/index');
        }

        $this->register('admin/home', 'admin');

        $this->register('admin/section', 'admin/@%1/%2/%3');
        $this->register('admin/action', 'admin/@/%1/%2/%3');
        $this->register('admin/page', 'admin/@/%1');

        $this->register('admin/index', 'admin/@$type/$id');
        $this->register('admin/create', 'admin/@/create');
        $this->register('admin/update', 'admin/@$type/update/$id');
        $this->register('admin/delete', 'admin/@$_section/delete/$id');
        $this->register('admin/crud', 'admin/@$_section/$action/$id');

        $this->register('admin/login', 'admin/login');
        $this->register('admin/logout', 'admin/logout');
    }


    protected function __initServices()
    {
        //
        // services
        //
        if ($this->match('user/($linkId/)(?P<file>(?P<fileBase>template/*).$action)$', ['linkId' => '[^/]+/[^/]+', 'action' => 'css'])/* and substr($this->getSelf(), -4) == '.css'*/) {
            Application::setController('templates');
            return 1;
        }
        elseif (substr($this->getSelf(), -4) == '.css') {
            Application::setController('templates');
            Application::setAction('cssBase');
            return 1;
        }
        //
        // uploads
        //
        elseif ($this->match('*/uploads(/0$dim)(/$image)$', ['dim' => '\d*x\d*[\w]*', 'image' => '.*\.(jpe?g|gif|png)'])) {
            Application::setController('uploads');
            Application::setAction('image');
            return 1;
        }
    }


    protected function __initFrontend()
    {
        //
        // autoloader
        //
        $loader = new \Lemmon\Autoloader;
        $loader->add('$root/app/models_template/$file.php');
        $loader->register(\Lemmon\Autoloader::PREPEND);
        //
        // routes
        //
        if ($this->_extended->match($this)) {
            // user extended
        }
        elseif ($this->match('p/($id|$slug)(/$paginate)(/$action)', ['id' => '\d+', 'slug' => '[\w\-]+', 'paginate' => '\d+', 'action' => '[\w\-]+']) and $page = Page::find($this->id ? ['id' => $this->id] : ['redirect' => $this->slug])) {
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
        elseif ($this->match('^$id/in/$page_id', ['id' => '\d+', 'page_id' => '\d+']) and $page = Page::find(['id' => $this->page_id, '!type' => null])) {
            // load page
            $this->_page = $page;
            Application::setController($page->type);
            Application::setAction('detail');
        }
        elseif ($this->match('a/$id', ['id' => '\d+'])) {
            // posts
            Application::setController('posts');
            Application::setAction('detail');
        }
        elseif ($this->match('c/$id(/$paginate)', ['id' => '\d+', 'paginate' => '\d+'])) {
            // categories
            Application::setController('posts');
            Application::setAction('category');
        }
        elseif (!$this->getParam(1)) {
            // frontpage
            Application::setController('pages');
            $this->_page = Page::find(['locale_id' => $this->_site->locale_id, 'parent_id' => null]);
        }
        else {
            die('Route: 404');
        }
    }


    protected function __init()
    {
        $this->_extended = new RouteExtended;
        //
        // site
        //
        if ($site = Site::findCurrent()) {
            $this->_site = $site;
            define('SITE_ID', $site->id);
            define('USER_DIR', BASE_DIR . '/user/' . $site->getLink());
        } else {
            // site not found
            die('Route/ Site not found');
        }
        //
        // user uploads
        \Lemmon\Model\Schema::setDefaultUploadDir(USER_DIR . '/uploads');
        //
        //
        // autoloader
        //
        $loader = new \Lemmon\Autoloader;
        $loader->addMask('*_Controller', function($class){
            return USER_DIR . '/app/controllers/' . strtolower(str_replace('__', DIRECTORY_SEPARATOR, preg_replace('/(.)([A-Z])/u', '$1_$2', substr($class, 0, -11)))) . '_controller.php';
        });
        $loader->add(USER_DIR . '/app/models/$file.php');
        $loader->add(USER_DIR . '/app/services/$file.php');
        $loader->register(\Lemmon\Autoloader::PREPEND);
        //
        // backend
        //
        if ($this->getParam(1) == 'admin') {
            $this->__initAdmin();
        }
        //
        // services
        //
        elseif ($this->__initServices()) {
            return;
        }
        //
        // frontend
        //
        else {
            $this->__initFrontend();
        }
        //
        // paths
        //
        $this->register('home', '/');
        $this->register('page', 'p/$id');
        $this->register('page_id', 'p/%1');
        $this->register('page_slug', 'p/$redirect');
        $this->register('page_redirect', '$redirect');
        $this->register('post', 'a/$id');
        $this->register('category', 'c/$id');
        $this->register('paginate', '@/@/%1');
        $this->register('module_item', '$id/in/$page.id');
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
            return $this->to(':admin/section', $page->type, $page->id);
        } else {
            return $this->to(':admin/update', $page);
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


    function getVendor($link, $params = null)
    {
        return $this->to('vendor/' . $link, $params);
    }


    function getTemplate($link)
    {
        return $this->_site->link_id ? $this->to('user/' . $this->_site->getLink() . '/template/' . $link) : $this->to('user/template/' . $link);
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
