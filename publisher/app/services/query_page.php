<?php
/**
* 
*/
class QueryPage
{
	private $_page;


	function __construct($page)
	{
		$this->_page = $page;
	}


	function getPage()
	{
		return $this->_page;
	}


	function getPages()
	{
		return (new QueryPages(['language_id' => $this->_page->language_id]))->findByParent($this->_page->parent_id);
	}
}
