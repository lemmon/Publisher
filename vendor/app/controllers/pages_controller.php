<?php
/**
* 
*/
class Pages_Controller extends Application
{

	function index()
	{
		$this->data['page'] = (new Pages())->first();
	}
}
