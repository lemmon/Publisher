<?php
/**
* 
*/
class Env extends \Lemmon\Environment
{


    protected function __init()
    {
        // DEVELOPMENT
        if (self::isDev())
        {
            // template dev environment
            \Lemmon\Template\Template::setDefaultEnvironment([
                'debug' => true,
            ]);
        }
        // PRODUCTION
        else
        {
            // template dev environment
            \Lemmon\Template\Template::setDefaultEnvironment([
                'cache' => BASE_DIR . '/cache/tpl',
            ]);
        }

        // uploads
        \Lemmon\Model\Schema::setDefaultUploadDir(BASE_DIR . '/user/uploads');
    }
}
