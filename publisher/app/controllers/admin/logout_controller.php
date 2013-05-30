<?php
/**
* 
*/
class Admin_Logout_Controller extends Admin_Backend_Controller
{


    function index()
    {
        $this->auth->clearIdentity();
        $this->flash->setNotice('You have been signed out');
        return $this->request->redir(':login');
    }
}
