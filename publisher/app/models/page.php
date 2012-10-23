<?php
/**
* 
*/
class Page extends \Lemmon\Model\AbstractRow
{
	static protected $model = 'Pages';


	static function getOptionsFor($model)
	{
		return Languages::find(['!locale' => null]);
	}


	static function getOptionsForState()
	{
		return States::getOptions();
	}


	function getState()
	{
		return States::getOptions()[$this->state_id];
	}


	/*
	function hasChildren()
	{
		return (bool)Pages::find(['parent_id' => $this->id])->count();
	}


	function getChildren()
	{
		return Pages::find(['parent_id' => $this->id]);
	}
	*/


	protected function onValidate(&$f)
	{
		
	}


	protected function onAfterCreate()
	{
		
	}


	protected function onAfterUpdate()
	{
		
	}
}
