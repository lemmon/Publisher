<?php
/**
* 
*/
class TemplateExtensionAdmin extends \Lemmon\Template\ExtensionTwig
{
    protected $i18n;


    function __construct(\Lemmon\I18n\I18n $i18n = null)
    {
        $this->i18n = $i18n;
    }


    function getFilters()
    {
        return [
        
            't'        => new \Twig_Filter_Function([$this, 't']),
            'resizeTo' => new \Twig_Filter_Function('TemplateExtensionAdmin::resizeTo', ['is_safe' => ['html']]),
            'toTs'     => new \Twig_Filter_Function('strtotime'),
        
        ] + parent::getFilters();
    }


    function t($foo)
    {
        return call_user_func_array([$this->i18n, 't'], func_get_args());
    }


    function resizeTo($html, $dim)
    {
        return preg_replace('#uploads(/0\d*x\d*[a-z]*)#i', 'uploads/0' . $dim, $html);
    }
}
