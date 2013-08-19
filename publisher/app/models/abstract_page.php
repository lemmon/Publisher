<?php

use \Lemmon\Sql\Query as SqlQuery,
    \Lemmon\Sql\Expression as SqlExpression;

/**
* 
*/
abstract class AbstractPage extends AbstractRow
{
    static protected $model = 'Pages';

    protected $cache = [];


    function getContent()
    {
        if (!array_key_exists('content', $this->cache)) {
            return $this->cache['content'] = (new SqlQuery)->select('pages_blocks')->where([
                'page_id' => $this->id,
                'name'    => 'content',
            ])->first()->content;
        } else {
            return $this->cache['content'];
        }
    }


    function getBlocks()
    {
        return $this->cache['blocks'] ?: $this->cache['blocks'] = (new SqlQuery)->select('pages_blocks')->where('page_id',  $this->id)->pairs('name', 'content');
    }


    function getBlock($name)
    {
        return $this->getBlocks()[$name];
        /*
        return (new SqlQuery)->select('pages_blocks')->where([
            'page_id' => $this->id,
            'name'    => $name,
        ])->first()->content;
        */
    }


    function setBlock($name, $content)
    {
        $this->cache['blocks'][$name] = $content;
        $this->cache['blocks_to_save'][$name] = $name;
        $this->requireSave();
        return $this;
    }


    function setBlocks(array $blocks)
    {
        $this->cache['blocks'] = array_merge((array)$this->cache['blocks'], $blocks);
        $this->cache['blocks_to_save'] = array_merge((array)$this->cache['blocks_to_save'], array_combine(array_keys($blocks), array_keys($blocks)));
        $this->requireSave();
        return $this;
    }


    function getTags()
    {
        if (array_key_exists('tags', $this->cache)) {
            return $this->cache['tags'];
        } elseif ($this->id) {
            return $this->cache['tags'] = (new SqlQuery)->select('pages_tags')->where(['page_id' => $this->id])->distinct('tag');
        }
    }


    function getLocale()
    {
        return Locales::fetch($this->locale_id);
    }


    function getRoot()
    {
        return Page::find($this->root_id);
    }


    function getParent()
    {
        if ($this->parent_id) {
            return Page::find($this->parent_id);
        }
    }


    function getState()
    {
        return States::getOptions()[$this->state_id];
    }


    function getUrl()
    {
        if ($this->parent_id == null and $this->top == 1 and $this->locale_id == $this->getRoute()->getSite()->locale_id) {
            // this is root page
            return $this->getRoute()->to(':home');
        } else {
            // subpage
            return $this->getRoute()->to(':page', $this);
        }
    }
}
