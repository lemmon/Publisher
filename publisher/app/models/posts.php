<?php
/**
* 
*/
class Posts extends AbstractModuleModel
{
    static $required  = ['name', 'state_id' => 'allow_null', 'locale_id'];
    static $timestamp = ['created_at', 'updated_at'];
    static $uploads   = ['image'];
    static $uploadDir = 'posts/%Y-%m';


    function __initModule()
    {
        // order
        $this->order('COALESCE(published_at, updated_at) DESC');
        // frontend
        if (Application::$isFrontend)
            $this->where(['state_id' => 1]);
    }
}
