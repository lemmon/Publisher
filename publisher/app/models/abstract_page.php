<?php

use \Lemmon\Sql\Query as SqlQuery;

/**
* 
*/
class AbstractPage extends \Lemmon\Model\AbstractRow
{
	static protected $model = 'Pages';


	function getChildren()
	{
		return Pages::find(['parent_id' => $this->id]);
	}


	protected function onValidate(&$f)
	{
		
	}


	protected function onAfterCreate()
	{
		Pages::rebuildTree();
		#$this->_updateTop();
	}


	protected function onAfterUpdate()
	{
		Pages::rebuildTree();
		#$this->_updateTop();
	}


	private function _updateTop()
	{
		$top = $this->top;
		$pairs = (new SqlQuery)->select('pages')->where('parent_id', $this->parent_id)->pairs('id', 'name');
		unset($pairs[$this->id]);
		// insert current item
		if (!$top or $top > count($pairs))
		{
			$pairs[$this->id] = '** THIS **';
		}
		elseif ($top <= 1)
		{
			$pairs = [$this->id => '** THIS **'] + $pairs;
		}
		else
		{
			$pairs = array_slice($pairs, 0, $top-1, true) + [$this->id => '** THIS **'] + array_slice($pairs, $top-1, null, true);
		}
		// update db
		dump($pairs);
		die('--3');
		foreach (array_keys($pairs) as $_top => $_id)
		{
			$this->query('UPDATE $_table SET `top`=%i WHERE `id`=%i LIMIT 1', $_top+1, $_id);
		}
	}
}
