<?php
/**
* 
*/
class Db extends Lemmon_MySQL_Connection
{


	protected function define()
	{
		// dev
		$this->user        = 'root';
		$this->db          = 'publisher';
		$this->tablePrefix = 'lp_';
	}
}
