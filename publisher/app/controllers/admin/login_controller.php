<?php
/**
* 
*/
class Admin_Login_Controller extends Application
{
	
	function index()
	{
		/* * /
		dump($this->auth->authenticate('jpelak@gmail.com', 'fuckme'));
		dump($this->auth->hasIdentity());
		dump($this->auth->getIdentity());
		dump($this->auth);
		dump($_SESSION);
		dump($this->auth->clearIdentity());
		dump($this->auth->hasIdentity());
		dump($_SESSION);
		die('--auth');
		/* */

		/* * /
		$pass = 'fuckme';
		$hash = Auth::encrypt($pass);
		
		dump($hash);
		dump(\Lemmon\Auth\Service::check($hash, $pass));
		die('--login');
		/* */
		
		// on Post
		if ($f=$_POST)
		{
 			if ($this->auth->authenticate($f['email'], $f['password']))
			{
				// ok
				$this->auth->storeIdentity();
				$this->flash->notice('You have been successfully logged in');
				return $this->request->redir(':home');
			}
			else
			{
				$this->flash->error('Invalid email or password entered');
			}
		}
		// clear current identity if necessary
		elseif ($this->auth->hasIdentity())
		{
			$this->auth->clearIdentity();
		}
	}
}
