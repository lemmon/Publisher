<?php
/**
* 
*/
class User extends \Lemmon\Model\AbstractRow
{
	static protected $model = 'Users';


	function getValues()
	{
		return UsersValues::find(['user_id' => $this->id]);
	}
}
