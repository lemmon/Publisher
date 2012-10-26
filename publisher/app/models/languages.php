<?php
/**
* 
*/
class Languages extends \Lemmon\Model\AbstractModel
{
	static $table   = 'countries';
	static $hasMany = ['Pages'];


	protected function __init()
	{
		// default sort
		$this->order('language_local');
	}


	static function findActive()
	{
		return self::find()->join('pages', ['language_id' => 'id', 'parent_id' => null])->group('countries.id');
	}


	function orderByImportance()
	{
		return $this->order((($language_id = Values::get('language_id')) ? 'CASE id WHEN ' . $language_id . ' THEN 1 ELSE 2 END, ' : '') . 'language_local');
	}
}
