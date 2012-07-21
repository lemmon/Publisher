<?php
/**
* 
*/
class Admin_Login_Controller extends Application
{
	
	function index()
	{
		if ($f=$_POST)
		{
			if (Users::doAuth($f))
			{
				$this->flash->notice('You have been successfully logged in');
				return $this->request->redir(':home');
			}
			else
			{
				$this->flash->error('Invalid email or password entered');
			}
		}
	}
}
