<?php
/**
* 
*/
abstract class AbstractModuleModel extends \Lemmon\Model\AbstractModel
{
    static $table     = 'items';
    static $sanitize  = [':all' => 'empty_to_null'];
    static $required  = ['page_id', 'state_id' => 'allow_null', 'name'];
    static $timestamp = ['created_at', 'updated_at'];

    static $allowChildren = true;


    final protected function __init()
    {
        // site_id
        if (defined('SITE_ID')) {
            $this->where('site_id', SITE_ID);
        }
        // default order
        $this->order('COALESCE(weight, 0), name');
        // init module
        $this->__initModule();
    }


    protected function __initModule()
    {
    }
}
