<?php
/**
* 
*/
class Media extends \Lemmon\Model\AbstractModel
{
	static $rowClass  = 'medium';
	static $required  = ['file'];
	static $timestamp  = ['created_at', 'updated_at'];
	static $uploads   = ['file'];
	static $uploadDir = 'media/%Y-%m';


	protected function __init()
	{
		$this->order('created_at DESC');
	}
}
