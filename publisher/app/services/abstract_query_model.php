<?php
/**
* 
*/
abstract class AbstractQueryModel implements AbstractQueryModelInterface, \IteratorAggregate, \ArrayAccess
{
    protected $model;


    function __construct($where = null)
    {
        $this->model = $this->__model()->where($where);
    }




    function inLocale($locale)
    {
        if (is_string($locale)) {
            $this->model->where(['locale_id' => $locale]);
        } elseif (is_array($locale) and $locale['id']) {
            $this->model->where(['locale_id' => $locale['id']]);
        } else {
            throw new \Exception('Unknown locale.');
        }
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
