<?php

	// configuration
    require("functions.php");
    
    // add some extra time just in case
    set_time_limit(100);
	
	// upload script modified from http://www.w3schools.com/php/php_file_upload.asp
	$allowedExts = array("gif", "GIF", "jpeg", "JPEG", "jpg", "JPG", "png", "PNG");
	$temp = explode(".", $_FILES["file"]["name"]);
	$filename = $temp[0];
	$extension = end($temp);
	if ((($_FILES["file"]["type"] == "image/gif")
		|| ($_FILES["file"]["type"] == "image/GIF")
		|| ($_FILES["file"]["type"] == "image/jpeg")
		|| ($_FILES["file"]["type"] == "image/JPEG")
		|| ($_FILES["file"]["type"] == "image/jpg")
		|| ($_FILES["file"]["type"] == "image/JPG")
		|| ($_FILES["file"]["type"] == "image/png")
		|| ($_FILES["file"]["type"] == "image/PNG"))
		&& ($_FILES["file"]["size"] < 5242880) // in bytes (5MB max)
		&& in_array($extension, $allowedExts))
  	{
  		if ($_FILES["file"]["error"] > 0)
    	{
    		echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
    	}
  		else
    	{
    		if (file_exists("../upload/" . $_FILES["file"]["name"]) == false)
      		{
      			// convert to png and store in uploads folder
      			// conversion snippet modified from http://stackoverflow.com/questions/8550015/convert-jpg-gif-image-to-png-in-php
      				imagepng(imagecreatefromstring(file_get_contents($_FILES["file"]["tmp_name"])), "../upload/" . $filename . ".png");
      			
      		}
      		
      		// update filename with new extension
      		$filename = $filename . ".png";
      		
      		// store path to file
      		$img_path = "../upload/" . $filename;
      		
      		// set max height and width
      		$width = 200;
      		$height = 200;
      		
      		resize($width, $height, $img_path, $filename);
  			
  			// new image
  			$img = imagecreatefrompng("../resized/" . $filename);
  			  			
  			// set up color storage array
  			$palette = array();
  			
  			// determine the palette
  			get_palette($img, $width, $height, $palette);
  			
  			// send json back to the client
  			$json = json_encode($palette);
  			echo $json;
    	}
  	}
	else
  	{
  		echo "Invalid file";
  	}

?>