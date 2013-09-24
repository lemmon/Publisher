<?php

use \Symfony\Component\Yaml\Yaml;

/**
* 
*/
class Form
{
    private $_form;


    function __construct($page)
    {
        // load form
        $form = Yaml::parse($page->getBlock('form/structure'));
        // fields
        if ($form['fields'] and is_array($form['fields'])) {
            // system
            $field_default = $form['fields']['_default'];
            // fields
            foreach ($form['fields'] as $name => $field) {
                // system
                if ($name{0} == '_') {
                    unset($form['fields'][$name]);
                    continue;
                } else {
                    $field = array_merge((array)$field_default, (array)$field);
                }
                // label
                if (!$field['label']) $field['label'] = \Lemmon\String::human($name);
                // dynamic
                foreach ($field as $_key => $_val) {
                    if ($_val{0} == '@')
                        $field[$_key] = $field[substr($_val, 1)];
                }
                // name
                $field['name'] = $name;
                //
                $form['fields'][$name] = $field;
            }
        }
        //
        return $this->_form = $form;
    }


    function getFields()
    {
        return $this->_form['fields'];
    }
}
