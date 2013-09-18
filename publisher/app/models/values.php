<?php

use \Lemmon\Sql\Query as SqlQuery;

/**
* 
*/
class Values
{
    private static $_cache = [];


    static function get($key)
    {
        // load many
        if (strpos($key, '%') !== false) {
            $many = [];
            foreach ((new SqlQuery)->select('values')->where(['key LIKE ?' => $key] + (defined('SITE_ID') ? ['site_id' => SITE_ID] : ''))->all() as $item) {
                $res = $item->value;
                if ($res{1} == ':' and preg_match('/^\w:\d+:/', $res)) {
                    $res = unserialize($res);
                }
                $many[$item->key] = $res;
            }
            return $many;
        }
        // load from cache
        elseif (array_key_exists($key, self::$_cache)) {
            return self::$_cache[$key];
        }
        // load from db
        else {
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
