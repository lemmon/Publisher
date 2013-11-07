<?php
/**
* 
*/
class Banner extends AbstractModuleRow
{
    static protected $model = 'Banners';


    function getImage($dim = null)
    {
        return $this->getRoute()->getUpload($this->get('image'), $dim);
    }


    protected function __validate($f)
    {
        // image is required
        if (!$this->get('image') and $_FILES['data']['error']['image'] == UPLOAD_ERR_NO_FILE) {
            $this->setError('data/image', _t('This field is required'));
        }
    }
}
