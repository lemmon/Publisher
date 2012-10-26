<?php
/**
* 
*/
class TreeItem
{
	private $_id;
	private $_languageId;
	private $_name;
	private $_parent;
	private $_children = [];

	private static $_instances = [];
	private static $_rootItems = [];


	private function __construct($id, $language_id, $name)
	{
		// defaults
		$this->_id         = $id;
		$this->_languageId = $language_id;
		$this->_name       = $name;
		// root
		self::$_rootItems[$language_id][$id] = $this;
		// instance
		self::$_instances[$id] = $this;
	}


	static function newInstance($id, $language_id, $name)
	{
		return new self($id, $language_id, $name);
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


	static function getRootItems($language_id)
	{
		return self::$_rootItems[$language_id];
	}


	function getId()
	{
		return $this->_id;
	}


	function getLanguageId()
	{
		return $this->_languageId;
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
		return array_search($this->_id, array_keys($this->_parent ? $this->_parent->_children : self::$_rootItems[$this->_languageId])) + 1;
	}


	function addChild($child)
	{
		$this->_children[$child->getId()] = $child;
		$child->_parent = $this;
		unset(self::$_rootItems[$child->getLanguageId()][$child->getId()]);
		return $this;
	}
}
