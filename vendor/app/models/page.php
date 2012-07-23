<?php
/**
* 
*/
class Page extends Zend_Db_Table_Row_Abstract
{


	function getState()
	{
		return new PagesStates($this->state_id);
	}
}
