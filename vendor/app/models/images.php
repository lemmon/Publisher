<?php
/**
* 
*/
class Images extends Lemmon_Model
{
	
	function define()
	{
		$this->fieldFile('image', 'images/%Y-%m');
		
		$this->timeStampable();
		
		$this->sort('created_at desc');
	}
}
