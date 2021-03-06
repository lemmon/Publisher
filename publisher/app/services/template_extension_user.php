<?php
/**
* 
*/
class TemplateExtensionUser extends \Lemmon\Template\ExtensionTwig
{
    protected $i18n;


    function __construct(\Lemmon\I18n\I18n $i18n = null)
    {
        $this->i18n = $i18n;
    }


    function getFilters()
    {
        return array_merge(
        
            parent::getFilters(), [
        
            't'         => new \Twig_Filter_Function([$this, 't']),
            'pp'        => new \Twig_Filter_Function('TemplateExtensionUser::pp'/*, ['is_safe' => ['html']]*/),
            'noImages'  => new \Twig_Filter_Function('TemplateExtensionUser::noImages'/*, ['is_safe' => ['html']]*/),
            'emailHide' => new \Twig_Filter_Function('TemplateExtensionUser::emailHide'/*, ['is_safe' => ['html']]*/),

            'urlLink' => new \Twig_Filter_Function(function($a) {
                return preg_replace('#^\w+://#', '', $a);
            }),
            'urlHref' => new \Twig_Filter_Function(function($a) {
                if (!preg_match('#^\w+://#', $a)) {
                    $a = 'http://' . $a;
                }
                return $a;
            }),

            'tDate'    => new \Twig_Filter_Function([$this, 'tDate']),

            /*
            new \Twig_SimpleFilter('t', function(){
                return $this->i18n->t();
            }),
            */
        
        ]);
    }


    function t($foo)
    {
        return call_user_func_array('_t', func_get_args());
    }


    function tDate($t, $format = '%e %b %Y')
    {
        $ts = strtotime($t);
        $format = str_replace([
            '%e.'
        ], [
            date('jS', $ts),
        ], $format);
        return strftime($format, $ts);
    }


    static function pp($content, $len = 1)
    {
        preg_match_all('#<(\w+)[^>]*>(.*)</\1>#isU', $content, $m);
        return join(array_slice($m[0], 0, $len));
    }


    static function noImages($content)
    {
        return preg_replace('#\s*<div[^>]*class="[^"]*image[^"]*"[^>]*>(.*)</div>\s*#isU', '', $content);
    }


    static function emailHide($email)
    {
        $email = str_replace(['@', '.'], ['$' . chr(rand(97, 122)), '+'], $email);
        $email = str_rot13($email);
        return $email;
    }
}
