<?php
/**
* 
*/
class TemplateExtensionUser extends \Lemmon\Template\ExtensionTwig
{


    function getFilters()
    {
        return [
        
            'pp'        => new \Twig_Filter_Function('TemplateExtensionUser::pp'/*, ['is_safe' => ['html']]*/),
            'noImages'  => new \Twig_Filter_Function('TemplateExtensionUser::noImages'/*, ['is_safe' => ['html']]*/),
            'emailHide' => new \Twig_Filter_Function('TemplateExtensionUser::emailHide'/*, ['is_safe' => ['html']]*/),
        
        ] + parent::getFilters();
    }


    function pp($content, $len = 1)
    {
        preg_match_all('#<(\w+)[^>]*>(.*)</\1>#isU', $content, $m);
        return join(array_slice($m[0], 0, $len));
    }


    function noImages($content)
    {
        return preg_replace('#\s*<div[^>]*class="[^"]*image[^"]*"[^>]*>(.*)</div>\s*#isU', '', $content);
    }


    function emailHide($email)
    {
        $email = str_replace(['@', '.'], ['$' . chr(rand(97, 122)), '+'], $email);
        $email = str_rot13($email);
        return $email;
    }
}
