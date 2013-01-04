<?php
/**
* 
*/
class Admin_Users_Controller extends Application
{


	function index()
	{
		$this->data['data'] = Users::find()->order('name')->all();
	}
}
