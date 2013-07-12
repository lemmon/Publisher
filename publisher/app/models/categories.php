<?php
/**
* 
*/
class Categories extends AbstractModuleModel
{
    static $required  = ['name', 'state_id' => 'allow_null', 'locale'];
    static $timestamp = ['created_at', 'updated_at'];
    static $uploads   = ['image'];
    static $uploadDir = 'categories/%Y-%m';


    protected function __initModule()
    {
        // default order
        $this->order('COALESCE(top, 0), name');
    }


    static function fetchActiveByLocale()
    {
        $pages = [];
        // load pages
        foreach (Categories::find() as $item) {
            $pages[$item->locale][$item->id] = $item;
        }
        //
        return $pages;
    }
}
