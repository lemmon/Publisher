<?php

use \Lemmon\Form\Scaffold;

/**
* 
*/
abstract class Admin_AbstractModule_Controller extends Admin_Backend_Controller
{
    protected $config = [];


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
        if ($page = $this->getPage()) {
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
        } else {
            // n/a
            return $this->route->notFound();
        }
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
            // n/a
            return $this->route->notFound();
        }
    }


    function delete()
    {
        if ($item = call_user_func([Scaffold::getModelName($this, $this->getConfig()), 'find'], $this->route->id)->first() and $page = $this->getPage($item->page_id)) {
            // on POST
            if ($f = $_POST) {
                return $this->_res(function() use ($f, $page, $item){
                    if ($f['delete'] and $item->delete()) {
                        $this->flash->setNotice('Entry deleted successfully');
                    } else {
                        $this->flash->setError('Entry has NOT been deleted');
                    }
                    return $this->route->getSection($page);
                });
            }
            //
            $this->data['item'] = $item;
        } else {
            // n/a
            return $this->route->notFound();
        }
    }
}
