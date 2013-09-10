<?php
/**
* 
*/
abstract class AbstractModuleRow extends AbstractRow
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
        //
        // validate module
        $this->__validate($f);
        //
        // site_id
        if (defined('SITE_ID')) {
            $f['site_id'] = SITE_ID;
        }
        //
        // published
        if ($f['state_id'] and !$this->dataDefault['state_id']) {
            $f['published_at'] = ($this->dataDefault['published_at']) ?: new \Lemmon\Sql\Expression('NOW()');
        } elseif (!$f['state_id']) {
            $f['published_at'] = null;
        }
    }


    function getState()
    {
        return States::getOptions()[$this->state_id];
    }


    function getLocale()
    {
        return Locales::fetch($this->locale_id);
    }


    function getPage()
    {
        return Page::find($this->page_id);
    }


    function getUrl()
    {
        return $this->getRoute()->to(':module_item', $this);
    }
}
