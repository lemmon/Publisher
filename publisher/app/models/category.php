<?php
/**
* 
*/
class Category extends \Lemmon\Model\AbstractRow
{
	static protected $model = 'Categories';


	function getUrl()
	{
		return Route::getInstance()->to(':category', $this);
	}


	function getLanguage()
	{
		return Locales::fetch($this->locale);
	}


	function getState()
	{
		return States::getOptions()[$this->state_id];
	}
}
