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
        #return Page::find($id);
    }
}
