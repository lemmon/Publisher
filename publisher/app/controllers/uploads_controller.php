<?php
/**
* 
*/
class Uploads_Controller extends Application
{


	function image()
	{
		//
		// parse dim
		preg_match('/(?<width>\d*)x(?<height>\d*)(?<flags>[a-z]*)/', $this->route->dim, $dim);
		$width  = $dim['width'];
		$height = $dim['height'];
		if ($flags = $dim['flags'] and $flags = str_split($flags) or $flags = []);
			$flags = array_combine($flags, $flags);
		//
		// paths
		$image_source = Lemmon\Model\Schema::getDefaultUploadDir() . $this->route->image;
		$image_cached = BASE_DIR . '/cache' . $this->route->getSelf();
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
		if ($flags['m'])
		{
			// provided dimension is maximal
			$image->enlarge_smaller_images = false;
			$crop_style = ZEBRA_IMAGE_NOT_BOXED;
		}
		if ($flags['b'])
		{
			// create box and fit image in
			$crop_style = ZEBRA_IMAGE_BOXED;
		}
		//
		// resize & save
		$image->resize((int)$width, (int)$height, $crop_style);
		//
		// flush image
		$this->_flushImage($image_cached);
	}


	private function _flushImage($image)
	{
		$image_type = getimagesize($image);
		header('Content-Type: ' . $image_type['mime']);
		echo file_get_contents($image);
		exit;
	}
}
