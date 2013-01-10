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
			Nav::setCurrentLocale($post->locale);
			$this->data['post'] = $post;
			//
			return $this->template->display('post');
		}
		else
		{
			die('404');
		}
	}
}
