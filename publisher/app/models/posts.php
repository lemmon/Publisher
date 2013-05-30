<?php
/**
* 
*/
class Posts extends \Lemmon\Model\AbstractModel
{
    static $required  = ['name', 'state_id' => 'allow_null', 'locale_id'];
    static $timestamp = ['created_at', 'updated_at'];
    static $uploads   = ['image'];
    static $uploadDir = 'posts/%Y-%m';


    function __init()
    {
        if (defined('SITE_ID')) {
            $this->where('site_id', SITE_ID);
        }
        // order
        $this->order('COALESCE(published_at, updated_at) DESC');
        // frontend
        if (Application::$isFrontend)
            $this->where(['state_id' => 1]);
    }
}
