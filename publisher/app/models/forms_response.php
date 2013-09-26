<?php

use \Lemmon\Sql\Query as SqlQuery;

/**
* 
*/
class FormsResponse extends AbstractModuleRow
{
    static protected $model = 'FormsResponses';


    /*
    private $_data;


    function setForm($form)
    {
        $this->page_id = $form;
        return $this;
    }


    function setData($data)
    {
        $this->_data = $data;
        return $this;
    }


    function getResponse()
    {
        return $this->_data ?: $this->_data = (new SqlQuery)->select('forms_responses_data')->where(['response_id' => $this->id])->pairs('field', 'value');
    }


    protected function __validate(&$f)
    {
        //
        // ip
        #$f['ip'] = inet_pton($_SERVER['REMOTE_ADDR']);
    }


    /*
    protected function __create()
    {
        foreach ($this->_data as $field => $value) {
            if ($value = trim($value)) {
                (new SqlQuery)->insert('forms_responses_data')->set([
                    'response_id' => $this->id,
                    'field'       => $field,
                    'value'       => $value,
                ])->exec();
            }
        }
    }
    */
}