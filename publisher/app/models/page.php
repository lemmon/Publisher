<?php

use \Lemmon\Sql\Query as SqlQuery,
    \Lemmon\Sql\Expression as SqlExpression;

/**
* Backend.
*/
class Page extends AbstractPage
{
    private $_temp = [];


    function getChildren()
    {
        if ($this->id) {
            return Pages::find(['parent_id' => $this->id]);
        }
    }


    function setBlock($name, $content)
    {
        $this->loadBlocks();
        $this->cache['blocks'][$name] = $content;
        $this->cache['blocks_to_save'][$name] = $name;
        $this->requireSave();
        return $this;
    }


    function setBlocks(array $blocks)
    {
        $this->loadBlocks();
        foreach ($blocks as $name => $content) {
            $this->cache['blocks'][$name] = $content;
            $this->cache['blocks_to_save'][$name] = $name;
        }
        $this->requireSave();
        return $this;
    }


    protected function onValidate(&$f)
    {
        //
        // site_id
        if (defined('SITE_ID')) {
            $f['site_id'] = SITE_ID;
        }
        //
        // content
        if (array_key_exists('blocks', $f)) {
            // we have received general content
            $this->_temp['blocks'] = $f['blocks'];
            unset($f['blocks']);
        }
        if ($this->cache['blocks_to_save']) {
            // additional blocks to save
            $this->_temp['blocks'] = array_merge((array)$this->_temp['blocks'], array_intersect_key($this->cache['blocks'], $this->cache['blocks_to_save']));
        }
        //
        // tags
        if (array_key_exists('tags', $f)) {
            $this->_temp['tags'] = $f['tags'];
            unset($f['tags']);
        }
        //
        // template
        if ($f['template']) {
            $f['template'] = \Lemmon\String::asciize($f['template'], '_');
        }
        //
        // top
        if (!$f['top']) {
            $f['top'] = 99999;
        }
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
        if ($this->data['top'] != $this->dataDefault['top'] or $this->data['parent_id'] != $this->dataDefault['parent_id']) {
            $this->_updateTop();
            Pages::rebuildTree();
        }
    }


    private function _insertContent()
    {
        // insert content
        if ($this->_temp['blocks']) {
            $blocks_to_remove = [];
            foreach ($this->_temp['blocks'] as $name => $content) {
                // sanitize
                do {
                    $content = trim(preg_replace('#<(\w+)[^>]*>(\xC2\xA0|\s+)*</\1>#', '', $content, -1, $n));
                } while ($n);
                // update content
                if ($content) {
                    // store to db
                    (new SqlQuery)->replace('pages_blocks')->set([
                        'page_id' => $this->id,
                        'name'    => $name,
                        'content' => $content,
                    ])->exec();
                } else {
                    // blocks to remove
                    $blocks_to_remove[] = $name;
                }
            }
            // remove unwanted content
            (new SqlQuery)->delete('pages_blocks')->where(['page_id' => $this->id, new SqlExpression('(content IS NULL OR content = "" OR name IN (?))', $blocks_to_remove)])->exec();
        }
        // insert tags
        if ($tags = $this->_temp['tags']) {
            // insert tags
            $tags = explode(',', $tags);
            // sanitize and save
            foreach ($tags as $i => $tag) {
                if ($tag = \Lemmon\String::asciize($tag)) {
                    (new SqlQuery)->replace('pages_tags')->set(['page_id' => $this->id, 'tag' => $tag])->exec();
                    $tags[$i] = $tag;
                } else {
                    unset($tags[$i]);
                }
            }
            // remove unwanted
            (new SqlQuery)->delete('pages_tags')->where(['page_id' => $this->id, '!tag' => $tags])->exec();
        } else {
            // remove all tags
            (new SqlQuery)->delete('pages_tags')->where(['page_id' => $this->id])->exec();
        }
    }


    private function _updateTop()
    {
        $top = $this->top;
        $pairs = (new SqlQuery)->select('pages')->where([
            'site_id'   => defined('SITE_ID') ? SITE_ID : null,
            'locale_id' => $this->locale_id,
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
