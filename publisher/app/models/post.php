<?php

use \Lemmon\Sql\Query as SqlQuery;

/**
* 
*/
class Post extends \Lemmon\Model\AbstractRow
{
    static protected $model = 'Posts';

    private $_cache = [];
    private $_data = [];


    function getUrl()
    {
        return Route::getInstance()->to(':post', $this);
    }


    function getLocale()
    {
        return Locales::fetch($this->locale_id);
    }


    function getState()
    {
        return States::getOptions()[$this->state_id];
    }


    function getPage()
    {
        return Page::find($this->page_id);
    }


    function getCategories()
    {
        if (array_key_exists('categories', $this->_cache))
            return $this->_cache['categories'];
        else
            return $this->_cache['categories'] = Categories::find([
                'id' => (new SqlQuery)->select('posts_to_categories')->where('post_id', $this->id)->distinct('category_id'),
            ])->allByPrimary();
    }


    protected function onValidate(&$f)
    {
        //
        // content
        $f['content'] = \Lemmon\String::sanitizeHtml($f['content']);
        //
        // published
        if ($f['state_id'] and !$this->dataDefault['state_id']) {
            $f['published_at'] = ($this->dataDefault['published_at']) ?: new \Lemmon\Sql\Expression('NOW()');
        } elseif (!$f['state_id']) {
            $f['published_at'] = null;
        }
        //
        // categories
        $this->_data = $f;
        unset($f['categories']);
    }


    private function _saveChildren()
    {
        // remove old categories
        (new SqlQuery)->delete('posts_to_categories')->where([
            'post_id'      => $this->id,
            '!category_id' => $this->_data['categories'],
        ])->exec();
        // insert categories
        if ($this->_data['categories']) foreach ($this->_data['categories'] as $_id)
        {
            (new SqlQuery)->replace('posts_to_categories')->set([
                'post_id'     => $this->id,
                'category_id' => $_id,
            ])->exec();
        }
    }


    protected function onAfterCreate()
    {
        $this->_saveChildren();
    }


    protected function onAfterUpdate()
    {
        $this->_saveChildren();
    }
}
