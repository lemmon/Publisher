<?php
/**
* 
*/
class Forms_Controller extends AbstractFrontend_Controller
{


    function __initModule()
    {
    }


    function index()
    {
        $this->data['form'] = new Form($this->page);
    }


    function submit()
    {
        return $this->_res(function(){
            // process form
            if ($f = $_POST and $this->_validate($f)) {
                (new FormsResponse)->setForm($this->page->id)->setData($f)->save();
                mail('tryton@lemmonjuice.com', $this->page->name, json_encode($f, JSON_PRETTY_PRINT));
                $this->flash->setNotice('Form sent successfully.');
            } else {
                $this->flash->setError('Some funky error.');
            }
        }, [], function(){
            // empty (default) response
            http_response_code(204);
            exit;
        });
    }


    private function _validate($f)
    {
        return true;
    }
}
