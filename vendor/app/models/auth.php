<?php
/**
* 
*/
class Auth implements Zend_Auth_Adapter_Interface
{
	private $_username;
	private $_password;


	public function __construct($username, $password)
	{
		$this->_username = $username;
		$this->_password = $password;
	}


	public function authenticate()
	{
		return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $this->_username);
	}
}
