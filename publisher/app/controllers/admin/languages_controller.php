<?php
/**
* 
*/
class Admin_Languages_Controller extends Lemmon_Scaffold
{


	function create()
	{
		self::_getValidLanguages();
		return parent::create();
	}


	function update()
	{
		self::_getValidLanguages();
		return parent::update();
	}


	private function _getValidLanguages()
	{
		$this->data['languages_collection'] = Countries::make()->pairs('id', 'language');
	}
}
