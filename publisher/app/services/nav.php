<?php

use \Lemmon\Sql\Query as SqlQuery;

/**
* 
*/
class Nav
{
    private static $_instance;
    private static $_cache = [];
    
    private static $_locale;
    private static $_page;
    private static $_pageNotActive;

    private $_site;


    function __construct($site)
    {
        // site
        $this->_site = $site;
        // instance
        self::$_instance = $this;
    }


    final static function getInstance()
    {
        return self::$_instance;
    }


    static function setCurrentLocale($locale)
    {
        self::$_locale = $locale;
    }


    static function getCurrentLocale()
    {
        return self::$_locale;
    }


    static function setCurrentPage($page, $active = true)
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


    static function getCurrentPage()
    {
        return self::$_page;
    }


    static function getActivePageId()
    {
        return self::$_pageNotActive ? null : self::$_page->id;
    }


    function getPages()
    {
        return new QueryPages([
            'locale_id' => self::$_locale,
            'parent_id' => NULL,
        ]);
    }


    function getRootPage()
    {
        return Page::find([
            'locale_id' => self::$_locale,
            'parent_id' => NULL,
        ]);
    }


    function getLocale()
    {
        return self::$_locale;
    }


    function getLocales()
    {
        return Locales::fetchActive($this->_site->locale_id);
    }


    function getMenu($menu_id)
    {
        $pages = [];
        foreach ((new SqlQuery)->select('pages_to_menus')->where(['site_id' => SITE_ID, 'locale_id' => self::getCurrentLocale()['id'], 'menu_id' => $menu_id])->order('top')->distinct('page_id') as $page_id) {
            $pages[$page_id] = Page::find($page_id);
        }
        return $pages;
    }
}