<?php
/**
* 
*/
class Admin_Forms_Controller extends Admin_AbstractModule_Controller
{


    protected function __init()
    {
        $this->route->register('admin/forms/response', '@/forms/response/$id');
    }


    protected function getConfig()
    {
        return [
            
            'model' => 'FormsResponses',

        ] + parent::getConfig();
    }


    function structure()
    {
        // page
        $page = $this->getPage($this->route->id);
        // form
        return $this->_res(function() use ($page){
        
            // on POST
            if ($f = $_POST) {
                $page->setBlocks([
                    'form/structure' => $f['structure'],
                ])->save();
                $this->flash->setNotice('Form structure has been updated');
                return $this->route->getSection($page);
            }
            // load values
            else {
                $this->data['f'] += [
                    'structure' => $page->getBlock('form/structure'),
                ];
            }
        
        });
    }


    function response()
    {
        if ($id = $this->route->id and $form_response = FormsResponse::find($id) and $page = $this->getPage($form_response->page_id)) {
            // load data
            $this->data += [
                'structure' => $page->getBlock('form/structure'),
                'response'  => $form_response,
            ];
        } else {
            // n/a
            die('--error');
            return $this->notFound();
        }
    }
}
