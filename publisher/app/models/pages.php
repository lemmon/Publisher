<?php
/**
* 
*/
class Pages extends \Lemmon\Model\AbstractModel
{
	static $fields    = ['name', 'content', 'state_id', 'language_id', 'parent_id', 'top'];
	static $sanitize  = [':all' => 'empty_to_null', 'price' => 'decimal', 'content' => 'html'];
	static $required  = ['name', 'state_id' => 'allow_null', 'language_id'];
	static $timestmp  = ['created_at', 'updated_at'];
	static $belongsTo = ['Language'];


	protected function __init()
	{
		// default sort
		$this->order('top');
	}


	static function fetchActiveWithLanguages()
	{
		$pages = [];
		// load pages
		foreach (Pages::find() as $page)
		{
			if (!$page->parent_id) $pages['langs'][$page->language_id][$page->id] = $page;
			else                   $pages['pages'][$page->parent_id][$page->id] = $page;
		}
		//
		return [
			'pages'     => $pages,
			'languages' => Languages::find(['id' => array_keys($pages['langs'])]),
		];
	}
}
