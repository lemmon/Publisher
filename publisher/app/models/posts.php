<?php
/**
* 
*/
class Posts extends \Lemmon\Model\AbstractModel
{
	static $required = ['name', 'state_id' => 'allow_null', 'language_id'];
	static $timestmp = ['created_at', 'updated_at'];


	function __init()
	{
		$this->order('COALESCE(published_at, updated_at) DESC');
	}
}
