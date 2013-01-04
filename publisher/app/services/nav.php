<?php
/**
* 
*/
class Nav
{
	private static $_instance;
	private static $_cache = array();
	
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


	public static function setCurrentPage($page)
	{
		self::$_page = $page;
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
		return (new QueryPage(self::$_page ? self::$_page->getRoot() : null));
	}
}