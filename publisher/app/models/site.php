<?php

use \Lemmon\Sql\Expression as SqlExpression;

/**
* 
*/
class Site extends \Lemmon\Model\AbstractRow
{
    static protected $model = 'Sites';

    static private $_current;


    static function findCurrent()
    {
        if (!($res = self::$_current)) {
            $server_name = preg_replace('/\.dev$/', '', $_SERVER['SERVER_NAME']);
            return self::$_current = Sites::find()->where(new SqlExpression('link = ? OR domain = ?', '', $server_name))->first();
        } else {
            return $res;
        }
    }
}
