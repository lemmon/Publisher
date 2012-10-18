<?php
/**
* 
*/
class Posts extends \Lemmon\Model\AbstractModel
{
	static $required = ['name', 'language_id'];
	static $timestmp = ['created_at', 'updated_at'];
}
