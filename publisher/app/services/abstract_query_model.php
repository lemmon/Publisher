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


    function byPage($pages)
    {
        if ($pages instanceof QueryPages) {
            // pages query
            return $this->byPage($pages->getIterator()->all());
        } elseif (is_array($pages)) {
            // array of page objects
            $query = [];
            foreach ($pages as $page) {
                if (is_int($page))
                    $query[] = $page;
                elseif ($page instanceof Page)
                    $query[] = $page->id;
            }
            $this->model->where(['page_id' => $query]);
        } elseif (is_numeric($pages)) {
            // singe page id
            $this->model->where(['page_id' => $pages]);
        } elseif ($pages instanceof Page) {
            // singe page object
            $this->model->where(['page_id' => $pages->id]);
        }
        return $this;
    }


    function onPage($page)
    {
        return $this->byPage($page);
    }


    function like($item)
    {
        return $this->byPage($item->getPage());
    }


    function limit($limit, $scope = null)
    {
        if ($scope) {
            if (preg_match('/^\w+$/', $scope)) {
                $this->model->order($scope . '_at DESC');
            } else {
                throw new \Exception('Invalid scope.');
            }
        }
        $this->model->limit($limit);
        return $this;
    }


    function random($limit = null)
    {
        if ($limit) {
            $this->limit($limit);
        }
        $this->model->order('RAND()');
        return $this;
    }


    function getRecent($limit = null, $scope = 'created')
    {
        if ($limit) {
            $this->limit($limit, $scope);
        }
        return $this;
    }


    function filter($key, $value)
    {
        $this->model->getStatement()->join('items_data', ['item_id' => ':id', 'name' => $key, 'content' => $value]);
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
