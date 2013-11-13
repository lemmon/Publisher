<?php
/**
* 
*/
class QueryPages extends AbstractQueryModel
{


    function __model()
    {
        return Pages::find(['state_id > ?' => 0]);
    }


    static function findById($id)
    {
        return (new self(['id' => $id]))[0];
    }


    function byTag($tag)
    {
        $this->model->where(['id' => (new \Lemmon\Sql\Query)->select('pages_tags')->where(['site_id' => SITE_ID, 'tag LIKE ?' => $tag])->distinct('page_id')]);
        return $this;
    }


    function byType($type)
    {
        $this->model->where(['type' => $type]);
        return $this;
    }
}
