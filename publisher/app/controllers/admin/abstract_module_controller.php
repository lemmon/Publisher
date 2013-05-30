<?php

use \Lemmon\Form\Scaffold;

/**
* 
*/
abstract class Admin_AbstractModule_Controller extends Admin_Backend_Controller
{


    protected function getPage($page_id = null)
    {
        if (($page_id or $page_id = $this->route->page) and $page = Page::find($page_id)) {
            $this->data['page'] = $page;
            return $page;
        }
    }


    function index()
    {
        if ($page = $this->getPage()) {
            // Page found okay
            $this->data += [
                'data' => call_user_func([Scaffold::getModelName($this), 'find'], ['page_id' => $page->id]),
            ];
        } else {
            // Page not found
            die('Error.');
        }
    }


    private function _getOptions()
    {
        $this->data += $this->getOptions() + [
            'states' => States::getOptions(),
        ];
    }


    protected function getOptions()
    {
        return [];
    }


    function create()
    {
        if ($page = $this->getPage($this->route->id)) {
            // options
            $this->_getOptions();
            // scaffolding
            return $this->_res(Scaffold::create($this, [
                'redir' => function($item){
                    return (string)$this->route->getSection($item->getPage());
                },
                'default' => [
                    'state_id'  => 1,
                ],
                'force' => [
                    'page_id'   => $page->id,
                    'locale_id' => $page->locale_id,
                ],
            ]));
        } else {
            // Page not found
            die('Error.');
        }
    }


    function update()
    {
        if ($item = call_user_func([Scaffold::getModelName($this), 'find'], $this->route->id)->first() and $page = $this->getPage($item->page_id)) {
            // options
            $this->_getOptions();
            // scaffolding
            return $this->_res(Scaffold::update($this, [
                'redir' => function($item){
                    return (string)$this->route->getSection($item->getPage());
                },
            ]));
        } else {
            // Page not found
                die('Error.');
        }
    }
}
