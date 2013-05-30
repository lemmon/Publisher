<?php
/**
* 
*/
class Application extends \Lemmon\Framework
{
    static $isFrontend;

    protected $config;
    protected $site;


    final protected function __initApplication()
    {
        // FLASH MESSAGES
        $this->data['flash'] = $this->flash = new \Lemmon\Form\Flash($this->route);
        // /FLASH
        
        //
        // site
        if ($site = Sites::findCurrent()) {
            // site found
            $this->data['site'] = $this->site = $site;
            define('SITE_ID', $site->id);
        } else {
            // site not found
            die('Site not found.');
        }
        
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
