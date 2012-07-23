<?php
/**
* 
*/
class Languages extends Zend_Db_Table_Abstract
{
	protected $_name     = 'lp_countries';
	protected $_primary  = 'id';
	protected $_rowClass = 'Language';

	protected $_dependentTables = ['Pages'];

	/*
	protected $_referenceMap = [
		'Pages' => [
			'columns'       => 'language_id',
			'refTableClass' => 'Pages',
			
		],
	];
	*/
	

	static function getActive()
	{
		return (new Pages)->fetchActiveLanguages();
	}


	static function getCollection()
	{
		$languages = new Languages;
		$languages->fetchAll($languages->select()->where(Language::$_name . ' IS NOT NULL')->order(Language::$_name));
	}
}
