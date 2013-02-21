<?php
/**
* 
*/
class QueryPages extends AbstractQueryModel
{


	function __model()
	{
		return new Pages;
	}


	function findById($id)
	{
		return $this->model->where('id', $id)->first();
	}
}
