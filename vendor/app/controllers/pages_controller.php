<?php
/**
* 
*/
class Pages_Controller extends Application
{

	function index()
	{
		dump(Languages::getActive());

		die('__PF__');
	}
}
