<?php
/**
* 
*/
abstract class AbstractQueryModel implements AbstractQueryModelInterface, \IteratorAggregate, \ArrayAccess
{
    protected $model;


    function __construct($where = null)
    {
        // load model
        $model = $this->__model();
        // query
        if ($where instanceof AbstractPage) {
            // locate page
            $model->where('page_id', $where->id);
        } elseif (is_array($where)) {
            // where
            $model->where($where);
        } elseif ($where) {
            // error
            throw new \Exception('Error.');
        }
        //
        return $this->model = $model;
    }




    function inLocale($locale = null)
    {
        if (!$locale) {
            // default page locale
            $this->model->where(['locale_id' => Nav::getCurrentLocale()['id']]);
        } elseif (is_string($locale)) {
            // locale by string
            $this->model->where(['locale_id' => $locale]);
        } elseif (is_array($locale) and $locale['id']) {
            // locale by array
            $this->model->where(['locale_id' => $locale['id']]);
        } else {
            // n/a
            throw new \Exception('Unknown locale.');
        }
        return $this;
    }


    function random()
    {
        $this->model->order('RAND()');
        return $this;
    }


    function getRecent($limit = null, $scope = 'created')
    {
        if (preg_match('/^\w+$/', $scope)) {
            $this->model->order($scope . '_at DESC');
        } else {
            throw new \Exception('Invalid scope.');
        }
        if ($limit) {
            $this->model->limit($limit);
        }
        return $this;
    }




    function getIterator()
    {
        return $this->model;
    }


    function count()
    {
        return $this->model->count();
    }


    function offsetExists($offset)
    {
        return $this->model->offsetExists($offset);
    }


    function offsetGet($offset)
    {
        return $this->model->offsetGet($offset);
    }


    function offsetSet($offset, $value)
    {
        return false;
    }


    function offsetUnset($offset)
    {
        return false;
    }
}
