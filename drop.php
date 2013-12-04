<?php

	// configuration
    require("functions.php");
    
    set_time_limit(100);
	
	// modified from http://www.w3schools.com/php/php_file_upload.asp
	$allowedExts = array("gif", "jpeg", "jpg", "png");
	$temp = explode(".", $_FILES["file"]["name"]);
	$extension = end($temp);
	if ((($_FILES["file"]["type"] == "image/gif")
		|| ($_FILES["file"]["type"] == "image/jpeg")
		|| ($_FILES["file"]["type"] == "image/jpg")
		|| ($_FILES["file"]["type"] == "image/pjpeg")
		|| ($_FILES["file"]["type"] == "image/x-png")
		|| ($_FILES["file"]["type"] == "image/png"))
		&& ($_FILES["file"]["size"] < 5242880) // in bytes (5MB max)
		&& in_array($extension, $allowedExts))
  	{
  		if ($_FILES["file"]["error"] > 0)
    	{
    		echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
    		//echo false;
    	}
  		else
    	{
    		//echo "Upload: " . $_FILES["file"]["name"] . "<br>";
    		//echo "Type: " . $_FILES["file"]["type"] . "<br>";
    		//echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
    		//echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

    		if (file_exists("upload/" . $_FILES["file"]["name"]))
      		{
      			//echo $_FILES["file"]["name"] . " already exists. ";
      		}
    		else
      		{
      			move_uploaded_file($_FILES["file"]["tmp_name"],
      			"upload/" . $_FILES["file"]["name"]);
      			//echo "Stored in: " . "upload/" . $_FILES["file"]["name"];
      		}
      		
      		// resizing modified from http://us2.php.net/imagecopyresampled
      		
      		// store path to file
      		$img_path = "upload/" . $_FILES["file"]["name"];
      		
      		// set max height and width
      		$width = 200;
      		$height = 200;
      		
      		// content type
      		header('Content-Type: image/jpg');
      		
      		// get new dimensions
      		list($width_orig, $height_orig) = getimagesize($img_path);

			$ratio_orig = $width_orig / $height_orig;

			if ($width / $height > $ratio_orig) {
   				$width = round($height * $ratio_orig);
			} else {
   				$height = round($width / $ratio_orig);
			}
			
			
			
			// resample
			$image_p = imagecreatetruecolor($width, $height);
			$image = imagecreatefromjpeg($img_path);
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
			
			// output
			imagejpeg($image_p, "resized/" . $_FILES["file"]["name"], 100);
      		
      		// store image dimensions
  			//$size = getimagesize($img_path);
  			//$width = $size[0];
  			//$height = $size[1];
  			
  			
  			// add if statements so correct function is used depending on file type
  			// new image
  			$img = imagecreatefromjpeg("resized/" . $_FILES["file"]["name"]);
  			// new width and height
  			
  			
  			// set up color storage arrays - maybe use only one array somehow?
  			$color_counts = array();
  			$palette = array();
  			
  			//dump($width);
  			
  			get_palette($img, $width, $height, $color_counts, $palette);
  			
  			/*
  			// loop through each stored color
  			foreach ($color_counts as $key => $value)
  			{
  				// add first color to palette array
  				if (empty($palette))
  				{
  					$palette[] = $key;
  				}
  				else
  				{
  					// expand color's rgb vals
  					$rgb = explode(",", $key);
  					$r = $rgb[0];
  					$g = $rgb[1];
  					$b = $rgb[2];
  					$rgb_key = $r . "," . $g . "," . $b;
  					
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
  						// factor out magic number
  						if ($r_diff < 70 && $g_diff < 70 && $b_diff < 70)
  						{
  							// new color is too similar
  							$unique = false;
  						}
  					}
  					
  					// if new color is unique enough, add to palette
  					if ($unique)
  					{
  						$palette[] = $rgb_key;
  					}
  				}
  			}
  			*/
  			
  			// display the palette
  			//render("palette.php", ["colors" => $palette, "img_path" => $img_path]);
  			$json = json_encode($palette);
  			echo $json;
    	}
  	}
	else
  	{
  		echo "Invalid file";
  	}

?>