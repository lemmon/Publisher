<?php
/**
* 
*/
class Query
{


	function getPages()
	{
		return new QueryPages;
	}


	function getPosts()
	{
		return new QueryPosts;
	}
}
