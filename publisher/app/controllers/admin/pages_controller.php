<?php
/**
* 
*/
class Admin_Pages_Controller extends \Lemmon\Model\Scaffold
{


	function index()
	{
		if (Pages::find()->count())
		{
			$this->data['pages']   = Pages::fetchActiveByLanguage();
			$this->data['locales'] = Locales::fetchActive();
		}
		else
		{
			return \Lemmon\Template::display('empty', self::getData(true));
		}
	}


	function create()
	{
		$this->data['pages']   = Pages::fetchActiveByLanguage();
		$this->data['locales'] = Locales::findAllWithPreferred();
		return parent::create();
	}


	function update()
	{
		$this->data['pages']   = Pages::fetchActiveByLanguage();
		$this->data['locales'] = Locales::findAllWithPreferred();
		return parent::update();
	}


	function rebuild()
	{
		Pages::rebuildTree();
		die('--ok');
	}
}
