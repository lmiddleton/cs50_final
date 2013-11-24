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
  					
  					// iterate over colors array to check if color exists already in array
  					// if no colors yet, add the first
  					//if (empty($colors))
  					//{
  					//	$colors[$pixel_count] = $icfi;
  					//}
  					
  					//else
  					//{
  					//foreach ($colors as $color)
  					//{
  						//$cr = $color["red"];
  						//$cg = $color["green"];
  						//$cb = $color["blue"];
  						//if ($r == $cr && $g == $cg && $b == $cb)
  						//{
  						//	$repeats++;
  						//}
  						//else
  						//{
  							// add color to array using its rgb string as a key to avoid duplicates
  							$colors[$r . $g . $b] = $icfi;
  						//}
  					//}
  					//}
  				}
  			}
  			
  			//foreach ($colors as $color)
  			//{
  			//	dump($color);
  			//}
  			
  			//dump($colors);
  			render("palette.php", ["colors" => $colors]);
    	}
  	}
	else
  	{
  		echo "Invalid file";
  	}

?>