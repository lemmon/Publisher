<?php
/**
* 
*/
class Contact_Controller extends Forms_Controller
{


    function index()
    {
        parent::index();
        
        $this->data['contact'] = $this->page->getBlock('contact') == 'section'
                               ? null
                               : (new QueryContact)->inLocale($this->page->locale)
                               ;
    }
}
