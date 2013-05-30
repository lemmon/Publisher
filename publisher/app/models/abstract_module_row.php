<?php
/**
* 
*/
class AbstractModuleRow extends AbstractRow
{


    protected function __validate(array &$f) {}


    final protected function onValidate(&$f)
    {
        // validate module
        $this->__validate($f);
        // site_id
        if (defined('SITE_ID')) {
            $f['site_id'] = SITE_ID;
        }
    }
}
