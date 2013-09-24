<?php

use \Lemmon\Form\Scaffold;

/**
* 
*/
abstract class Admin_AbstractModule_Controller extends Admin_Backend_Controller
{
    protected $config = [];


    protected function getPage($id)
    {
        if ($page = Page::find($id)) {
            // page found
            return $this->data['page'] = $this->page = $page;
        } else {
            // page not found
            throw new \Exception('Page not found.');
        }
    }


    protected function getConfig()
    {
        return $this->config;
    }


    function index()
    {
        // page
        $page = $this->getPage($this->route->page);
        // data
        $this->data += [
            'data' => call_user_func([Scaffold::getModelName($this, $this->getConfig()), 'find'], ['page_id' => $page->id]),
        ];
    }


    private function _getOptions()
    {
        $this->data += [
            'states' => States::getOptions(),
        ] + $this->getOptions();
    }


    protected function getOptions()
    {
        return [];
    }


    function create()
    {
        // page
        $page = $this->getPage($this->route->id);
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
        ] + $this->getConfig()));
    }


    function update()
    {
        if ($item = call_user_func([Scaffold::getModelName($this, $this->getConfig()), 'find'], $this->route->id)->first() and $page = $this->getPage($item->page_id)) {
            // options
            $this->_getOptions();
            // scaffolding
            return $this->_res(Scaffold::update($this, [
                'redir' => function($item){
                    return (string)$this->route->getSection($item->getPage());
                },
            ] + $this->getConfig()));
        } else {
            // Item not found
            die('404: Entry not found');
        }
    }
}
