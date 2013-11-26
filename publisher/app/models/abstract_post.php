<?php

use \Lemmon\Sql\Query as SqlQuery;

/**
* 
*/
abstract class AbstractPost extends AbstractModuleRow
{
    static protected $model = 'Posts';

    private $_temp = [];


    function getCategories()
    {
        if (array_key_exists('categories', $this->_cache)) {
            return $this->_cache['categories'];
        }
        else {
            return $this->_cache['categories'] = Categories::find([
                'id' => (new SqlQuery)->select('posts_to_categories')->where('post_id', $this->id)->distinct('category_id'),
            ])->allByPrimary();
        }
    }


    protected function __validate(&$f)
    {
        //
        // categories
        $this->_temp['categories'] = $f['categories'];
        unset($f['categories']);
    }


    protected function __children()
    {
        // remove old categories
        (new SqlQuery)->delete('posts_to_categories')->where([
            'post_id'      => $this->id,
            '!category_id' => $this->_temp['categories'],
        ])->exec();
        // insert categories
        if ($this->_temp['categories']) foreach ($this->_temp['categories'] as $_id) {
            (new SqlQuery)->replace('posts_to_categories')->set([
                'post_id'     => $this->id,
                'category_id' => $_id,
            ])->exec();
        }
    }
}
