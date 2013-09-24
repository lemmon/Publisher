<?php
/**
* 
*/
class TemplateFrontend extends \Lemmon\Template\Template
{
    private $_cache;


    function setCache($cache)
    {
        $this->_cache = $cache;
        return $this;
    }


    function render($data)
    {
        return $this->_cache->put(parent::render($data));
    }
}
