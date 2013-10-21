<?php
/**
* 
*/
class Admin_Contact_Controller extends Admin_Forms_Controller
{


    function details()
    {
        // page
        $page = $this->getPage();
        // form
        return $this->_res(function() use ($page){
        
            // on POST
            if ($f = $this->sanitize($_POST)){
                Values::putMany('contact', $f);
                $this->flash->setNotice('Contact info has been updated');
                return $this->route->getSection($page);
            }
            // load values
            else {
                $this->data['f'] += (array)Values::getMany('contact');
            }
        
        });
    }
}
