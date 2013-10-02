<?php
/**
* 
*/
class Forms_Controller extends AbstractFrontend_Controller
{
    protected $form;


    function __initModule()
    {
        $this->form = new Form($this->page);
    }


    function index()
    {
        $this->data['form'] = $this->form;
    }


    function submit()
    {
        //
        // no cache
        $this->noCache();
        //
        // process request
        return $this->_res(function(){

            //
            // process form
            if ($f = $this->sanitize($_POST)) {
                if ($this->_sanitize($f) !== false and $this->_validate($f) !== false) {
                    try {
                        (new FormsResponse)->set([
                            'name'      => $this->page->name,
                            'page_id'   => $this->page->id,
                            'locale_id' => $this->page->locale_id,
                            'state_id'  => null,
                            'data'      => [
                                'ip' => bin2hex(inet_pton($_SERVER['REMOTE_ADDR'])),
                            ]
                                + $f,
                        ])->save();
                    } catch (\Lemmon\Model\ValidationException $e) {
                        $this->flash->setError('Error submitting form');
                    }
                    mail('publisher@lemmonjuice.com', $this->page->name, json_encode($f, JSON_PRETTY_PRINT));
                    $this->flash->setNotice('Form has been sent successfully');
                    return $this->page->getUrl();
                } else {
                    // throw error
                    $this->flash->setError('Your input contains errors');
                    $this->flash->setError('Form has NOT been sent');
                }
            }
            
        }, [], function(){
            
            //
            // empty (default) response
            http_response_code(204);
            exit;
            
        });
    }


    private function _sanitize(&$f)
    {
        
    }


    private function _validate(&$f)
    {
        $ok = true;
        //
        // validate
        foreach ($this->form->getFields() as $id => $field) {
            $value = $f[$id];
            // required
            if ($case = $field['weight'] and $case == 'required' and !$value) {
                $this->flash->setErrorField($id, 'This field is required', $case);
                $ok = false;
            }
            // validate
            if ($value and $case = $field['validate']) {
                switch ($case) {
                    case 'email':
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $this->flash->setErrorField($id, 'This is NOT a valid email', 'validate-' . $case);
                            $ok = false;
                        }
                        break;
                }
            }
        }
        //
        // res
        return $ok;
    }
}
