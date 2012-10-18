<?php
/**
* 
*/
class Language extends \Lemmon\Model\AbstractRow
{
	static protected $model = 'Languages';


	function getCode()
	{
		return substr($this->locale, 0, 2);
	}


	function getCountry()
	{
		return [
			'name' => $this->data['name'],
			'code' => $this->data['code'],
		];
	}


	function getName()
	{
		return $this->language_local;
	}


	function getCaption()
	{
		return self::getName();
	}


	function getPages()
	{
		return Pages::find(['language_id' => $this->id, 'parent_id' => null]);
	}
}
