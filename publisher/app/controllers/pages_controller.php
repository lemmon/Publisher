<?php
/**
* 
*/
class Pages_Controller extends Frontend_Controller
{


	function index()
	{
		$locale = reset(Locales::fetchActive());
		$page   = Pages::find(['locale' => $locale['id']])->first();
		//
		Nav::setCurrentPage($page);
		$this->data['page'] = $page;
		//
		if ($_template = $page->template)
 			return $this->template->display($_template);
	}


	function subpage()
	{
		// nav
		if ($id = $this->route->id and $page = Page::find($id))
		{
			Nav::setCurrentPage($page);
			$this->data['page'] = $page;
			//
			if ($_template = $page->template)
	 			return $this->template->display($_template);
		}
		else
		{
			die('404');
		}
	}
}
