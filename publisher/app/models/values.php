<?php

use \Lemmon\Sql\Query as SqlQuery,
    \Lemmon\Sql\Expression as SqlExpression;

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
            foreach ((new SqlQuery)->select('values')->where(['site_id' => SITE_ID, 'key LIKE ?' => $key])->all() as $item) {
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
            $res = (new SqlQuery)->select('values')->where(['site_id' => SITE_ID, 'key' => $key])->first()->value;
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
        (new SqlQuery)->replace('values')->set(['site_id' => SITE_ID, 'key' => $key, 'value' => $value])->exec();
        // update cache
        self::$_cache[$key] = $value;
    }


    static function putMany($prefix, $values)
    {
        // recursion
        function r($prefix, $values, &$in = []) {
            $i = 0;
            foreach ($values as $key => $val) {
                if (is_array($val)) {
                    // itterate children
                    r("$prefix/$key", $val, $in);
                } elseif (strlen($val)) {
                    // save to db
                    if (is_numeric($key)) {
                        $key_db = "{$prefix}/{$i}";
                        $i++;
                    } else {
                        $key_db = "{$prefix}/{$key}";
                    }
                    (new SqlQuery)->replace('values')->set(['site_id' => SITE_ID, 'key' => $key_db, 'value' => $val])->exec();
                    $in[] = $key_db;
                }
            }
            return $in;
        }
        // save new
        $in = r($prefix, $values);
        // remove old
        (new SqlQuery)->delete('values')->where(['site_id' => SITE_ID, new SqlExpression('(key = ? OR key LIKE ?)', $prefix, "{$prefix}/%"), '!key' => $in])->exec();
    }


    static function getMany($prefix) {
        $res = [];
        foreach (self::get("{$prefix}/%") as $key => $val) {
            $res0 = &$res;
            foreach (explode('/', $key) as $key0) {
                $res0 = &$res0[$key0];
            }
            $res0 = $val;
        }
        return $res[$prefix];
    }
}
