<?php
/**
* 
*/
class Template extends \Lemmon\Template\Template
{
    private static $_cache = [];


    private static function _getConfig()
    {
        if (array_key_exists('config_template', self::$_cache)) {
            return self::$_cache['config_template'];
        } else {
            return self::$_cache['config_template'] = (file_exists($file = USER_DIR . '/config.yml') and $res = \Symfony\Component\Yaml\Yaml::parse($file)) ? $res : [];
        }
    }


    static function getConfig($section = null)
    {
        return $section ? self::_getConfig()[$section] : self::_getConfig();
    }


    static function sanitizeHtml($html)
    {
        // remove &nbsp+space
        $html = preg_replace('/&nbsp;\s+/', '&nbsp;', $html);
        // remove whitespace bullshit
        do { $html = trim(preg_replace('#<(\w+)[^>]*>(\xC2\xA0|\s+)*</\1>#', '', $html, -1, $n)); } while ($n);
        //
        return $html;
    }
}
