<?php
/**
* 
*/
class Categories extends Lemmon\Model\AbstractModel
{
	static $required  = ['name', 'state_id' => 'allow_null', 'locale'];
	static $timestamp  = ['created_at', 'updated_at'];
	static $uploads   = ['image'];
	static $uploadDir = 'categories/%Y-%m';


	protected function __init()
	{
		// default order
		$this->order('COALESCE(top, 0), name');
		// frontend
		if (Application::$isFrontend)
			$this->where(['state_id' => 1]);
	}


	static function fetchActiveByLocale()
	{
		$pages = [];
		// load pages
		foreach (Categories::find() as $item)
		{
			$pages[$item->locale][$item->id] = $item;
		}
		//
		return $pages;
	}
}
