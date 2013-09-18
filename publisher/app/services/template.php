<?php
/**
* 
*/
class Template
{
    private static $_cache = [];


    private static function _getConfig()
    {
        if (array_key_exists('config_template', self::$_cache)) {
            return self::$_cache['config_template'];
        } else {
            return self::$_cache['config_template'] = (file_exists($file = USER_DIR . '/template/config.yml') and $res = \Symfony\Component\Yaml\Yaml::parse($file)) ? $res : [];
        }
    }


    static function getConfig($section = null)
    {
        return $section ? self::_getConfig()[$section] : self::_getConfig();
    }
}
