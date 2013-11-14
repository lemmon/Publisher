<?php

use \Lemmon\String;

/**
* 
*/
class AbstractRow extends \Lemmon\Model\AbstractRow
{
    private static $_route;


    static function setRoute($route)
    {
        self::$_route = $route;
    }


    final protected function getRoute()
    {
        return self::$_route;
    }


    final function get($name)
    {
        return $this->getBlock($name);
    }


    final protected function _validateUploads($files = null)
    {
        if ($files or $files = $_FILES) {
            // files
            foreach ($files as $handle => $file) {
                if ($file['error'] != UPLOAD_ERR_OK and $file['error'] != UPLOAD_ERR_NO_FILE) {
                    $this->setError('data/image', _t('Error uploading file'), 'upload');
                }
            }
            // upload directory
            $dir_base = \Lemmon\Model\Schema::getDefaultUploadDir();
            $dir_file = String::classToTableName(self::getModelName(), '-') . '/' . strftime('%Y-%m');
            if ((!is_dir($dir_base . '/' . $dir_file) and !@mkdir($dir_base . '/' . $dir_file, 0777, true)) or !is_writable($dir_base . '/' . $dir_file)) {
                foreach (array_keys($files) as $handle)
                    $this->setError($handle, _t('Upload directory not writable'), 'upload/dir');
            }
        }
    }


    final protected function _saveUploads(&$data, $files = null)
    {
        if ($files or $files = $_FILES) {
            // base dir
            $dir_base = \Lemmon\Model\Schema::getDefaultUploadDir();
            $dir_file = String::classToTableName(self::getModelName(), '-') . '/' . strftime('%Y-%m');
            // upload files
            foreach ($files as $handle => $file) {
                if ($file['error'] == UPLOAD_ERR_OK) {
                    // remove old file
                    if ($_file = $this->get($handle)) {
                        @unlink($dir_base . '/' . $_file);
                        $data[$handle] = null;
                    }
                    // upload file
                    $_file = [
                        'base' => substr($file['name'], 0, strrpos($file['name'], '.')),
                        'ext'  => substr($file['name'], strrpos($file['name'], '.') + 1),
                    ];
                    $file_name = String::asciize($_file['base']) . '.' . time() . '.' . strtolower($_file['ext']);
                    if (@move_uploaded_file($file['tmp_name'], $dir_base . '/' . $dir_file . '/' . $file_name)) {
                        $data[$handle] = $dir_file . '/' . $file_name;
                    }
                } elseif ($file['error'] == UPLOAD_ERR_NO_FILE) {
                    $data[$handle] = $this->get($handle);
                }
            }
        }
    }
}
