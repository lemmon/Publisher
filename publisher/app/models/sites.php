<?php

use \Lemmon\Sql\Expression as SqlExpression;

/**
* 
*/
class Sites extends \Lemmon\Model\AbstractModel
{
    static $required  = ['founder_id', 'locale_id', 'locale_id', 'name', 'link', 'email'];
    static $timestamp = ['created_at', 'updated_at'];


    function __init()
    {
    }


    static function findCurrent()
    {
        return self::find()->where(new SqlExpression('link = ? OR domain = ?', '', Route::getHost()))->first();
    }
}
