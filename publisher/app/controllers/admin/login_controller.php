<?php
/**
* 
*/
class Admin_Login_Controller extends Admin_Backend_Controller
{


    function index()
    {
        // redir if user is already logged
        if ($this->auth->hasIdentity()) {
            return $this->route->to(':admin/home');;
        }
        
        /* *
        // clear current identity if necessary
        if ($this->auth->hasIdentity()) {
            $this->auth->clearIdentity();
            return $this->route->getSelf();
        }
        /* */
        
        // do login
        return $this->_res(function(){

            // on Post
            if ($f = $_POST) {
                if ($this->auth->authenticate($f['email'], $f['password'])) {
                    // ok
                    $this->auth->storeIdentity();
                    $this->flash->setNotice('You have been successfully logged in');
                    return $this->route->to(':admin/home');
                }
                else {
                    $this->flash->setError('Invalid email or password entered');
                }
            }

        });
    }
}
