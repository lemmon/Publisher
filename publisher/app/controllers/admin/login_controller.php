<?php
/**
* 
*/
class Admin_Login_Controller extends Admin_Backend_Controller
{


    function index()
    {
        // on Post
        if ($f = $_POST)
        {
            if ($this->auth->authenticate($f['email'], $f['password']))
            {
                // ok
                $this->auth->storeIdentity();
                $this->flash->setNotice('You have been successfully logged in');
                return $this->request->redir(':home');
            }
            else
            {
                $this->flash->setError('Invalid email or password entered');
            }
        }
        // clear current identity if necessary
        elseif ($this->auth->hasIdentity())
        {
            $this->auth->clearIdentity();
            return $this->request->redir($this->route->getSelf());
        }
    }
}
