<?php
/**
* 
*/
class AbstractModel
{
	private $_data;


	function __construct($data)
	{
		$this->_data = $data;
	}


	function __toString()
	{
		return (string)$this->_data['name'];
	}
}
