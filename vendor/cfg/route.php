<?php
/**
* 
*/
class Route extends \Lemmon\Route
{


	protected function __init()
	{
		if ($this->getParam(1)=='admin')
		{
			//
			// backend
			//
			if ($this->match('$controller(/$page)(/$action(/$id))', ['controller'=>'admin\/[\w\-]+', 'action'=>'[\w\-]+', 'id'=>'\d+', 'page'=>'\d+']))
			{
				// general CRUD
			}
			else
			{
				Application::setController('admin/index');
			}

			$this->register('home', 'admin');
			$this->register('site', '/');

			$this->register('crud', 'admin/@$section/$action/$id');
			$this->register('section', 'admin/@$section/$page');
			$this->register('create', 'admin/@$section/create');
			$this->register('update', 'admin/@$section/update/$id');
			$this->register('delete', 'admin/@$section/delete/$id');

			$this->register('login', 'admin/login');
			$this->register('logout', 'admin/logout');
		}
		elseif (substr($this->getSelf(), -4)=='.css')
		{
			//
			// services
			//
			Application::setController('services/templates');
			Application::setAction('css');
		}
		else
		{
			//
			// frontend
			//
			if ($this->match('$controller/$action', ['controller'=>'emails', 'action'=>'\w+']))
			{
				// crud
			}
			elseif ($this->match('$id(/$slug).html', ['id'=>'\d+', 'slug'=>'.+']))
			{
				// subpages
				Application::setController('pages');
			}
			else
			{
				// frontpage
				Application::setController('pages');
			}

			$this->register('page', '$id/{$name}.html');
		}
	}


	function getVendor($link)
	{
		return '/vendor/public/' . $link;
	}
}
