<?php

use \Lemmon\Form\Scaffold;

/**
* 
*/
class Admin_Pages_Controller extends Admin_Backend_Controller
{


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
        if ($id = $this->route->id and $page = Page::find($id)) {
            // options
            $this->_getOptions($page->locale_id);
            // scaffolding
            return $this->_res(Scaffold::update($this, [
                'redir' => function($item){
                    return $this->route->getSection($item);
                },
            ]));
        } else {
            // page not found
            die('Page not found.');
        }
    }


    function menu()
    {
        if ($locale_id = $this->route->hash and $menu_id = $this->route->getParam(5) and $menu = Template::getConfig('menus')[$menu_id]) {
            
            // ok
            $this->data += [
                'menu'   => $menu + ['id' => $menu_id],
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
