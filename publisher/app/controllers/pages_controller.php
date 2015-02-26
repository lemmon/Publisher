<?php
/**
* 
*/
class Pages_Controller extends AbstractFrontend_Controller
{


    function index()
    {
        $this->_fixHost();
    }


    function subpage()
    {
        $this->_fixHost();
    }


    private function _fixHost()
    {
        if ((string)$this->route->getSelf()->includeHost() != (string)$this->page->getUrl()->includeHost()) {
            header('Location: ' . (string)$this->page->getUrl()->includeHost(), true, 301);
            exit;
        }
    }
}
