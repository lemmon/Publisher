<?php

use \Lemmon\Sql\Query as SqlQuery;

/**
* 
*/
class Page extends AbstractPage
{
	private $_cache = [];
	private $_active;


	static function getOptionsFor($model)
	{
		return Languages::find(['!locale' => null]);
	}


	static function getOptionsForState()
	{
		return States::getOptions();
	}


	function getChildren()
	{
		if ($this->id)
			return Application::$isFrontend
				// accessible for frontend templating
				? new QueryPages(['parent_id' => $this->id])
				// get generic children
				: parent::getChildren()
				;
	}


	function getContent()
	{
		if (!array_key_exists('content', $this->_cache))
		{
			return $this->_cache['content'] = (new SqlQuery)->select('pages_blocks')->where([
				'page_id' => $this->id,
				'name'    => 'main-content',
			])->first()->content;
		}
		else
		{
			return $this->_cache['content'];
		}
	}


	function getLanguage()
	{
		return Locales::fetch($this->locale);
	}


	function getRoot()
	{
		return Page::find($this->root_id);
	}


	function getParent()
	{
		if ($this->parent_id)
			return Page::find($this->parent_id);
	}


	function getState()
	{
		return States::getOptions()[$this->state_id];
	}


	function getUrl()
	{
		if (!$this->parent_id and $this->top == 1)
		{
			return Route::getInstance()->to(':home');
		}
		else
		{
			return Route::getInstance()->to(':page', $this);
		}
	}


	function isActive($active = null)
	{
		if ($active !== null)
		{
			$this->_active = $active;
			return $this;
		}
		else
		{
			return is_null($_ = $this->_active) ? $this->id == Nav::getCurrentPage()->id : $_;
		}
	}


	function isSelected()
	{
		return in_array($this->id, explode(',', Nav::getCurrentPage()->path_query));
	}
}
