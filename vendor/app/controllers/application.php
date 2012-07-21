<?php
/**
* 
*/
class Application extends \Lemmon\Framework
{


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
		// authentication
		if ($user=Users::getCurrent())
		{
			// user ok
			$this->data['user'] = $user;
		}
		elseif ((string)$this->route->getSelf() != (string)$this->route->to(':login'))
		{
			// must login
			return $this->request->redir(':login');
		}
	}
}
