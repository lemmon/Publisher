<?php
/**
* 
*/
abstract class AbstractQueryModel implements AbstractQueryModelInterface, \IteratorAggregate, \ArrayAccess
{
	protected $model;


	function __construct($where = null)
	{
		$this->model = $this->__model()->where($where);
	}


	function getIterator()
	{
		return $this->model;
	}


	function count()
	{
		return $this->model->count();
	}


	function offsetExists($offset)
	{
		return $this->model->offsetExists($offset);
	}
	
	function offsetGet($offset)
	{
		return $this->model->offsetGet($offset);
	}
	
	function offsetSet($offset, $value)
	{
		return false;
	}
	
	function offsetUnset($offset)
	{
		return false;
	}
}
