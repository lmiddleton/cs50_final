<?php

    /**
     * functions.php
     *
     * Modified from:
     * Computer Science 50
     * Problem Set 7
     *
     * Helper functions.
     */

	// display errors, warnings, and notices
    ini_set("display_errors", true);
    error_reporting(E_ALL);

    /**
     * Facilitates debugging by dumping contents of variable
     * to browser.
     */
    function dump($variable)
    {
        require("../dump.php");
        exit;
    }


    /**
     * Renders template, passing in values.
     */
     /*
    function render($template, $values = [])
    {
        // if template exists, render it
        if (file_exists("../templates/$template"))
        {
            // extract variables into local scope
            extract($values);

            // render header
            //require("../templates/header.php");

            // render template
            require("../templates/$template");

            // render footer
            //require("../templates/footer.php");
        }

        // else err
        else
        {
            trigger_error("Invalid template: $template", E_USER_ERROR);
        }
    }
    */
    
/***************/
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

?>
