<?php
/**
* 
*/
class QueryContact extends AbstractQueryModel
{
    private $_loaded;
    private $_data;
    private $_dataRaw;


    function __model()
    {
        return (new \Lemmon\Sql\Query)->select('values')->where(['site_id' => SITE_ID, 'key LIKE ?' => 'contact/%']);
    }


    private function _load()
    {
        if (!$this->_loaded) {
            $this->_data = Values::getMany('contact', $raw);
            $this->_dataRaw = $raw;
            $this->_loaded = true;
        }
    }


    function count()
    {
        $this->_load();
        return $this->_data ? 1 : 0;
    }


    function offsetExists($offset)
    {
        return (bool)$this->offsetGet($offset);
    }


    function offsetGet($offset)
    {
        $this->_load();
        if (strpos('/', $offset)) {
            return $this->_dataRaw[$offset];
        } else {
            return $this->_data[$offset];
        }
    }


    function inLocale() {}
    function getRecent() {}
}
