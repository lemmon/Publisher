<?php

use \Lemmon\Form\Scaffold;

/**
* 
*/
class Admin_Posts_Controller extends Admin_Backend_Controller
{


	function __init()
	{
		if ($f = $_POST)
		{
			$_SESSION['defaults']['locale'] = $f['locale'];
		}
	}


	function index()
	{
		if ($data = Posts::find() and $data->count())
			$this->data['data'] = $data;
		else
			return $this->template->display('empty');
	}


	private function _getOptions()
	{
		$this->data += [
			'locales'    => Locales::fetchActive(),
			'states'     => States::getOptions(),
			'categories' => Categories::fetchActiveByLocale(),
		];
	}


	function create()
	{
		// options
		$this->_getOptions();
		// scaffolding
 		return Scaffold::create($this, [
			'default' => $_SESSION['defaults'],
		]);
	}


	function update()
	{
		// options
		$this->_getOptions();
		// scaffolding
		return Scaffold::update($this);
	}
}