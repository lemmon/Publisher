<?php

use \Lemmon\String,
    \Cake\Utility\Inflector;

/**
* 
*/
class Medium extends \Lemmon\Model\AbstractRow
{
	static protected $model = 'Media';


	protected function onValidate(&$f)
	{
		// name
		if (empty($f['name']))
		{
			$f['name'] = String::human(preg_replace('@\.[^\.]+$@', '', basename($f['file'])));
		}
	}


	protected function upload($field, $file, &$f)
	{
		// mime type
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mime = finfo_file($finfo, $file);
		finfo_close($finfo);
		preg_match('#^(\w+)/(\w+)#', $mime, $m);
		$f['file_mime_type'] = $m[1];
		$f['file_mime_subtype'] = $m[2];
	}
}
