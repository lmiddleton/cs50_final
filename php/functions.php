<?php

// display errors, warnings, and notices
ini_set("display_errors", true);
error_reporting(E_ALL);

/* from CS-50 problem set 7 */
/**
 * Facilitates debugging by dumping contents of variable
 * to browser.
 */
function dump($variable)
{
	require("../dump.php");
    exit;
}
    
/* builds the color palette for the given image */
function get_palette($img, $width, $height, &$palette)
{
	// set color uniqueness factor
	$factor = 70;
	
	// loop through each col of pixels
  	for ($i = 0; $i < $width; $i++)
  	{
  		// loop through each row of pixels
  		for ($j = 0; $j < $height; $j++)
  		{
  			// store rgb vals of pixel
  			$rgb = imagecolorat($img, $i, $j);
  			$icfi = imagecolorsforindex($img, $rgb);
  			$r = $icfi["red"];
  			$g = $icfi["green"];
  			$b = $icfi["blue"];
  			$rgb_key = $r . "," . $g . "," . $b;
  					
  			if (empty($palette))
  			{
  				// add first color
  				$palette[] = $rgb_key;
  			}
  					
  			else
  			{
  				// compare new color to each in palette
  				// start by assuming color is unique
  				$unique = true;
  					
  				// loop through colors already in palette to compare new color
  				foreach ($palette as $key2 => $value2)
  				{
  					// calculate difference between new color and palette color
  					$rgb_t = explode(",", $value2);
  					$r_diff = abs($r - $rgb_t[0]);
  					$g_diff = abs($g - $rgb_t[1]);
  					$b_diff = abs($b - $rgb_t[2]);  
  						
  					// determine if new color is too similar to palette color
  					if ($r_diff < $factor && $g_diff < $factor && $b_diff < $factor)
  					{
  						// new color is too similar
  						$unique = false;
  						break;
  					}
  				}
  					
  				// if new color is unique enough, add to palette
  				if ($unique)
  				{
  					$palette[] = $rgb_key;
  				}
  			}
  		}
  	}
}

/* resizes an images and outputs it */
/* modified from http://us2.php.net/imagecopyresampled */
function resize(&$width, &$height, $img_path, $filename)
{
	// set content type
    header('Content-Type: image/png');
      		
    // get current dimensions
    list($width_orig, $height_orig) = getimagesize($img_path);
	$ratio_orig = $width_orig / $height_orig;
			
	// set new dimensions
	if ($width / $height > $ratio_orig) {
   		$width = round($height * $ratio_orig);
	} else {
   		$height = round($width / $ratio_orig);
	}
			
	// resample
	$image_p = imagecreatetruecolor($width, $height);
	$image = imagecreatefrompng($img_path);
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
			
	// output to resized folder
	imagepng($image_p, "../resized/" . $filename, 0);
}

?>
