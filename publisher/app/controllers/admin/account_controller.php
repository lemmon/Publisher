<?php

use \Lemmon\Form\Scaffold;

/**
* 
*/
class Admin_Account_Controller extends Admin_Backend_Controller
{


    function main()
    {
    }


    function index()
    {
    }


    function logout()
    {
        $this->auth->clearIdentity();
        $this->flash->setNotice('You have been signed out');
        return $this->route->to(':admin/login');
    }
}
