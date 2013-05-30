<?php
/**
* 
*/
class TreeItem
{
    private $_id;
    private $_locale;
    private $_name;
    private $_parent;
    private $_children = [];

    private static $_instances = [];
    private static $_rootItems = [];


    private function __construct($id, $locale, $name)
    {
        // defaults
        $this->_id     = $id;
        $this->_locale = $locale;
        $this->_name   = $name;
        // root
        self::$_rootItems[$locale][$id] = $this;
        // instance
        self::$_instances[$id] = $this;
    }


    static function newInstance($id, $locale, $name)
    {
        return new self($id, $locale, $name);
    }


    static function getInstance($id)
    {
        if ($instance=self::$_instances[$id])
        {
            return $instance;
        }
        else
        {
            throw new \Exception(sprintf('Instance Id: %d not available.', $id));
        }
    }


    static function getAllItems()
    {
        return self::$_instances;
    }


    static function getRootItems($locale)
    {
        return self::$_rootItems[$locale];
    }


    function getId()
    {
        return $this->_id;
    }


    function getLanguageId()
    {
        return $this->_locale;
    }


    function getName()
    {
        return $this->_name;
    }


    function getChildren()
    {
        return $this->_children;
    }


    function getRootId()
    {
        return $this->_parent ? $this->_parent->getRootId() : $this->_id;
    }


    function getPathQuery($include_self=false)
    {
        return ($this->_parent ? $this->_parent->getPathQuery(true) . ($include_self ? ',' : '') : null) . ($include_self ? $this->_id : null);
    }


    function getChildrenQuery()
    {
        $q = $this->_id;
        if ($this->_children)
        {
            foreach ($this->_children as $child) $q .= ',' . $child->getChildrenQuery();
        }
        return $q;
    }


    function getLevel($level=0)
    {
        return $this->_parent ? $this->_parent->getLevel($level+1) : $level;
    }


    function getTop()
    {
        return array_search($this->_id, array_keys($this->_parent ? $this->_parent->_children : self::$_rootItems[$this->_locale])) + 1;
    }


    function addChild($child)
    {
        $this->_children[$child->getId()] = $child;
        $child->_parent = $this;
        unset(self::$_rootItems[$child->getLanguageId()][$child->getId()]);
        return $this;
    }
}
