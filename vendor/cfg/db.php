<?php
/**
* 
*/
class Db/* extends Lemmon\Db\Connection*/
{
	private $_adapter;


	function __construct()
	{
		$this->_adapter = $adapter = Zend_Db::factory('Pdo_Mysql', [
			'host'     => '127.0.0.1',
			'username' => 'root',
			'password' => '',
			'dbname'   => 'publisher',
			'charset'  => 'utf8',
		]);
		
		Zend_Db_Table_Abstract::setDefaultAdapter($adapter);
	}


	function getAdapter()
	{
		return $this->_adapter;
	}
}
