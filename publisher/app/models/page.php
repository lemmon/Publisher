<?php
/**
* 
*/
class Page extends AbstractPage
{


	static function locked()
	{
		return Pages::locked();
	}


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
		return self::locked() ? new QueryPages(['parent_id' => $this->id]) : parent::getChildren();
	}


	function getLanguage()
	{
		return Language::find($this->language_id);
	}


	function getRoot()
	{
		return Page::find($this->root_id);
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


	function isActive()
	{
		return $this->id == Nav::getCurrentPage()->id;
	}


	function isSelected()
	{
		return in_array($this->id, explode(',', Nav::getCurrentPage()->path_query));
	}
}
