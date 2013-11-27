<?php

	// configuration
    require("../includes/config.php");
	
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
		&& ($_FILES["file"]["size"] < 500000)
		&& in_array($extension, $allowedExts))
  	{
  		if ($_FILES["file"]["error"] > 0)
    	{
    		echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
    	}
  		else
    	{
    		echo "Upload: " . $_FILES["file"]["name"] . "<br>";
    		echo "Type: " . $_FILES["file"]["type"] . "<br>";
    		echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
    		echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

    		if (file_exists("upload/" . $_FILES["file"]["name"]))
      		{
      			echo $_FILES["file"]["name"] . " already exists. ";
      		}
    		else
      		{
      			move_uploaded_file($_FILES["file"]["tmp_name"],
      			"upload/" . $_FILES["file"]["name"]);
      			echo "Stored in: " . "upload/" . $_FILES["file"]["name"];
      		}
      		
      		// store path to file
      		$img_path = "upload/" . $_FILES["file"]["name"];
      		
      		// store image dimensions
  			$size = getimagesize($img_path);
  			$width = $size[0];
  			$height = $size[1];
  			
  			// add if statements so correct function is used depending on file type
  			$img = imagecreatefromjpeg($img_path);
  			
  			// set up color storage arrays - maybe use only one array somehow?
  			$color_counts = array();
  			$palette = array();
  			
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
  					
  					// store occurances of pixel color
  					if (empty($color_counts[$rgb_key]))
  					{
  						$color_counts[$rgb_key] = 1;
  					}
  					// don't even need this unless decide want actual counts
  					else
  					{
  						$color_counts[$rgb_key] = $color_counts[$rgb_key] + 1;
  					}
  				}
  			}
  			
  			/*
  			$place = 0;
  			$cc_length = count($color_counts);
  			// for each color in array
  			for ($k = 0; $k < $cc_length; $k++)
  			{
  				// after first color
  				if ($place > 0)
  				{
  					// compare to previous kept colors
  					
  				}
  			}
  			*/
  			
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
  			
  			// display the palette
  			render("palette.php", ["colors" => $palette, "img_path" => $img_path]);
    	}
  	}
	else
  	{
  		echo "Invalid file";
  	}

?>