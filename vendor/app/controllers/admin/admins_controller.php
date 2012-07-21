<?php
/**
* 
*/
class Admin_Admins_Controller extends Lemmon_Scaffold
{
	
	public function __construct()
	{
		parent::__construct();
		
		if (!Admins::getCurrent()->is_admin)
		{
			$this->flashError('Access denied');
			$this->route->redir('@')->exec();
		}
	}
}
