<?php

use \Lemmon\Sql\Query as SqlQuery,
    \Lemmon\Sql\Expression as SqlExpression,
    \Lemmon\String;

/**
* 
*/
abstract class AbstractPage extends AbstractRow
{
    static protected $model = 'Pages';

    protected $cache = [];
    private $_blocksLoaded;


    function getTemplateName()
    {
        // template is defined
        if ($this->template) {
            return $this->template;
        }
        // type is defined
        elseif ($this->type) {
            return $this->type . (Application::getAction() == 'index' ? '' : '_' . Application::getAction());
        }
        // default
        else {
            return 'default';
        }
    }


    function getContent()
    {
        return $this->getBlock('content');
    }


    protected function loadBlocks()
    {
        if (!$this->_blocksLoaded) {
            $this->cache['blocks'] = (new SqlQuery)->select('pages_blocks')->where('page_id',  $this->id)->pairs('name', 'content');
            $this->_blocksLoaded = true;
        }
        return $this;
    }


    function getData()
    {
        return $this->toArray();
    }


    function getBlocks()
    {
        $this->loadBlocks();
        return $this->cache['blocks'];
    }


    function getBlock($name)
    {
        return $this->getBlocks()[$name];
    }


    function getItems()
    {
        if ($this->type) {
            return call_user_func([String::tableToClassName($this->type), 'find'], ['page_id' => $this->id]);
        }
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
        if ($redirect = $this->redirect) {
            // redirect
            if ($redirect{0} == '/') {
                return $this->getRoute()->to($redirect);
            }
            elseif (preg_match('#:?//#', $redirect)) {
                return $this->getRoute()->to($redirect);
            }
            else {
                return $this->getRoute()->to(':page_slug', $this);
            }
        }
        elseif ($this->parent_id == null and $this->top == 1 and $this->locale_id == $this->getRoute()->getSite()->locale_id) {
            // this is root page
            return $this->getRoute()->to(':home');
        }
        else {
            // subpage
            return $this->getRoute()->to(':page', $this);
        }
    }
}
