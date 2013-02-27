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
            \Lemmon\Template\Template::setDefaultEnvironment([
                'auto_reload' => true,
                'debug'       => true,
                'cache'       => BASE_DIR . '/cache/tpl',
            ]);
        }
        // PRODUCTION
        else
        {
            // TODO
            die('EnvBase/ PRODUCTION');
            // template dev environment
            \Lemmon\Template\Template::setDefaultEnvironment([
                'cache' => BASE_DIR . '/cache/tpl',
            ]);
        }

        // uploads
        \Lemmon\Model\Schema::setDefaultUploadDir(BASE_DIR . '/user/uploads');
    }
}
