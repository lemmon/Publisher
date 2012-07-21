<?php
/**
* 
*/
class Admin_Pages_Controller extends Lemmon_Scaffold
{
	
	function index()
	{
		/*
		$languages = Languages::make()->all();
		foreach ($languages as $language) $data[$language->id] = Pages::make()->find('language_id', $language->id)->findNull('parent_id')->all();
		$this->data['languages'] = $languages;
		$this->data['data'] = $data;
		*/
		return \Lemmon\Template::display('first', $this->getData(1));
	}


	function create()
	{
		$res = parent::create();
		
		$this->data['languages_collection'] = Languages::getCollection();
		
		return $res;
	}
}
