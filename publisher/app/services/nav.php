<?php
/**
* 
*/
class Nav
{
    private static $_instance;
    private static $_cache = array();
    
    private static $_locale;
    private static $_page;
    private static $_pageNotActive;


    public function __construct()
    {
        // instance
        self::$_instance = $this;
    }


    final public static function getInstance()
    {
        return self::$_instance;
    }


    public static function setCurrentLocale($locale)
    {
        self::$_locale = $locale;
    }


    public static function getCurrentLocale()
    {
        return self::$_locale;
    }


    public static function setCurrentPage($page, $active = true)
    {
        if (is_numeric($page)) {
            $page = Page::find($page);
        }
        self::$_page = $page;
        if ($active == false) {
            self::$_pageNotActive = true;
        }
        self::setCurrentLocale($page->locale);
    }


    public static function getCurrentPage()
    {
        return self::$_page;
    }


    public static function getActivePageId()
    {
        return self::$_pageNotActive ? null : self::$_page->id;
    }


    function getPages()
    {
        return new QueryPages([
            'locale_id' => self::$_locale,
            'parent_id' => null,
        ]);
    }


    function getRootPage()
    {
        return Page::find([
            'locale_id' => self::$_locale,
            'parent_id' => null,
        ]);
    }


    function getLocale()
    {
        return self::$_locale;
    }
}