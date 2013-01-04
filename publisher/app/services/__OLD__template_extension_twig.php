<?php
/**
* 
*/
class TemplateExtensionTwig extends Twig_Extension
{


	function getFilters()
	{
		return [
			
			// I18n
			't'            => new Twig_Filter_Function('Lemmon_I18n::t'),
			'tn'           => new Twig_Filter_Function('Lemmon_I18n::tn'),
			'tDate'        => new Twig_Filter_Function('Lemmon_I18n::date'),
			'tDateTime'    => new Twig_Filter_Function('Lemmon_I18n::datetime'),
			'tPrice'       => new Twig_Filter_Function('Lemmon_I18n::price', ['pre_escape' => 'html', 'is_safe' => ['html']]),
			'tPriceInt'    => new Twig_Filter_Function('Lemmon_I18n::priceInt', ['pre_escape' => 'html', 'is_safe' => ['html']]),
			'tTime'        => new Twig_Filter_Function('Lemmon_I18n::time'),
			
			// Numbers
			'round'        => new Twig_Filter_Function('round'),
			
			// Strings
			'asciize'      => new Twig_Filter_Function('Lemmon\String::asciize'),
			'html'         => new Twig_Filter_Function('Lemmon\String::html', ['is_safe' => ['html']]),
			'human'        => new Twig_Filter_Function('Lemmon\String::human'),
			'line'         => new Twig_Filter_Function('Lemmon\String::line'),
			'p'            => new Twig_Filter_Function('Lemmon\String::paragraph'),
			'text_to_html' => new Twig_Filter_Function('Lemmon\String::text', ['pre_escape' => 'html', 'is_safe' => ['html']]),
			'html_to_text' => new Twig_Filter_Function('Lemmon\String::html2text'),
			
			// Debug
			'dump'         => new Twig_Filter_Function('TemplateExtensionTwig::dump', ['is_safe' => ['html']]),
			
		];
	}


	function getName()
	{
		return 'project_sandboxed';
	}


	function dump($data)
	{
		ob_start();
		dump($data);
		$res = ob_get_contents();
		ob_clean();
		return $res;
	}
}
