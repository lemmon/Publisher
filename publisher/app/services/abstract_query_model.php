<?php
/**
* 
*/
abstract class AbstractQueryModel implements AbstractQueryModelInterface
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
}
