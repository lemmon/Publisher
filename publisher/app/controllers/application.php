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
        Env::setDev(true);
        //
        // flash
        $this->data['flash'] = $this->flash = new \Lemmon\Form\Flash($this->route);
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
        // section
        return $this->__initSection();
    }


    protected function sanitize($in, $remove_empty = false)
    {
        return $this->_sanitize($in, $remove_empty);
    }


    private function _sanitize($in, $remove_empty)
    {
        if (is_array($in)) {
            foreach ($in as $key => $val) {
                if ($res = $this->_sanitize($val, $remove_empty) or !($remove_empty and ($res === null or $res === []))) {
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


    protected function noCache()
    {
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
    }
}
