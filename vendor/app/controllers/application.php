<?php
/**
* 
*/
class Application extends \Lemmon\Framework
{
	protected $auth;
	protected $user;


	function __init()
	{
		if ($this->route->getParam(1)=='admin') return $this->__initBackend();
		else                                    return $this->__initFrontend();
	}


	private function __initFrontend()
	{
	}


	private function __initBackend()
	{
		// auth
		$this->auth = $auth = Zend_Auth::getInstance()->setStorage(new AuthStorage);
		
		if ($auth->hasIdentity())
		{
			// user logged in
			$user = new Users;
			$user = $user->fetchRow($user->select()->where('email LIKE ?', $auth->getIdentity()));
			//
			if ($user)
			{
				// user ok
				$this->data['user'] = $this->user = $user;
			}
			else
			{
				// user not valid in db
				$this->auth->clearIdentity();
				$this->flash->error('User not found in database');
				return $this->request->redir(':login');
			}
		}
		elseif ((string)$this->route->getSelf() != (string)$this->route->to(':login'))
		{
			// must login
			$this->flash->error('You have to be logged in to access admin area');
			return $this->request->redir(':login');
		}
	}
}
