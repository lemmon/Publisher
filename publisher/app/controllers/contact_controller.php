<?php
/**
* 
*/
class Contact_Controller extends Forms_Controller
{


    function index()
    {
        parent::index();
        
        $this->data['contact'] = (new QueryContact);
    }
}
