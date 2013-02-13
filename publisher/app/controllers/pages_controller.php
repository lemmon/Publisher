<?php
/**
* 
*/
class Pages_Controller extends Frontend_Controller
{


	function index()
	{
		$locale = reset(Locales::fetchActive());
		$page   = Pages::find(['locale' => $locale['id'], 'parent_id' => null])->first();
		//
		Nav::setCurrentPage($page);
		$this->data['page'] = $page;
		//
		return $this->template->display(($page->template) ?: 'default');
	}


	function subpage()
	{
		// nav
		if ($id = $this->route->id and $page = Page::find($id))
		{
			Nav::setCurrentPage($page);
			$this->data['page'] = $page;
			//
 			return $this->template->display(($page->template) ?: 'default');
		}
		else
		{
			header('HTTP/1.0 404 Not Found');
			die('404');
		}
	}
}
