<?php
/**
* 
*/
class Admin_Pages_Controller extends \Lemmon\Model\Scaffold
{


	function index()
	{
		$this->data += Pages::fetchActiveWithLanguages();
	}


	function create()
	{
		$this->data += Pages::fetchActiveWithLanguages();
		return parent::create();
	}


	function create_()
	{
		if ($f = $_POST)
		{
			// sanitize fields
			foreach ($f as $key => $val)
			{
				if ($val) $f[$key] = trim($f[$key]);
				else $f[$key] = null;
			}
			// save
			try
			{
				$page = new Page;
				$page->set($f);
				$page->save();
				$this->flash->notice(\Lemmon_I18n::t('Item has been created'));
				return $this->request->redir(':section');
			}
			catch (\Lemmon\Model\ValidationException $e)
			{
				$this->flash->error($e->getMessage())
				            ->error(\Lemmon_I18n::t('Item has NOT been created'))
				            ->errorFields($e->getFields());
			}
		}
		// model
		$this->data['item'] = new Page;
		
		// custom data
		$this->data += Pages::fetchActiveWithLanguages();
	}


	function update()
	{
		if ($id = $this->route->id and $item = Page::find($id))
		{
			// POST
			if ($f = $_POST)
			{
				// empty field to null
				foreach ($f as $key => $val) if (!$val) $f[$key] = null;
				// save
				try
				{
					$item->set($f);
					$item->save();
					$this->flash->notice(\Lemmon_I18n::t('Item has been updated'));
					return $this->request->redir(':section');
				}
				catch (\Lemmon\Model\ValidationException $e)
				{
					$this->flash->error($e->getMessage())
					            ->error(\Lemmon_I18n::t('Item has NOT been updated'))
					            ->errorFields($e->getFields());
				}
			}
			// default values
			else
			{
				$this->data['f'] = $item;
			}
			// model
			$this->data['item'] = $item;
		}
		else
		{
			throw new \Exception('Page not found.');
		}

		// custom data
		$this->data += Pages::fetchActiveWithLanguages();
	}
}
