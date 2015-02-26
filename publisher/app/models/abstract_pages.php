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
    static $timestamp = ['created_at', 'updated_at'];


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


    static function fetchAllByLanguage()
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
        // menus
        if ($menus = Template::getConfig('menus')) {
            $pages['menus'] = $menus;
            /*
            foreach ((new SqlQuery)->select('pages_to_menus')->where(['site_id' => defined('SITE_ID') ? SITE_ID : null])->order('top') as $item) {
                $pages['menus']['pages'][$item->locale_id][] = $item->page_id;
            }
            */
        }
        //
        return $pages;
    }
}
