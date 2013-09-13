<?php

use \Lemmon\Sql\Query as SqlQuery;

/**
* 
*/
abstract class AbstractModuleRow extends AbstractRow
{
    private $_temp = [];
    protected $cache = [];


    protected function __initItem() {}
    protected function __validate(array &$f) {}
    protected function __children() {}


    final protected function __init()
    {
        if ($this->_getState() != self::STATE_EMPTY and defined('SITE_ID') and $this->site_id != SITE_ID) {
            throw new Exception('Access Denied');
        }
    }


    function getState()
    {
        return States::getOptions()[$this->state_id];
    }


    function getLocale()
    {
        return Locales::fetch($this->locale_id);
    }


    function getPage()
    {
        return Page::find($this->page_id);
    }


    function getUrl()
    {
        return $this->getRoute()->to(':module_item', $this);
    }


    final function getBlocks()
    {
        return $this->cache['blocks'] ?: $this->cache['blocks'] = (new SqlQuery)->select('items_data')->where('item_id',  $this->id)->pairs('name', 'content');
    }


    final function getBlock($name)
    {
        return $this->getBlocks()[$name];
    }


    function getContent()
    {
        return self::getBlock('content');
    }


    final function get($name)
    {
        return $this->getBlock($name);
    }



    final protected function onValidate(&$f)
    {
        //
        // validate module
        $this->__validate($f);
        //
        // content
        if (array_key_exists('blocks', $f)) {
            // we have received general content
            $this->_temp['blocks'] = $f['blocks'];
            unset($f['blocks']);
        }
        //
        // site_id
        if (defined('SITE_ID')) {
            $f['site_id'] = SITE_ID;
        }
        //
        // item type
        $f['type_id'] = get_class($this); # $this->getSchema()->rowClass;
        //
        // published
        if ($f['state_id'] and !$this->dataDefault['state_id']) {
            $f['published_at'] = ($this->dataDefault['published_at']) ?: new \Lemmon\Sql\Expression('NOW()');
        } elseif (!$f['state_id']) {
            $f['published_at'] = null;
        }
    }


    final protected function onAfterCreate()
    {
        $this->_insertContent();
    }


    final protected function onAfterUpdate()
    {
        $this->_insertContent();
    }


    private function _insertContent()
    {
        // insert content
        if ($blocks = $this->_temp['blocks'] and is_array($blocks)) {
            foreach ($blocks as $name => $content) {
                // sanitize
                do {
                    $content = preg_replace('#<(\w+)[^>]*>(\xC2\xA0|\s+)*</\1>#', '', $content, -1, $n);
                } while ($n);
                // save
                (new SqlQuery)->replace('items_data')->set([
                    'item_id' => $this->id,
                    'name'    => $name,
                    'content' => $content,
                ])->exec();
            }
            // remove old content
            (new SqlQuery)->delete('items_data')->where([
                'item_id' => $this->id,
                '!name'   => array_keys($blocks),
            ])->exec();
        }
        else {
            // remove all content from this item
            (new SqlQuery)->delete('items_data')->where([
                'item_id' => $this->id,
            ])->exec();
        }
    }
}
