<?php

use \Lemmon\Form\Scaffold;

/**
* 
*/
class Admin_Pages_Controller extends Admin_Backend_Controller
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
		if (Pages::find()->count())
		{
			$this->data['pages']   = Pages::fetchActiveByLanguage();
			$this->data['locales'] = Locales::fetchActive();
		}
		else
		{
			return $this->template->display('empty');
		}
	}


	private function _getOptions()
	{
		$this->data += [
			'pages'   => Pages::fetchActiveByLanguage(),
			'locales' => Locales::fetchAllWithPreferred(),
		];
	}


	function create()
	{
		// options
		$this->_getOptions();
		// scaffolding
 		return Scaffold::create($this, [
			'default' => (array)$_SESSION['defaults'] + ['state_id' => 1],
		]);
	}


	function update()
	{
		// options
		$this->_getOptions();
		// scaffolding
		return Scaffold::update($this);
	}


	/** /
	function rebuild()
	{
		Pages::rebuildTree();
		die('--ok');
	}
	/**/
}
