<?php
/**
* 
*/
class Application extends \Lemmon\Framework
{
	static $isFrontend;

	protected $config;


	final protected function __initApplication()
	{
		// FLASH MESSAGES
		$this->data['flash'] = $this->flash = new \Lemmon\Form\Flash($this->route);
		// /FLASH
		
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
