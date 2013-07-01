<?php
/**
* 
*/
class TemplateExtensionAdmin extends \Lemmon\Template\ExtensionTwig
{


    function getFilters()
    {
        return [
        
            'resizeTo' => new \Twig_Filter_Function('TemplateExtensionAdmin::resizeTo', ['is_safe' => ['html']]),
            'toTs'     => new \Twig_Filter_Function('strtotime'),
        
        ] + parent::getFilters();
    }


    function resizeTo($html, $dim)
    {
        return preg_replace('#uploads(/0\d*x\d*[a-z]*)#i', 'uploads/0' . $dim, $html);
    }
}
