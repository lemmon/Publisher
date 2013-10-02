<?php
/**
* 
*/
class Application extends \Lemmon\Framework
{
    protected $config;
    protected $site;
    protected $page;
    protected $i18n;
    protected $cache;

    private $_cache = [];


    protected function __initSection() {}


    final protected function __initApplication()
    {
        // FLASH MESSAGES
        $this->data['flash'] = $this->flash = new \Lemmon\Form\Flash($this->route);
        // /FLASH
        
        Env::setDev(true);
        
        //
        // site
        if ($site = $this->route->getSite()) {
            // site found
            $this->data['site'] = $this->site = $site;
        }
        //
        // page
        $this->page = $this->route->getPage();
        //
        // i18n
        $this->i18n = new \Lemmon\I18n\I18n();
        //
        // section
        return $this->__initSection();
    }


    static function getDb()
    {
        return self::getInstance()->db;
    }


    protected function sanitize($in, $remove_empty = false)
    {
        function r($in, $remove_empty) {
            if (is_array($in)) {
                foreach ($in as $key => $val) {
                    if ($res = r($val, $remove_empty) or !($remove_empty and ($res === null or $res === []))) {
                        $in[$key] = $res;
                    } else {
                        unset($in[$key]);
                    }
                }
            } else {
                $in = trim($in);
                if (strlen($in) == 0) $in = null;
            }
            return $in;
        }
        return r($in, $remove_empty);
    }


    protected function noCache()
    {
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
    }
}
