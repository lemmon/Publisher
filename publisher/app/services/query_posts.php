<?php
/**
* 
*/
class QueryPosts extends AbstractQueryModel
{
    private $_pagination;


    function __model()
    {
        return Posts::find(['state_id' => 1]);
    }


    function paginate($page, $perpage = 25)
    {
        $this->_pagination = \Lemmon\Form\Scaffold::paginate($this->model, $page, $perpage);
        return $this;
    }


    function getPagination()
    {
        return $this->_pagination;
    }


    function inCategory($category)
    {
        $category_id = ($category instanceof Category) ? $category->id : $category;
        $this->model->where('id IN (?)', new \Lemmon\Sql\Expression('SELECT post_id FROM posts_to_categories WHERE category_id = ?', $category_id));
        return $this;
    }


    function getRecent($limit = null, $scope = 'published')
    {
        return parent::getRecent($limit, $scope);
    }
}
