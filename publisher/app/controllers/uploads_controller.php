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
		preg_match('/(?<width>\d*)x(?<height>\d*)(?<flags>\w*)/', $this->route->dim, $dim);
		$width  = $dim['width'];
		$height = $dim['height'];
		$flags  = $dim['flags'];
		#if ($flags = $dim['flags'] and $flags = str_split($flags) or $flags = []);
		#	$flags = array_combine($flags, $flags);
		//
		// paths
		$image_source = Lemmon\Model\Schema::getDefaultUploadDir() . '/' . $this->route->image;
		$image_cached = BASE_DIR . '/cache' . $this->route->getSelf();
		//
		// source exists
		if (is_file($image_source))
		{
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
			if ($flags{0} == 'm')
			{
				// provided dimension is maximal
				$image->enlarge_smaller_images = false;
				$crop_style = ZEBRA_IMAGE_NOT_BOXED;
			}
			elseif ($flags{0} == 'b')
			{
				// create box and fit image in
				$crop_style = ZEBRA_IMAGE_BOXED;
			}
			elseif ($flags{0} == 'c')
			{
				// crop
				switch ($flags{1})
				{
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
		else
		{
			//
			// dim
			$w = current(array_filter([$width, $height, 150]));
			$h = current(array_filter([$height, $width, 150]));
			preg_match('/(\d*)x(\d*)/', $this->route->dim, $m);
			//
			// create empty image
			$image = ImageCreateTrueColor($w, $h);
			$bg_color = ImageColorAllocate($image, 200, 200, 200);
			ImageFill($image, 0, 0, $bg_color);
			//
			// placeholder
			if ($w >= 50 and $h >= 50)
			{
				$placeholder = ImageCreateFromPNG(ROOT_DIR . '/public/img/image-placeholder.png');
				if ($w >= 80 and $h >= 80)
					$placeholder_w = $placeholder_h = 75;
				else
					$placeholder_w = $placeholder_h = 45;
				$placeholder_w_orig = ImagesX($placeholder);
				$placeholder_h_orig = ImagesY($placeholder);
				ImageCopyResized($image, $placeholder, round(($w - $placeholder_w) / 2), round(($h - $placeholder_h) / 2), 0, 0, $placeholder_w, $placeholder_h, $placeholder_w_orig, $placeholder_h_orig);
			}
			//
			// flush image
			header('X-Robots-Tag: noindex', true);
			header('Content-type: image/png');
			ImagePNG($image);
			ImageDestroy($image);
		}
		//
		//
		return false;
	}


	private function _flushImage($image)
	{
		$image_type = getimagesize($image);
		header('Content-Type: ' . $image_type['mime']);
		echo file_get_contents($image);
		exit;
	}
}
