<?php
/**
* 
*/
class Languages extends Lemmon_Model
{
	static private $_default;


	protected $_table = 'countries';


	protected function define()
	{
	}


	static function getCollection()
	{
		return (new Languages)->excludeNull('language_local')->pairs('id', 'language_local');
	}


	/*
	static public function getDefault()
	{
		return ($res=self::$_default) ? $res : self::$_default=Languages::make()->first();
	}
	
	public function getName()
	{
		return $this->country->language_local;
	}
	
	public function getCode()
	{
		return substr($this->country->locale, 0, 2);
	}
	
	public function getHref()
	{
		if ($this->id==Languages::getDefault()->id)
		{
			return Lemmon_Route::getInstance()->to(':home');
		}
		else
		{
			return Pages::make()->find('language_id', $this->id)->findNull('parent_id')->excludeNull('state_id')->first()->getHref();
		}
	}
	
	protected function onValidate(&$row)
	{
		$ok = true;
		// state
		if (!array_key_exists('state_id', $row))
		{
			Application::getInstance()->flashError('Missing field State');
			$ok = false;
		}
		//
		return $ok;
	}
	
	protected function onBeforeCreate(&$f)
	{
		// default
		if (!Languages::make()->find('default', 1)->count())
		{
			$f['default'] = 1;
		}
	}
	
	protected function onAfterCreate($f)
	{
		self::onAfterUpdate($f);
	}
	
	protected function onAfterUpdate($f)
	{
		// default
		if ($f['default'] and $languages=Languages::make()->exclude($this->id)->find('default', 1) and $languages->count())
		{
			$languages->update(array( 'default' => null ), 1);
		}
	}
	*/
}
