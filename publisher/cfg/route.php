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
			if ($this->match('$controller(/$page)(/$action(/$id))', ['controller' => 'admin\/[\w\-]+', 'action' => '[\w\-]+', 'id' => '\d+', 'page' => '\d+']))
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
			Application::setController('templates');
			Application::setAction('css');
		}
		else
		{
			//
			// frontend
			//
			if ($this->match('p/$id', ['id' => '\d+']))
			{
				// subpages
				Application::setController('pages');
				Application::setAction('subpage');
			}
			else
			{
				// frontpage
				Application::setController('pages');
			}

			$this->register('home', '/');
			$this->register('page', 'p/$id');
		}
	}


	function getHome()
	{
		return '/';
	}


	function getPublisher($link)
	{
		return '/publisher/public/' . $link;
	}


	function getVendor($link, $params=null)
	{
		return $this->to('vendor/' . $link, $params);
	}


	function getTemplate($link)
	{
		return $this->to('user/template/' . $link);
	}


	function getJQuery()
	{
		return $this->getVendor('jquery/jquery-1.8.2.min.js');
	}
}
