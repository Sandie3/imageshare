<?php
	/* imagefilter.inc.php
	 * Class for filtering / compressing images.
	 * Crop, jpeg compress, thumbnail generation. */

	class ImageFilter{
		var $imagick_data;
		var $valid;
		
		function __construct($input){
			$this->imgdata = new Imagick($input);
			$this->check_valid();
		}

		function __destruct(){
			unset($imagick_data);
			unset($valid);
		}

		// Write current image data to a destination on disk
		// Example : writeout("/database/icons/username.png");
		function writeout($path){
			$this->imgdata->writeImage($path);
		}
		
		// Convert the image to a compressed 350x350 thumbnail JPG
		function compress_thumbnail(){
			// Check for invalid image
			if(!$this->check_valid()){
				$_SESSION['errormsg'] = "Error: Imagick invalid image.";
				return -1;
			}

			// Optimize the image layers
			//$this->imgdata->optimizeImageLayers();
			
			// Compress & crop
			$this->jpegify(90);
			$this->crop_ctr(350, 350);

			// Check for invalid image (2nd pass)
			if(!$this->check_valid()){
				$_SESSION['errormsg'] = "Error: Imagick invalid image.";
				return -1;
			}

			return 0;
		}
		
		// Convert the image into a JPG, set quality.
		function jpegify ($quality = 90){
			$this->imgdata->setImageCompression(Imagick::COMPRESSION_JPEG);
			$this->imgdata->setImageCompressionQuality($quality);
		}
		
		// Crop and Imagick image, similar to a 'cover' sizing in html.
		// w and h are the resulting dimensions.
		function crop_ctr($w, $h){
			$geo = $this->imgdata->getImageGeometry();

			// algorithm
			if(($geo['width'] / $w) < ($geo['height'] / $h)){
				$this->imgdata->cropImage($geo['width'], floor($h*$geo['width']/$w), 0, (($geo['height'] - ($h * $geo['width'] / $w)) / 2));
			}else{
				$this->imgdata->cropImage(ceil($w * $geo['height'] / $h), $geo['height'], (($geo['width'] - ($w * $geo['height'] / $h)) / 2), 0);
			}

			unset($geo);
			
			// Generate thumbnail
			$this->imgdata->thumbnailImage($w, $h, true);
		}

		// Update valid status and return valid value
		function check_valid(){
			$this->valid = $this->imgdata->valid();
			return $this->valid;
		}
	}
?>
