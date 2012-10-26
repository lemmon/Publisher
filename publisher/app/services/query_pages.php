<?php
/**
* 
*/
class QueryPages implements \IteratorAggregate
{
	private $_pages;


	function __construct($where = null)
	{
		$this->_pages = Pages::findVisible($where);
	}


	function getIterator()
	{
		return $this->_pages;
	}


	function findByParent($parent_id)
	{
		$this->_pages->where('parent_id', $parent_id);
		return $this;
	}


	function count()
	{
		return $this->_pages->count();
	}
}
