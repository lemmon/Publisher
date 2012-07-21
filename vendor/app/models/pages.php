<?php
/**
* 
*/
class Pages extends Lemmon_Model
{
	private $_parent_id_old;


	private static $_index = array();


	protected function define()
	{
		$this->required('name');
		
		$this->belongsTo('language');
		$this->belongsTo('root', array('model'=>'Pages', 'field'=>'root_id'));
		$this->belongsTo('parent', array('model'=>'Pages'));
		$this->hasMany('children', array('model'=>'Pages', 'field'=>'parent_id'));
		$this->hasOne('@state');

		$this->timeStampable();

		$this->sort('top');
	}


	public function getParents($language_id=null, $parent_id=null, $step=0)
	{
		if (!$language_id)
		{
			$data0 = Languages::make()->excludeNull('state_id')->all();
			foreach ($data0 as $item)
			{
				$data[ ':' . $item->id ] = $item->country->language_local;
				$data = $data + self::getParents($item->id, null, $step + 1);
			}
		}
		else
		{
			/*
			$indent = $step ? join('', array_fill(0, $step, ' ... ')) : '';
			$data0 = Pages::make()->exclude((int)$this->id)->find('language_id', $language_id)->find('parent_id', $parent_id)->sort('top')->pairs();
			foreach ($data0 as $id => $name)
			{
				$data[$id] = $indent . $name;
				$data = $data + self::getParents($language_id, $id, $step + 1);
			}
			*/
		}
		return (array)$data;
	}


	protected function onValidate(&$f)
	{
		$ok = true;
		// state
		if (!array_key_exists('state_id', $f))
		{
			Application::getInstance()->getFlash()->error('Missing field State');
			$ok = false;
		}
		// language_id, parent_id
		if ($language_or_parent=$f['language_or_parent'])
		{
			if ($language_or_parent{0}==':')
			{
				$f['language_id'] = substr($language_or_parent, 1);
				$f['parent_id'] = null;
			}
			else
			{
				$f['language_id'] = Pages::make($language_or_parent)->language_id;
				$f['parent_id'] = $language_or_parent;
			}
			unset($f['language_or_parent']);
		}
		else
		{
			Application::getInstance()->getFlash()->error('Missing field Language')->errorField('parent');
			#Application::getInstance()->flashError('Missing field Parent')->flashErrorField('parent');
			$ok = false;
		}
		// top
		if (!($top=$f['top']) or $f['top']!=$this->top or $f['prent_id']!=$this->parent_id)
		{
			$items = new Pages();
			$items->find('language_id', $f['language_id']);
			if ($f['parent_id']): $items->find('parent_id', $f['parent_id']); else: $items->findNull('parent_id'); endif;
			if ($this->id) $items->exclude($this->id);
			if ($top)
			{
				foreach ($all=$items->distinct('id') as $i => $_id)
				{
					Db::q('UPDATE [pages] SET `top`=%i WHERE `id`=%i LIMIT 1', $i+1+(int)($i>$top-2), $_id);
				}
				if ($top>count($all)+1) $f['top'] = count($all) + 1;
			}
			else
			{
				$f['top'] = end($items->distinct('top')) + 1;
			}
		}
		// content
		$f['content'] = preg_replace('/(\.\.\/)+uploads\//', '/uploads/', $f['content']);
		//
		return $ok;
	}


	protected function onAfterCreate($f)
	{
		self::onAfterUpdate($f);
	}


	protected function onAfterUpdate($f)
	{
		foreach (Pages::make()->sort('parent_id, top')->all() as $item)
		{
			Pages::_updateChildren($item);
		}
	}


	private function _updateChildren($item)
	{
		$query = new Lemmon_MySQL_Query_Builder('pages');
		$query->find($item->id);
		$query->update(array(
			'root_id' => $item->parent_id ? $item->parent->root_id : $item->id,
			'path_query' => $item->parent_id ? ltrim($item->parent->path_query . ',' . $item->parent->id, ',') : null,
			'children_query' => join(',', $item->_getChildren()),
		));
	}


	private function _getChildren($save=false, $res=array())
	{
		$res[] = $this->id;
		foreach ($this->children->all() as $item) $res = $item->_getChildren($save, $res);
		return $res;
	}
}
