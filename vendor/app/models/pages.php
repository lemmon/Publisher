<?php
/**
* 
*/
class Pages extends Zend_Db_Table_Abstract
{
	protected $_name     = 'lp_pages';
	protected $_primary  = 'id';
	protected $_rowClass = 'Page';


	function fetchActiveLanguages()
	{
		// db
		$db = $this->getAdapter();
		// select unique languages
		$select = $db->select()
		             ->distinct()
		             ->from($this->_name, 'language_id')
		             ->where('parent_id IS NULL')
		             ->where('state_id IS NOT NULL')
		             ;
		// fetch language ids
		$languages = $db->fetchCol($select);
		// res as Languages
		return (new Languages)->find($languages);
	}
}
