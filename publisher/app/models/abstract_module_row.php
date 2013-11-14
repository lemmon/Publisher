<?php

use \Lemmon\Sql\Query as SqlQuery,
    \Lemmon\Sql\Expression as SqlExpression,
    \Lemmon\String;

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


    final function getData()
    {
        return $this->cache['data'] ?: $this->cache['data'] = (new SqlQuery)->select('items_data')->where('item_id',  $this->id)->pairs('name', 'content');
    }


    final function getBlock($name)
    {
        return $this->getData()[$name];
    }


    function getContent()
    {
        return self::getBlock('content');
    }


    final function delete()
    {
        // trash
        (new SqlQuery)->insert('trash')->set([
            'site_id'    => SITE_ID,
            'table'      => $this->getSchema()->table,
            'id'         => $this->id,
            'data'       => serialize($this->toArray() + ['data' => $this->getData()]),
            'created_at' => new SqlExpression('NOW()'),
        ])->exec();
        // delete item
        (new SqlQuery)->delete('items_data')->where('item_id', $this->id)->exec();
        (new SqlQuery)->delete('items')->where(['site_id' => SITE_ID, 'id' => $this->id])->exec();
        //
        return true;
    }


    final protected function onValidate(&$f)
    {
        //
        // validate module
        $this->__validate($f);
        //
        // files uploads
        $this->_validateUploads();
        //
        // ok?
        if ($this->_isInvalid()) {
            return false;
        }
        //
        // content
        $this->_temp['data'] = (array)$f['blocks'] + (array)$f['data'];
        unset($f['blocks']);
        unset($f['data']);
        //
        // site_id
        $f['site_id'] = SITE_ID;
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
        $data = $this->_temp['data'];
        //
        // upload files
        $this->_saveUploads($data);
        //
        // insert content
        if ($data) {
            foreach ($data as $name => $content) {
                // sanitize
                do {
                    $content = trim(preg_replace('#<(\w+)[^>]*>(\xC2\xA0|\s+)*</\1>#', '', $content, -1, $n));
                } while ($n);
                // save
                if (strlen($content)) {
                    (new SqlQuery)->replace('items_data')->set([
                        'item_id' => $this->id,
                        'name'    => $name,
                        'content' => $content,
                    ])->exec();
                } else {
                    unset($data[$name]);
                }
            }
            // remove old content
            (new SqlQuery)->delete('items_data')->where([
                'item_id' => $this->id,
                '!name'   => array_keys($data),
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
