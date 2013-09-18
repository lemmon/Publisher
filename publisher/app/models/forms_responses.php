<?php
/**
* 
*/
class FormsResponses extends AbstractModuleModel
{
    #static $table     = 'forms_responses';
    #static $sanitize  = [':all' => 'empty_to_null'];
    #static $required  = [];
    #static $timestamp = ['created_at'];


    protected function __initModule()
    {
        // default order
        $this->order('created_at DESC');
    }
}
