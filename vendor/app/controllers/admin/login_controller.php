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
			$authAdapter = new Auth($f['email'], $f['password']);
			
			$res = $this->auth->authenticate($authAdapter);
			
			switch ($res)
			{
				case Zend_Auth_Result::SUCCESS:
					$this->flash->notice('You have been successfully logged in');
					return $this->request->redir(':home');
					break;
				
				default:
					$this->flash->error('Invalid email or password entered');
					break;
			}
		}
	}
}
