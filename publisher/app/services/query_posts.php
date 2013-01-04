<?php
/**
* 
*/
class QueryPosts extends AbstractQueryModel
{


	function __model()
	{
		return new Posts;
	}


	function getRecent($limit = null)
	{
		$q = clone $this;
		$q->model->order('published_at DESC');
		if ($limit)
			$q->model->limit($limit);
		return $q;
	}
}
