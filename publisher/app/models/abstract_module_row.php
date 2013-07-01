<?php
/**
* 
*/
class AbstractModuleRow extends AbstractRow
{
    protected function __initItem() {}
    protected function __validate(array &$f) {}


    final protected function __init()
    {
        if ($this->_getState() != self::STATE_EMPTY and defined('SITE_ID') and $this->site_id != SITE_ID) {
            throw new Exception('Access Denied');
        }
    }


    final protected function onValidate(&$f)
    {
        // validate module
        $this->__validate($f);
        // site_id
        if (defined('SITE_ID')) {
            $f['site_id'] = SITE_ID;
        }
    }


    function getUrl()
    {
        return $this->getRoute()->to(':module_item', $this);
    }


    function getPage()
    {
        return Page::find($this->page_id);
    }
}
