<?php
/**
* Frontend.
*/
class Page extends AbstractPage
{
    private $_active;
    private $_selected;


    function getCaption()
    {
        return $this->data['caption'] ?: $this->data['name'];
    }


    function getChildren()
    {
        if ($this->id) {
            return new QueryPages(['parent_id' => $this->id]);
        }
    }


    function getNext()
    {
        return (new QueryPages(['parent_id' => $this->parent_id, 'locale_id' => $this->locale_id, 'top > ?' => $this->top]))[0];
    }


    function hasChildren()
    {
        return (bool)$this->getChildren()->count();
    }


    function isActive($active = null)
    {
        return is_null($_ = $this->_active)
               ? $this->_active = ($this->id == Nav::getActivePageId())
               : $_;
    }


    function isSelected()
    {
        return is_null($_ = $this->_selected)
               ? $this->_selected = (in_array($this->id, explode(',', Nav::getCurrentPage()->path_query)) ?: ($this->isActive() ? false : $this->id == Nav::getCurrentPage()->id))
               : $_;
    }
}
