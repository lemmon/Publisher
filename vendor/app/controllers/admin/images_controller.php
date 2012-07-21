<?php
/**
* 
*/
class Admin_Images_Controller extends Lemmon_Scaffold
{

	function create()
	{
		if ($_FILES)
		{
			Images::make()->create(array('image'=>null));
		}
		return $this->route->redir(':section');
	}
}
