<?php

use \Lemmon\Sql\Query as SqlQuery,
    \Lemmon\Sql\Expression as SqlExpression;

/**
* 
*/
class AbstractPage extends \Lemmon\Model\AbstractRow
{
	static protected $model = 'Pages';

	private $_temp = [];


	function getChildren()
	{
		return Pages::find(['parent_id' => $this->id]);
	}


	protected function onValidate(&$f)
	{
		// content
		$this->_temp['content'] = $f['content'];
		unset($f['content']);
		// template
		if ($f['template'])
			$f['template'] = \Lemmon\String::asciize($f['template']);
	}


	protected function onAfterCreate()
	{
		$this->_insertContent();
		Pages::rebuildTree();
		#$this->_updateTop();
	}


	protected function onAfterUpdate()
	{
		$this->_insertContent();
		Pages::rebuildTree();
		#$this->_updateTop();
	}


	private function _insertContent()
	{
		(new SqlQuery)->replace('pages_blocks')->set([
			'page_id'    => $this->id,
			'name'       => 'main-content',
			'content'    => \Lemmon\String::sanitizeHtml($this->_temp['content']),
			//'created_at' => new SqlExpression('NOW()'),
			//'updated_at' => new SqlExpression('NOW()'),
		])->exec();
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
