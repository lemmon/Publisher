<?php
/**
* 
*/
abstract class AbstractModuleModel extends \Lemmon\Model\AbstractModel
{
    static $allowChildren = false;


    final protected function __init()
    {
        // site_id
        if (defined('SITE_ID')) {
            $this->where('site_id', SITE_ID);
        }
        // init module
        $this->__initModule();
    }


    protected function __initModule()
    {
        
    }
}
