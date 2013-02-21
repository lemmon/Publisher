<?php
/**
* 
*/
class QueryPosts extends AbstractQueryModel
{
	private $_perpage;
	private $_page;


	function __model()
	{
		return new Posts;
	}


	function paginate($perpage, $page)
	{
		$this->_perpage = $perpage;
		$this->_page = $page;
		$this->model->limit($perpage)->offset($page * $perpage);
		return $this;
	}


	function getPagination()
	{
		return 'foo';
	}


	function getRecent($limit = null)
	{
		$this->model->order('published_at DESC');
		if ($limit)
			$this->model->limit($limit);
		return $this;
	}
}
