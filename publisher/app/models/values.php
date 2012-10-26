<?php

use \Lemmon\Sql\Query as SqlQuery;

/**
* 
*/
class Values
{
	static private $_cache = [];


	static function get($key)
	{
		return array_key_exists($key, self::$_cache) ? self::$_cache[$key] : self::$_cache[$key] = (new SqlQuery)->select('values')->where('key', $key)->first()->value;
	}


	function set($key, $val)
	{
		
	}
}
