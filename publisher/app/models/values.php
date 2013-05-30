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
        return array_key_exists($key, self::$_cache)
             ? self::$_cache[$key]
             : self::$_cache[$key] = (new SqlQuery)->select('values')->where(['key' => $key] + (defined('SITE_ID') ? ['site_id' => SITE_ID] : ''))->first()->value;
    }


    static function put($key, $value)
    {
        (new SqlQuery)->insert('values')->set(['key' => $key, 'val' => $value] + (defined('SITE_ID') ? ['site_id' => SITE_ID] : ''));
        self::$_cache[$key] = $value;
    }
}
