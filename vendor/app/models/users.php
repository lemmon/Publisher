<?php
/**
* 
*/
class Users extends Lemmon_Model_Auth
{


	protected function define()
	{
		$this->required('name', 'email');
		
		$this->timeStampable();
	}


	protected function onAuth($f)
	{
		$this->findLike('email', $f['email']);
		$this->find('password', md5($f['password']));
		return $this;
	}


	protected function onBeforeCreate(&$f)
	{
		// password
		if ($f['password'])
		{
			$f['password'] = md5($f['password']);
		}
		else
		{
			Application::getInstance()->flashError('Missing field Password')->flashErrorField('password');
			return false;
		}
	}


	protected function onBeforeUpdate(&$f)
	{
		// password
		if ($f['password'])
		{
			$f['password'] = md5($f['password']);
		}
		else
		{
			$f['password'] = $this->password;
		}

	}
}
