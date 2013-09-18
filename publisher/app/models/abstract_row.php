<?php
/**
* 
*/
class AbstractRow extends \Lemmon\Model\AbstractRow
{
    private static $_route;


    static function setRoute($route)
    {
        self::$_route = $route;
    }


    final protected function getRoute()
    {
        return self::$_route;
    }
}
