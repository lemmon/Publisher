<?php
/**
* 
*/
class Env extends Lemmon\Environment
{


	protected function __init()
	{
		// DEVELOPMENT
		if (Route::matchHost('.dev') or true)
		{
			self::setDevelopment();
			// error reporting
			set_error_handler('Lemmon\ErrorHandler::error');
			// template dev environment
			Lemmon\Template::setEnvironment([
				'auto_reload' => true,
				'debug'       => true,
			]);
		}
		// PRODUCTION
		else
		{
			// TODO
			die('EnvBase/ PRODUCTION');
		}
	}
}
