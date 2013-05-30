<?php
/**
* 
*/
class Admin_Posts_Controller extends Admin_AbstractModule_Controller
{


    protected function getOptions()
    {
        return [
            'categories' => Categories::fetchActiveByLocale(),
        ];
    }
}