<?php

use \Lemmon\Sql\Query as SqlQuery;

/**
* 
*/
class Pages extends \Lemmon\Model\AbstractModel
{
	static private $_locked;

	static $fields    = ['name', 'content', 'state_id', 'language_id', 'parent_id', 'top'];
	static $sanitize  = [':all' => 'empty_to_null', 'price' => 'decimal', 'content' => 'html'];
	static $required  = ['name', 'state_id' => 'allow_null', 'language_id'];
	static $timestmp  = ['created_at', 'updated_at'];
	static $belongsTo = ['Language'];


	static function lock()
	{
		self::$_locked = true;
	}


	static function locked()
	{
		return self::$_locked ? true : false;
	}


	protected function __init()
	{
		// default sort
		$this->order('top');
	}


	static function findVisible($where)
	{
		return self::find($where)->where('state_id > ?', 0);
	}


	static function fetchActiveWithLanguages()
	{
		$pages = [];
		// load pages
		foreach (Pages::find() as $page)
		{
			if (!$page->parent_id) $pages['langs'][$page->language_id][$page->id] = $page;
			else                   $pages['pages'][$page->parent_id][$page->id] = $page;
		}
		//
		return [
			'pages'     => $pages,
			'languages' => $pages ? Languages::find(['id' => array_keys($pages['langs'])])->orderByImportance() : null,
		];
	}


	static function rebuildTree()
	{
		// load items
		foreach (self::find()->order('top') as $item)
		{
			// item
			$tree_item = TreeItem::newInstance($item->id, $item->language_id, $item->name);
			// parent
			if ($item->parent_id)
			{
				$data[] = [
					'id'        => $item->id,
					'parent_id' => $item->parent_id,
					'name'      => $item->name,
				];
			}
		}
		// assign children
		foreach ($data as $item)
		{
			TreeItem::getInstance($item['parent_id'])->addChild(TreeItem::getInstance($item['id']));
		}
		//
		foreach (TreeItem::getAllItems() as $item)
		{
			$q = (new SqlQuery)->update('pages');
			$q->set([
				'root_id'        => $item->getRootId(),
				'path_query'     => ($_=$item->getPathQuery()) ? $_ : null,
				'children_query' => $item->getChildrenQuery(),
				'top'            => $item->getTop(),
				'level'          => $item->getLevel(),
			]);
			$q->where('id', $item->getId());
			$q->limit(1);
			$q->exec();
		}
	}
}
