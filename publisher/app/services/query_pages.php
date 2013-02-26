<?php
/**
* 
*/
class QueryPages extends AbstractQueryModel
{


	function __model()
	{
		return (new Pages)->where('state_id', 1);
	}


	static function findById($id)
	{
		return Page::find($id);
	}
}
