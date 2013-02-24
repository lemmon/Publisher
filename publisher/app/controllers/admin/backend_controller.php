<?php
/**
* 
*/
abstract class Admin_Backend_Controller extends Application
{
	protected $auth;
	protected $user;


	final protected function __initSection()
	{
		//
		// auth
		if ($this->auth = new Auth and $this->auth->hasIdentity())
		{
			// user signed in
			$this->data['user'] = $this->user = $this->auth->getIdentity();
		}
		elseif (self::getController() != 'admin/login')
		{
			// must login
			return $this->request->redir(':login');
		}
		//
		// i18n
		if ($i18n = $this->config['i18n'])
		{
			if (file_exists($_file = USER_DIR . "/i18n/{$i18n}.php"))
			{
				Lemmon_I18n::setBase(dirname($_file));
				Lemmon_I18n::setLocale($i18n);
			}
		}
		//
		// templating
		$this->template = (new \Lemmon\Template\Template(ROOT_DIR . '/app/views', self::getAction()))
			->appendFilesystem(self::getController())
			->appendFilesystem(self::getController(), USER_DIR . '/app/views')
			->setExtension(new TemplateExtensionAdmin);
			;
	}
}
