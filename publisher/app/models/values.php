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
        if (array_key_exists($key, self::$_cache)) {
            // load from cache
            return self::$_cache[$key];
        } else {
            // load from db
            $res = (new SqlQuery)->select('values')->where(['key' => $key] + (defined('SITE_ID') ? ['site_id' => SITE_ID] : ''))->first()->value;
            // parse Json if necessary
            if ($res{1} == ':' and preg_match('/^\w:\d+:/', $res)) {
                $res = unserialize($res);
            }
            //
            return self::$_cache[$key] = $res;
        }
    }


    static function put($key, $value)
    {
        // convert to Json if necessary
        if (is_array($value)) {
            $value = serialize($value);
        }
        // save to db
        (new SqlQuery)->replace('values')->set(['key' => $key, 'value' => $value] + (defined('SITE_ID') ? ['site_id' => SITE_ID] : ''))->exec();
        // update cache
        self::$_cache[$key] = $value;
    }
}
