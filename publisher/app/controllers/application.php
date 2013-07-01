<?php
/**
* 
*/
class Application extends \Lemmon\Framework
{
    static $isFrontend;

    protected $config;
    protected $site;
    protected $page;


    final protected function __initApplication()
    {
        // FLASH MESSAGES
        $this->data['flash'] = $this->flash = new \Lemmon\Form\Flash($this->route);
        // /FLASH
        
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


    protected function __initSection()
    {
    }


    static function getDb()
    {
        return self::getInstance()->db;
    }
}
