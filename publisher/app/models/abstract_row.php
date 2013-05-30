<?php
/**
* 
*/
class AbstractRow extends \Lemmon\Model\AbstractRow
{
    static private $_route;


    static function setRoute($route)
    {
        self::$_route = $route;
    }


    final protected function getRoute()
    {
        return self::$_route;
    }
}
