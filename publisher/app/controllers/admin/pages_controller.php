<?php

use \Lemmon\Form\Scaffold,
    \Lemmon\Sql\Query as SqlQuery;

/**
* 
*/
class Admin_Pages_Controller extends Admin_Backend_Controller
{


    static function __type()
    {
        return 'page';
    }


    function index()
    {
    }


    function main()
    {
        if (Pages::find()->count()) {
            // has pages
            $this->data['pages']   = Pages::fetchAllByLanguage();
            $this->data['locales'] = Locales::fetchActive($this->site->locale_id);
        } else {
            // need to create first page
            return $this->route->to(':admin/create');
        }
    }


    private function _getOptions($locale_id)
    {
        $this->data += [
            'pages'  => Pages::fetchTreeInLocale($locale_id),
            'states' => States::getOptions(),
        ];
    }


    function create()
    {
        if ($locale_id = $this->route->hash and $locale = Locales::fetch($locale_id)) {

            // options
            $this->_getOptions($locale['id']);
            // scaffolding
            return $this->_res(Scaffold::create($this, [
                'default' => [
                    'state_id' => 1,
                ],
                'force' => [
                    'locale_id' => $locale_id,
                ],
                'redir' => function($item){
                    return $this->route->getSection($item);
                },
            ]));

        } elseif ($f = $_POST and array_key_exists('locale_id', $f)) {

            // creating first page
            return $this->_res(Scaffold::create($this, [
                'redir' => function($item){
                    return $this->route->getSection($item);
                },
            ]));

        } else {

            // locale not found
            $this->data += [
                'locales' => Locales::fetchInactiveWithPreferred(),
                'states' => States::getOptions(),
            ];
            $this->data['f'] = [
                'state_id' => 1,
                'locale_id' => !Pages::find(['locale_id' => $this->site->locale_id])->count() ? $this->site->locale_id : null,
            ];
            #return $this->template->display('empty');

        }
    }


    function update()
    {
        // page
        $page = $this->getPage();
        //
        if ($page->type or $page->template) {
            if (file_exists(USER_DIR . '/app/views/' . self::getController() . '/' . $page->getTemplateName() . '.' . self::getAction() . '.html')) {
                $this->template->display($page->getTemplateName() . '.' . self::getAction());
            }
        }
        // options
        $this->_getOptions($page->locale_id);
        // scaffolding
        return $this->_res(Scaffold::update($this, [
            'redir' => function($item){
                return $this->route->getSection($item);
            },
        ]));
    }


    function delete()
    {
        // page
        $page = $this->getPage();
        // on POST
        if ($f = $_POST) {
            return $this->_res(function() use ($f, $page){
                if ($f['delete'] and $page->delete()) {
                    $this->flash->setNotice('Page deleted successfully');
                } else {
                    $this->flash->setError('Page has NOT been deleted');
                }
                return $this->route->to(':admin/section');
            });
        }
    }


    function menu()
    {
        if ($locale_id = $this->route->hash and $menu_id = $this->route->getParam(5) and $menus = Template::getConfig('menus') and array_key_exists($menu_id, $menus)) {
            
            $menu = (array)$menus[$menu_id];
            // on POST
            if ($f = $_POST) {
                return $this->_res(function() use ($f, $menu_id, $locale_id){
                    // save menu
                    foreach ((array)$f['pages'] as $i => $page_id) {
                        (new SqlQuery)->replace('pages_to_menus')->set([
                            'site_id'   => SITE_ID,
                            'locale_id' => $locale_id,
                            'menu_id'   => $menu_id,
                            'page_id'   => $page_id,
                            'top'       => $i + 1,
                        ])->exec();
                    }
                    // remove old instances
                    (new SqlQuery)->delete('pages_to_menus')->where([
                        'site_id'   => SITE_ID,
                        'locale_id' => $locale_id,
                        'menu_id'   => $menu_id,
                        '!page_id'  => $f['pages'],
                    ])->exec();
                    // redir
                    $this->flash->setNotice('Menu updated successfully');
                    return $this->route->getSelf();
                });
            }
            // fetch menu
            $pages = [];
            foreach ((new SqlQuery)->select('pages_to_menus')->where(['site_id' => SITE_ID, 'locale_id' => $locale_id, 'menu_id' => $menu_id])->order('top')->distinct('page_id') as $page_id) {
                $pages[$page_id] = Page::find($page_id);
            }
            // default data
            $this->data += [
                'menu'   => $menu + ['id' => $menu_id, 'pages' => $pages],
                'pages'  => Pages::fetchLinearInLocale($locale_id),
            ];
            
        } else {
            
            // 404
            die('404');
            
        }
    }


    /** /
    function rebuild()
    {
        Pages::rebuildTree();
        die('--ok');
    }
    /**/
}
