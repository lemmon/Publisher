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
}
