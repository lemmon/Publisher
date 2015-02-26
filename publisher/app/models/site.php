<?php

use \Lemmon\Sql\Query as SqlQuery,
    \Lemmon\Sql\Expression as SqlExpression;

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
                // check for valid host
                if ($_SERVER['HTTP_HOST'] != $site->host and !$_POST) {
                    header('Location: ' . (($_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://') . $site->host . $_SERVER['REQUEST_URI'], true, 301);
                    exit;
                }
                // all ok
                return self::$_current = $site;
            }
            // alternative host
            elseif ($site_alias = (new SqlQuery)->select('sites_aliases')->where(['host' => [$host, $host_alt]])->first() and $site = self::find($site_alias->id)) {
                // check for valid host
                if ($_SERVER['HTTP_HOST'] != $site_alias->host and !$_POST) {
                    header('Location: ' . (($_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://') . $site_alias->host . $_SERVER['REQUEST_URI'], true, 301);
                    exit;
                }
                // all ok
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


    function getAliases()
    {
        if (!array_key_exists('aliases', $this->_cache)) {
            return $this->_cache['aliases'] = (new SqlQuery)->select('sites_aliases')->where(['id' => $this->id])->pairs('host', 'locale_id');
        } else {
            return $this->_cache['aliases'];
        }
    }
}
