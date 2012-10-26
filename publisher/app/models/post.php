<?php
/**
* 
*/
class Post extends \Lemmon\Model\AbstractRow
{
	static protected $model = 'Posts';


	static function getCustomOptionsForLanguage()
	{
		return Languages::find([
			'id' => (new \Lemmon\Sql\Query())->select('pages')->distinct('language_id'),
		]);
		// TODO
		#return Languages::find()->has('Pages');
		// TODO
		#return Languages::find()->has('Pages', ['parent_id' => null, '!state_id' => null]);
		// TODO
		#return Languages::find(['Pages.parent_id' => null, '!Pages.state_id' => null]);
	}


	/*
	static function getOptionsForLanguageInGroups()
	{
		$groups = [];
		if ($active = (new \Lemmon\Sql\Query())->select('pages')->distinct('language_id'))
		{
			$groups[] = [
				'caption' => '-',
				'data'    => Languages::find(['id' => $active]),
			];
		}
		$groups[] = [
			'caption' => '-',
			'data'    => Languages::find(['!locale' => null, '!id' => $active]),
		];
		
		return $groups;
	}
	*/


	static function getOptionsForState()
	{
		return States::getOptions();
	}


	function getLanguage()
	{
		return Language::find($this->language_id);
	}


	function getState()
	{
		return States::getOptions()[$this->state_id];
	}


	protected function onValidate(&$f)
	{
		// published
		if ($f['state_id'] and !$this->dataDefault['state_id'])
		{
			$f['published_at'] = new \Lemmon\Sql\Expression('NOW()');
		}
		elseif (!$f['state_id'])
		{
			$f['published_at'] = null;
		}
		else
		{
			#dump($f);
			#die('--v');
		}
	}


	protected function onBeforeCreate()
	{
		dump('onBeforeCreate');
	}


	protected function onBeforeUpdate()
	{
		dump('onBeforeUpdate');
	}


	protected function onAfterCreate()
	{
		dump('onAfterCreate');
	}


	protected function onAfterUpdate()
	{
		dump('onAfterUpdate');
	}
}
