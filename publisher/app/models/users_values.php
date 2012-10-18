<?php
/**
* 
*/
class UsersValues extends \Lemmon\Model\AbstractModel
{
	protected $primary   = ['user_id', 'key'];
	protected $required  = ['user_id', 'key'];
	protected $belongsTo = ['User'];
}
