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
      		
      		// store image dimensions
  			$size = getimagesize("upload/" . $_FILES["file"]["name"]);
  			$width = $size[0];
  			$height = $size[1];
  			
  			// add if statements so correct function is used depending on file type
  			$im = imagecreatefromjpeg("upload/" . $_FILES["file"]["name"]);
  			
  			$colors = array();
  			$color_counts = array();
  			$top_colors = array();
  			$pixel_count = 0;
  			$repeats = 0;
  			
  			// loop through pixels
  			for ($i = 0; $i < $width; $i++)
  			{
  				for ($j = 0; $j < $height; $j++)
  				{
  					$rgb = imagecolorat($im, $i, $j);
  					$icfi = imagecolorsforindex($im, $rgb);
  					$r = $icfi["red"];
  					$g = $icfi["green"];
  					$b = $icfi["blue"];
  					$pixel_count++;
  					
  					// add color to array using its rgb string as a key to avoid duplicates
  					$colors[$r . $g . $b] = $icfi;
  							
  					// count occurance of each color
  					if (empty($color_counts[$r . "," . $g . "," . $b]))
  					{
  						$color_counts[$r . "," . $g . "," . $b] = 1;
  					}
  					else
  					{
  						$color_counts[$r . "," . $g . "," . $b] = $color_counts[$r . "," . $g . "," . $b] + 1;
  					}
  							
  				}
  			}
  			
  			// this works
  			/*
  			foreach ($color_counts as $key => $value)
  			{
  				$val = $value;
  				if ($val > 50)
  				{
  					//dump($val);
  					$top_colors[] = $key;
  				}
  				//dump($val);
  			}
  			*/
  			
  			//dump($color_counts);
  			
  			$test_count = 0;
  			
  			foreach ($color_counts as $key => $value)
  			{
  				$test_count++;
  				if (empty($top_colors))
  				{
  					$top_colors[] = $key;
  				}
  				else
  				{
  					$rgb = explode(",", $key);
  					$r = $rgb[0];
  					$g = $rgb[1];
  					$b = $rgb[2];
  					
  					$unique = true;
  					
  					foreach ($top_colors as $key2 => $value2)
  					{
  						//dump($value2);
  						$rgb_t = explode(",", $value2);
  						//dump($rgb_t);
  						$r_diff = abs($r - $rgb_t[0]);
  						//dump($r_diff);
  						$g_diff = abs($g - $rgb_t[1]);
  						$b_diff = abs($b - $rgb_t[2]);  
  						//dump($b_diff);
  						
  						if ($r_diff < 70 && $g_diff < 70 && $b_diff < 70)
  						{
  							//dump($r_diff);
  							$unique = false;
  						}
  					}
  					
  					if ($unique)
  					{
  						$top_colors[] = $r . "," . $g . "," . $b;
  						//break;
  					}
  				}
  			}
  			
  			$img = "upload/" . $_FILES["file"]["name"];
  			//dump($top_colors);
  			render("palette.php", ["colors" => $top_colors, "img" => $img]);
    	}
  	}
	else
  	{
  		echo "Invalid file";
  	}

?>