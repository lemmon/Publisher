<?php
/**
* 
*/
class Posts_Controller extends Frontend_Controller
{


	function detail()
	{
		// nav
		if ($id = $this->route->id and $post = Post::find($id))
		{
			// current page
			Nav::setCurrentPage(
				$page = Page::find(['template' => 'posts'])->isActive(false)
			);
			
			// template
			$this->data += [
				'page' => $page,
				'post' => $post,
			];
			return $this->template->display('post');
		}
		else
		{
			die('404');
		}
	}


	function category()
	{
		// nav
		if ($id = $this->route->id and $category = Category::find($id))
		{
			// current page
			Nav::setCurrentPage(
				$page = Page::find(['template' => 'posts'])->isActive(false)
			);
			
			// template
			$this->data += [
				'page'     => $page,
				'category' => $category,
			];
			return $this->template->display('category');
		}
		else
		{
			die('404');
		}
	}
}
