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
		// i18n
		if ($i18n = $this->config['i18n'] and (is_string($i18n) or ($i18n = $i18n['front'])))
		{
			if (file_exists($_file = USER_DIR . "/i18n/{$i18n}.php"))
			{
				Lemmon_I18n::setBase(dirname($_file));
				Lemmon_I18n::setLocale($i18n);
			}
		}
		//
		// templating
		$this->template = (new \Lemmon\Template\Template(USER_DIR . '/template', 'index'))
			->setExtension(new TemplateExtensionUser);
		//
		// default services
		$this->data += [
			'nav'   => new Nav,
			'query' => new Query,
		];
	}
}
