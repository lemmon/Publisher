<?php

use \Lemmon\Form\Scaffold;

/**
* 
*/
class Admin_Media_Controller extends Admin_Backend_Controller
{


	function index()
	{
		if ($data = Media::find() and $data->count())
		{
			$this->data += [
				'data' => $data,
			];
		}
		else
		{
			return $this->template->display('empty');
		}
	}


	function frame()
	{
		switch ($this->route->getParam(4))
		{
			case 'images':
				$this->data['data'] = Media::find(['file_mime_type' => 'image']);
				return $this->template->display('frame/images');
				break;
			default:
				throw new \Exception('wtf?');
				break;
		}
	}


	function create()
	{
		// scaffolding
		return Scaffold::create($this);
	}


	function update()
	{
		// scaffolding
		return Scaffold::update($this);
	}
}
