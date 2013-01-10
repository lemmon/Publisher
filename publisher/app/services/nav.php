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


	public static function setCurrentPage($page)
	{
		self::$_page = $page;
		self::setCurrentLocale($page->locale);
	}


	public static function getCurrentPage()
	{
		return self::$_page;
	}


	function getPages()
	{
		return (new QueryPages);
	}


	function getRoot()
	{
		if ($page = self::$_page)
			return (new QueryPage(self::$_page));
		else
		{
			$page = new Page;
			$page->locale = self::$_locale;
			return (new QueryPage($page));
		}
	}
}