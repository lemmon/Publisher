<?php
/**
* 
*/
class Services_Templates_Controller extends Application
{


	function css()
	{
		// locate source
		$file = BASE_DIR . dirname($this->route->getSelf()) . '/src/' . substr(basename($this->route->getSelf()), 0, -4) . '.less';
		
		//
		if (file_exists($file))
		{
			// less css
			require_once LIBS_DIR . '/lessphp/lessc.inc.php';
			$less = new lessc($file);
			
			// parse
			$code = $less->parse();
			
			// return
			header('Content-type: text/css; charset=utf-8');
			echo $code;
		}
		
		//
		return false;
	}
}
