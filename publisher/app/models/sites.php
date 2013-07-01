<?php
/**
* 
*/
class Sites extends \Lemmon\Model\AbstractModel
{
    static $required  = ['founder_id', 'locale_id', 'locale_id', 'name', 'link', 'email'];
    static $timestamp = ['created_at', 'updated_at'];


    function __init()
    {
    }
}
