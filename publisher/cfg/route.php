<?php
/**
* 
*/
class Route extends \Lemmon\Route
{
	private $_extend;


	protected function __init()
	{
		$this->_extended = new RouteExtended;
		//
		// backend
		//
		if ($this->getParam(1) == 'admin')
		{
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

			$this->register('section', 'admin/@%1/%2/%3');
			$this->register('action', 'admin/@/%1/%2');
			$this->register('page', 'admin/@/%1');

			$this->register('create', 'admin/@$_section/create');
			$this->register('update', 'admin/@$_section/update/$id');
			$this->register('delete', 'admin/@$_section/delete/$id');
			$this->register('crud', 'admin/@$_section/$action/$id');

			$this->register('login', 'admin/login');
			$this->register('logout', 'admin/logout');
		}
		//
		// services
		//
		elseif (substr($this->getSelf(), -4) == '.css')
		{
			Application::setController('templates');
			Application::setAction('css');
		}
		//
		// uploads
		//
		elseif ($this->match('*/uploads(/0$dim)(/$image)$', ['dim' => '\d*x\d*\w*', 'image' => '.*\.(jpe?g|gif|png)']))
		{
			Application::setController('uploads');
			Application::setAction('image');
		}
		//
		// frontend
		//
		else
		{
			if ($this->_extended->match($this))
			{
				// user extended
			}
			elseif ($this->match('p/$id(/$paginate)', ['id' => '\d+', 'paginate' => '\d+']))
			{
				// subpages
				Application::setController('pages');
				Application::setAction('subpage');
			}
			elseif ($this->match('b/$id', ['id' => '\d+']))
			{
				// posts
				Application::setController('posts');
				Application::setAction('detail');
			}
			elseif ($this->match('c/$id(/$paginate)', ['id' => '\d+', 'paginate' => '\d+']))
			{
				// categories
				Application::setController('posts');
				Application::setAction('category');
			}
			elseif (!$this->getParam(1))
			{
				// frontpage
				Application::setController('pages');
			}
			else
			{
				die('404');
			}

			$this->register('home', '/');
			$this->register('page', 'p/$id');
			$this->register('post', 'b/$id');
			$this->register('category', 'c/$id');
			$this->register('paginate', '@/@/%1');
		}
		//
		// user defined routes
		//
		$this->_extended->register($this);
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


	function getUpload($file, $dim = null)
	{
		return '/user/uploads/' . ($dim ? "0{$dim}/" : '') . $file;
	}


	function getJQuery()
	{
		return $this->getVendor('jquery/jquery-1.8.2.min.js');
	}
}
