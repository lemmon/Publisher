<?php
/**
* 
*/
class Query
{


    function __call($name, $arguments)
    {
        $class_name = 'Query' . ucfirst($name);
        return new $class_name;
    }
}
