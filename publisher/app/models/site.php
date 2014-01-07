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
        if (self::$_current) {
            // site already found
            return self::$_current;
        } else {
            // find site...
            $host = $_SERVER['HTTP_HOST'];
            $host_alt = (substr($host, 0, 4) == 'www.') ? substr($host, 4) : 'www.' . $host;
            // ...in db
            if ($site = self::find(['host' => [$host, $host_alt]])) {
                return self::$_current = $site;
            }
        }
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
