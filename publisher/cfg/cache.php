<?php
/**
* 
*/
class Cache
{
    private $_file;
    private $_allow;
    private $_cached;


    function __construct()
    {
        //
        // vars
        $host = $_SERVER['HTTP_HOST'];
        $uri = $_SERVER['REQUEST_URI'];
        $route = substr($uri, 1);
        $this->_file = $file = BASE_DIR . '/cache/contents/' . join(str_split(substr(md5($host), 0, 4), 2), '/') . '/' . str_replace(':', ';', $host) . '/' . str_replace('/', ',,', $route) . '_.html';
        //
        // customised cache
        if (DO_CACHING === true and !$_POST and !$_GET and !$_SESSION['__FLASH__']['messages']) {
            if (file_exists($file)) {
                readfile($file);
                $this->_cached = true;
                exit;
            } else {
                $this->_allow = true;
            }
        }
    }


    function getFile()
    {
        return $this->_file;
    }


    function put($contents)
    {
        if ($file = $this->_file and $this->_allow) {
            // create cache directory
            if (!is_dir($dir = dirname($file)) and !mkdir($dir, 0777, true)) {
                throw new Exception('Error creating cache directory.');
            }
            // cache and return dynamic contents
            $res = file_put_contents($file, $contents);
        }
        return $contents;
    }


    function flush()
    {
        foreach (glob(dirname($this->_file) . '/*.html') as $file) {
            @unlink($file);
        }
    }
}
