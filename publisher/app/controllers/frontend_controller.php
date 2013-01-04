<?php
/**
* 
*/
abstract class Frontend_Controller extends Application
{


	final protected function __initSection()
	{
		//
		// app environment
		Application::$isFrontend = true;
		//
		// templating
		$this->template = (new \Lemmon\Template\Template(USER_DIR . '/template', 'index'));
		//
		// default services
		$this->data += [
			'nav'   => new Nav,
			'query' => new Query,
		];
	}
}
