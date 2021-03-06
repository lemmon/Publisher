<?php
/**
* 
*/
class Uploads_Controller extends Application
{


    private function _getDim()
    {
        preg_match('/(?<width>\d*)x(?<height>\d*)(?<flags>\w*)/', $this->route->dim, $dim);
        return [$dim['width'], $dim['height'], $dim['flags']];
    }


    function image()
    {
        //
        // dim
        list($width, $height, $flags) = $this->_getDim();
        //
        // paths
        $image_source = Lemmon\Model\Schema::getDefaultUploadDir() . '/' . $this->route->image;
        $image_cached = BASE_DIR . '/cache/user/' . $this->site->getLink() . '/' . $this->route->path;
        //
        // source exists
        if (is_file($image_source)) {
            //
            // cache dir
            if (!($_dir = dirname($image_cached)) or (!is_dir($_dir) and !mkdir($_dir, 0777, true)) or !is_writable($_dir))
                throw new Exception('Error creating cache directory.');
            //
            // image
            $image = new Zebra_Image;
            $image->source_path = $image_source;
            $image->target_path = $image_cached;
            $crop_style = ZEBRA_IMAGE_CROP_CENTER;
            //
            // flags
            if ($flags{0} == 'm') {
                // provided dimension is maximal
                $image->enlarge_smaller_images = false;
                $crop_style = ZEBRA_IMAGE_NOT_BOXED;
            }
            elseif ($flags{0} == 'b') {
                // create box and fit image in
                $crop_style = ZEBRA_IMAGE_BOXED;
            }
            elseif ($flags{0} == 'c') {
                // crop
                switch ($flags{1}) {
                    case '1': $crop_style = ZEBRA_IMAGE_CROP_TOPLEFT; break;
                    case '2': $crop_style = ZEBRA_IMAGE_CROP_TOPCENTER; break;
                    case '3': $crop_style = ZEBRA_IMAGE_CROP_TOPRIGHT; break;
                    case '4': $crop_style = ZEBRA_IMAGE_CROP_MIDDLELEFT; break;
                    case '5': $crop_style = ZEBRA_IMAGE_CROP_CENTER; break;
                    case '6': $crop_style = ZEBRA_IMAGE_CROP_MIDDLERIGHT; break;
                    case '7': $crop_style = ZEBRA_IMAGE_CROP_BOTTOMLEFT; break;
                    case '8': $crop_style = ZEBRA_IMAGE_CROP_BOTTOMCENTER; break;
                    case '9': $crop_style = ZEBRA_IMAGE_CROP_BOTTOMRIGHT; break;
                }
            }
            //
            // resize & save
            $image->resize((int)$width, (int)$height, $crop_style);
            //
            // flush image
            $this->_flushImage($image_cached);
        }
        //
        // source not found
        else {
            return $this->_notFound($width, $height);
        }
        //
        //
        return false;
    }


    function placeholder()
    {
        list($width, $height) = $this->_getDim();
        return $this->_notFound($width, $height);
    }


    private function _notFound($width, $height)
    {
        //
        // dim
        $this->data += [
            'width'  => current(array_filter([$width, $height, 150])),
            'height' => current(array_filter([$height, $width, 150])),
        ];
        header('Content-type: image/svg+xml');
        header('X-Robots-Tag: noindex', true);
        return Lemmon\Template::display('image_not_found', $this->data); // legacy solution
        #return $this->template->display('image_not_found');
    }


    private function _flushImage($image)
    {
        $image_type = getimagesize($image);
        header('Content-Type: ' . $image_type['mime']);
        echo file_get_contents($image);
        exit;
    }
}
