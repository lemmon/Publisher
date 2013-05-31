<?php
/**
* 
*/
class Env extends \Lemmon\Environment
{


    protected function __init()
    {
        if (self::isDev()) {
            
            //
            // DEVELOPMENT
            //
            
            // template dev environment
            \Lemmon\Template\Template::setDefaultEnvironment([
                'debug' => true,
            ]);

        }
        else {
            
            //
            // PRODUCTION
            //
            
            // template dev environment
            \Lemmon\Template\Template::setDefaultEnvironment([
                'cache' => BASE_DIR . '/cache/tpl',
            ]);

        }
        
        // uploads
        \Lemmon\Model\Schema::setDefaultUploadDir(BASE_DIR . '/user/uploads');
    }
}
