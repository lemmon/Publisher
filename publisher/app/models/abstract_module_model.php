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


    protected function __initModule() {}


    final protected function __init()
    {
        //
        // site_id
        if (defined('SITE_ID')) {
            $this->where('site_id', SITE_ID);
        }
        //
        // item type
        $this->where('type_id', $this->getSchema()->rowClass);
        //
        // default order
        $this->order('COALESCE(weight, 0), name');
        //
        // init module
        $this->__initModule();
    }


    /*
    static function fetchActiveByLanguage()
    {
        $data = [];
        // load items
        foreach (self::find() as $item) {
            $data[$item->locale_id][$item->id] = $item;
        }
        //
        return $data;
    }
    */
}
