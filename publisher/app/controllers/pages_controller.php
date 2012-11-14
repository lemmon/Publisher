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
		$locale = reset(Locales::fetchActive());
		//
		Nav::setCurrentPage($page = Pages::find(['locale' => $locale['id']])->first());
		//
		$this->data['page'] = $page;
		//
 		return Template::display('index', self::getData(true));
	}


	function subpage()
	{
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
	}
}
