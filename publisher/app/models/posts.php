<?php
/**
* 
*/
class Posts extends AbstractModuleModel
{
    /*
    static $uploads   = ['image'];
    static $uploadDir = 'posts/%Y-%m';
    */


    protected function __initModule()
    {
        // order
        $this->order('COALESCE(weight, 0) ASC, COALESCE(published_at, updated_at) DESC');
    }
}
