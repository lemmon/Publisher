<?php
/**
* 
*/
class Pages_Controller extends Application
{


	function __init()
	{
		$res = parent::__init();

		Pages::lock();
		$this->data['nav']  = new Nav();
		
		return $res;
	}


	function index()
	{
		Nav::setCurrentPage($page = Pages::find(['language_id' => Language::findDefault()->id])->first());
		//
		$this->data['page'] = $page;
		//
 		return Template::display('index', self::getData(true));
	}


	function subpage()
	{
		Pages::lock();
		// nav
		if ($id = $this->route->id and $page = Page::find($id))
		{
			Nav::setCurrentPage($page);
			//
			$this->data['page'] = $page;
			//
	 		return Template::display('index', self::getData(true));
		}
		else
		{
			die('404');
		}
		die('--3');
	}
}
