<?php
/**
* 
*/
class PagesStates implements Iterator, Countable
{
	static private $_data = [
		null => 'Hidden',
		   1 => 'Active',
	];
	
	private $_keys;
	private $_current;


	function __construct()
	{
		$this->_current = 0;
		$this->_keys = array_keys(self::$_data);
	}


	static function getCollection()
	{
		return (new self)->fetchAll();
	}


	function fetchAll()
	{
		return $this;
	}


	//
	// Countable
	//


	function count()
	{
		return count(self::$_data);
	}


	//
	// Iterator
	//


	function current()
	{
		return '=' . $this->_current . '=';
		
		$current = current(self::$_data);
		
		if (is_array($current))
		{
			$current['id'] = key(self::$_data);
		}
		else
		{
			$current = [
				'id' => key($_data),
				'name' => $current,
			];
		}
		
		return new AbstractModel($current);
	}


	function key()
	{
		return $this->_current;
	}


	function next()
	{
		$this->_current++;
	}


	function rewind()
	{
		$this->_current = 0;
	}


	function valid()
	{
		return array_key_exists($this->_current, $this->_keys);
	}
}
