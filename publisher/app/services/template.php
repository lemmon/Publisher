<?php
/**
* 
*/
class Template
{


	static function getFilesystem()
	{
		// init
		$res = [];
		// system
		$res[] = BASE_DIR . '/user/template/';
		//
		return $res;
	}


	static function display($file_base, array $data = [], $include_data = false)
	{
		// data
		if ($include_data)
		{
			$data = array_merge(Application::getInstance()->getData(), $data);
		}
		
		// common
		$data['flash'] = $_SESSION['__FLASH__'];
		
		// template environment
		$twig_env = \Lemmon\Template::getEnvironment();
		
		// filename
		$template_file = $file_base;
		if (strpos($file_base, '.')===false) $template_file .= '.html';
		
		// tempalte
		$template_loader = new Twig_Loader_Filesystem(self::getFilesystem());
		$template_environment = new Twig_Environment($template_loader, $twig_env);
		$template_environment->addExtension(new TemplateExtensionTwig());
		$template = $template_environment->loadTemplate($template_file);
		
 		return $template->render($data);
	}
}
