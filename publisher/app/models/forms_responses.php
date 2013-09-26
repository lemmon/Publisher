<?php
/**
* 
*/
class FormsResponses extends AbstractModuleModel
{


    protected function __initModule()
    {
        // default order
        $this->order('created_at DESC');
    }
}
