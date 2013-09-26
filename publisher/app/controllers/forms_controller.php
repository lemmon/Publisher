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
            
            //
            // process form
            if (($f = $_POST or $f = $_GET) and $this->_validate($f)) {
                try {
                    (new FormsResponse)->set([
                        'name'      => $this->page->name,
                        'page_id'   => $this->page->id,
                        'locale_id' => $this->page->locale_id,
                        'state_id'  => null,
                        'data'      => [
                            'ip' => bin2hex(inet_pton($_SERVER['REMOTE_ADDR'])),
                        ] + $f,
                    ])->save();
                } catch (\Lemmon\Model\ValidationException $e) {
                    $this->flash->setError('Error sending form');
                }
                mail('publisher@lemmonjuice.com', $this->page->name, json_encode($f, JSON_PRETTY_PRINT));
                $this->flash->setNotice('Form has been sent successfully');
            } else {
                // throw error
                $this->flash->setError('Some funky error');
            }
            
        }, [], function(){
            
            //
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
