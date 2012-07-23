<?php
/**
* 
*/
class AuthStorage implements Zend_Auth_Storage_Interface
{


	public function isEmpty()
	{
		return !array_key_exists('__AUTH__', $_SESSION);
	}


	public function read()
	{
		return $_SESSION['__AUTH__'];
	}


	public function write($contents)
	{
		$_SESSION['__AUTH__'] = $contents;
	}


	public function clear()
	{
		unset($_SESSION['__AUTH__']);
	}
}
