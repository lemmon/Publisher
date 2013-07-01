<?php
/**
* 
*/
class Pages_Controller extends AbstractFrontend_Controller
{


    protected function __initModule()
    {
        if (!$this->page) {
            // 404
            http_response_code(404);
            die('HTTP Error 404: Page Not Found');
        }
    }


    function index()
    {
    }


    function subpage()
    {
    }
}
