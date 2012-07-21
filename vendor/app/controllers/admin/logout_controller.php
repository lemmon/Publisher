<?php
/**
* 
*/
class Admin_Logout_Controller extends Application
{
	
	function index()
	{
		Admins::clearCurrent();
		return $this->route->redir(':home');
	}
}
