<?php
/**
* 
*/
class Language extends Zend_Db_Table_Row_Abstract
{
	static $_name = 'language_local';


	function __toString()
	{
		return $this->{self::$_name};
	}


	function getPages()
	{
		$pages = new Pages;
		
		$select = $pages->select()
		                ->where('language_id = ?', $this->id)
		                ->where('parent_id IS NULL')
		                ->order('top')
		                ;
		
		return $pages->fetchAll($select);
	}
}
