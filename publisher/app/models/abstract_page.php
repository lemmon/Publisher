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
			$f['template'] = \Lemmon\String::asciize($f['template'], '_');
		// top
		if (!$f['top'])
			$f['top'] = 99999;
	}


	protected function onAfterCreate()
	{
		$this->_insertContent();
		$this->_updateTop();
		Pages::rebuildTree();
	}


	protected function onAfterUpdate()
	{
		$this->_insertContent();
		$this->_updateTop();
		Pages::rebuildTree();
	}


	private function _insertContent()
	{
		(new SqlQuery)->replace('pages_blocks')->set([
			'page_id'    => $this->id,
			'name'       => 'main-content',
			#'content'    => \Lemmon\String::sanitizeHtml($this->_temp['content']),
			'content'    => $this->_temp['content'],
		])->exec();
	}


	private function _updateTop()
	{
		$top = $this->top;
		$pairs = (new SqlQuery)->select('pages')->where([
			'locale'    => $this->locale,
			'parent_id' => $this->parent_id,
			'!id'       => $this->id,
		])->order('top')->distinct('id');
		if ($top < 1)
			$top = 1;
		elseif ($top > count($pairs))
			$top = count($pairs) + 1;
		foreach (array_slice($pairs, 0, $top - 1) as $i => $id)
			(new SqlQuery)->update('pages')->set('top', $i + 1)->where('id', $id)->exec();
		foreach (array_slice($pairs, $top - 1, null) as $i => $id)
			(new SqlQuery)->update('pages')->set('top', $i + $top + 1)->where('id', $id)->exec();
	}
}
