<?php

use \Lemmon\Form\Scaffold;

/**
* 
*/
class Admin_Media_Controller extends Admin_Backend_Controller
{


    function index()
    {
        $this->data += [
            'data' => Media::find(),
        ];
    }


    function main()
    {
        return $this->index() ?: $this->template->display('index');
    }


    function create()
    {
        return $this->_res(Scaffold::create($this, ['redir' => '/'], $item), function() use ($item){
            $this->flash->getNotices();
            return [
                'file' => (string)$this->route->getUpload($item->file),
                'file_id' => $item->id,
            ];
        });
    }


    function update()
    {
        // scaffolding
        return Scaffold::update($this);
    }


    function frame()
    {
        die('--frame?');
    }
}
