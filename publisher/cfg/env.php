<?php
/**
* 
*/
class Env extends \Lemmon\Environment
{


	protected function __init()
	{
		self::setDev();
		
		// DEVELOPMENT
		if (self::isDev())
		{
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
