<?php
/**
* 
*/
class Posts_Controller extends Frontend_Controller
{


	function detail()
	{
		return $this->template->display('post');
	}
}
