<?php
/**
* 
*/
class Admin_Logout_Controller extends Application
{
	
	function index()
	{
		$this->auth->clearIdentity();
		$this->flash->notice('You have been signed out');
		return $this->request->redir(':login');
	}
}
