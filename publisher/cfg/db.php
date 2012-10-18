<?php
/**
* 
*/
class Db extends \Lemmon\Db\Adapter
{


	function __construct($driver=[])
	{
		// connect
		parent::__construct([
			'host'     => DB_HOST,
			'username' => DB_USER,
			'password' => DB_PASS,
			'database' => DB_NAME,
			'encoding' => 'utf8',
		]);
	}
}
