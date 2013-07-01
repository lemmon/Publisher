<?php

use \Lemmon\Sql\Query as SqlQuery;

/**
* 
*/
abstract class AbstractPages extends \Lemmon\Model\AbstractModel
{
    static $fields    = ['name', 'content', 'state_id', 'locale_id', 'parent_id', 'top'];
    static $sanitize  = [':all' => 'empty_to_null', 'price' => 'decimal', 'content' => 'html'];
    static $required  = ['name', 'state_id' => 'allow_null', 'locale_id'];
    static $timestamp  = ['created_at', 'updated_at'];


    protected function __init()
    {
        if (defined('SITE_ID')) {
            $this->where('site_id', SITE_ID);
        }
        // default sort
        $this->order('top, name');
    }


    static function findVisible($where)
    {
        return self::find($where)->where('state_id > ?', 0);
    }


    static function fetchActiveByLanguage()
    {
        $pages = [];
        // load pages
        foreach (Pages::find() as $page)
        {
            // unlinked pages
            if ($page->parent_id == -1)
                $pages['unlinked'][$page->locale_id][$page->id] = $page;
            // root pages
            elseif (!$page->parent_id)
                $pages['langs'][$page->locale_id][$page->id] = $page;
            // children pages
            else
                $pages['pages'][$page->parent_id][$page->id] = $page;
        }
        //
        return $pages;
    }


    static function fetchTreeInLocale($locale_id)
    {
        $pages = [];
        // load pages
        foreach (Pages::find(['locale_id' => $locale_id]) as $page)
        {
            // unlinked pages
            if ($page->parent_id == -1)
                $pages['unlinked'][$page->locale_id][$page->id] = $page;
            // root pages
            elseif (!$page->parent_id)
                $pages['root'][$page->id] = $page;
            // children pages
            else
                $pages['pages'][$page->parent_id][$page->id] = $page;
        }
        //
        return $pages;
    }


    static function rebuildTree()
    {
        // load items
        foreach (self::find()->order('top') as $item)
        {
            // item
            $tree_item = TreeItem::newInstance($item->id, $item->locale_id, $item->name);
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
        if ($data) foreach ($data as $item)
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
