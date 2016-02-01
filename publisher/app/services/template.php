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
        if ($html) {
            // sanitize
            $html = preg_replace('#[ \r\t]+\n[ \t]*#', "\n", $html);
            $html = preg_replace('#<!--.*-->#muU', '', $html);
            $html = preg_replace('#&nbsp;#', ' ', $html);
            $html = preg_replace('#\s{2,}#', ' ', $html);
            $html = preg_replace('#</(strong|b|em|i)><\1>#', '', $html);
            $html = preg_replace('#\s+</#', '</', $html);
            $html = preg_replace('#(<(ul|ol|li|p|div)>)\s+#', '\1', $html);
            $html = preg_replace('#(</(ul|ol|li|p|div)>)\s+#', '\1', $html);
            $html = str_replace('„', '&ldquo;', $html);
            $html = str_replace('“', '&rdquo;', $html);
            // remove whitespace bullshit
            do { $html = trim(preg_replace('#<(\w+)[^>]*>(\xC2\xA0|\s+)*</\1>#', '', $html, -1, $n)); } while ($n);
            //
            return $html;
        }
    }
}
