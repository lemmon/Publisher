<?php

use \Lemmon\Sql\Query as SqlQuery;

/**
* 
*/
class Pages extends AbstractPages
{




    static function fetchTreeInLocale($locale_id)
    {
        $pages = [];
        // load pages
        foreach (Pages::find(['locale_id' => $locale_id]) as $page) {
            // unlinked pages
            if ($page->parent_id == -1)
                $pages['unlinked'][$page->id] = $page;
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


    static function fetchLinearInLocale($locale_id)
    {
        function doit($query) {
            $pages = [];
            foreach ($query as $page) {
                /* */
                $pages[] = [
                    'id'       => $page->id,
                    'name'     => $page->name,
                    'level'    => $page->level,
                    'state_id' => $page->state_id,
                ];
                /* *
                $page[] = $page;
                /* */
                $pages = array_merge($pages, doit($page->getChildren()));
            }
            return $pages;
        }
        return [
            'root' => doit(Pages::find(['locale_id' => $locale_id, 'parent_id' => null])),
            'unlinked' => doit(Pages::find(['locale_id' => $locale_id, 'parent_id' => -1])),
        ];
    }


    static function rebuildTree()
    {
        // load items
        foreach (self::find()->order('top') as $item) {
            // item
            $tree_item = TreeItem::newInstance($item->id, $item->locale_id, $item->name);
            // parent
            if ($item->parent_id) {
                $data[] = [
                    'id'        => $item->id,
                    'parent_id' => $item->parent_id,
                    'name'      => $item->name,
                ];
            }
        }
        // assign children
        if ($data) foreach ($data as $item) {
            if ($item['parent_id'] != -1)
                TreeItem::getInstance($item['parent_id'])->addChild(TreeItem::getInstance($item['id']));
            else
                TreeItem::getInstance($item['id'])->setUnlinked();
        }
        //
        /*
        foreach (TreeItem::getAllItems() as $item) {
            dump([
                'id'        => $item->getId(),
                'name'      => $item->getName(),
                'parent_id' => $item->getParentId(),
                'top'       => $item->getTop(),
            ]);
        }
        */
        //
        foreach (TreeItem::getAllItems() as $item) {
            $q = (new SqlQuery)->update('pages');
            $q->set([
                'root_id'        => $item->getRootId(),
                'path_query'     => ($_=$item->getPathQuery()) ? $_ : null,
                'children_query' => $item->getChildrenQuery(),
                'top'            => $item->getTop(),
                'level'          => $item->getLevel(),
            ]);
            $q->where(['site_id' => defined('SITE_ID') ? SITE_ID : null, 'id' => $item->getId()]);
            $q->limit(1);
            $q->exec();
        }
        //
    }
}
