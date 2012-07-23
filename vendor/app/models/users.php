<?php
/**
* 
*/
class Users extends Zend_Db_Table_Abstract
{
	protected $_name     = 'lp_users';
	protected $_primary  = 'id';
	protected $_rowClass = 'User';
}
