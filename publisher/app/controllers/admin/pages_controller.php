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
			$this->data += Pages::fetchActiveWithLanguages();
		}
		else
		{
			return \Lemmon\Template::display('empty', self::getData(true));
		}
	}


	function create()
	{
		$this->data += Pages::fetchActiveWithLanguages();
		return parent::create();
	}


	function update()
	{
		$this->data += Pages::fetchActiveWithLanguages();
		return parent::update();
	}


	function rebuild()
	{
		Pages::rebuildTree();
		return '--ok';
	}
}
