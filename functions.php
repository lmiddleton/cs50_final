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

    //require_once("constants.php");

    /**
     * Apologizes to user with message.
     */
     /*
    function apologize($message)
    {
        render("apology.php", ["message" => $message]);
        exit;
    }
    */

    /**
     * Facilitates debugging by dumping contents of variable
     * to browser.
     */
    function dump($variable)
    {
        require("dump.php");
        exit;
    }

    /**
     * Logs out current user, if any.  Based on Example #1 at
     * http://us.php.net/manual/en/function.session-destroy.php.
     */
     /*
    function logout()
    {
        // unset any session variables
        $_SESSION = array();

        // expire cookie
        if (!empty($_COOKIE[session_name()]))
        {
            setcookie(session_name(), "", time() - 42000);
        }

        // destroy session
        session_destroy();
    }
    */


    /**
     * Redirects user to destination, which can be
     * a URL or a relative path on the local host.
     *
     * Because this function outputs an HTTP header, it
     * must be called before caller outputs any HTML.
     */
    function redirect($destination)
    {
        // handle URL
        if (preg_match("/^https?:\/\//", $destination))
        {
            header("Location: " . $destination);
        }

        // handle absolute path
        else if (preg_match("/^\//", $destination))
        {
            $protocol = (isset($_SERVER["HTTPS"])) ? "https" : "http";
            $host = $_SERVER["HTTP_HOST"];
            header("Location: $protocol://$host$destination");
        }

        // handle relative path
        else
        {
            // adapted from http://www.php.net/header
            $protocol = (isset($_SERVER["HTTPS"])) ? "https" : "http";
            $host = $_SERVER["HTTP_HOST"];
            $path = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
            header("Location: $protocol://$host$path/$destination");
        }

        // exit immediately since we're redirecting anyway
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
    function get_palette($img, $width, $height, &$color_counts, &$palette)
    {
    	
    	//dump($width);
    		
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
  							// factor out magic number
  							if ($r_diff < 70 && $g_diff < 70 && $b_diff < 70)
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
  						
  						/*
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
  						*/
  					}
  				}
  			}
  			//dump($palette);
    }

?>
