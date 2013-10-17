<?php

use \Lemmon\Sql\Expression as SqlExpression;

/**
* 
*/
class Site extends \Lemmon\Model\AbstractRow
{
    static protected $model = 'Sites';

    private static $_current;

    private $_cache = [];


    static function findCurrent()
    {
        return self::$_current ?: (self::$_current = self::find(['host' => $_SERVER['HTTP_HOST']]));
    }


    function getLink()
    {
        if ($this->link_id) {
            return $this->link_id;
        } elseif (!array_key_exists('link', $this->_cache)) {
            return $this->_cache['link'] = preg_replace('/^([^\.]+)\.(.*)(.dev)?(:\d+)?$/U', '$2/$1', $this->host);
        } else {
            return $this->_cache['link'];
        }
    }
}
