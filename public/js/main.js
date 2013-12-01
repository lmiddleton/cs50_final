$(document).ready(function(){
	//initHandleKeypress();
	handleConvert();
	
	// set dropzone options
	Dropzone.options.drop = {
		acceptedFiles: '.jpeg, .jpg, .gif, .png',
		init: function() {
			this.on("success", function(file, response) {
				console.log(response);
				// parse the response
				var object = $.parseJSON(response);
				
				// print the color palette
				$('#middle').append('<div style="clear: left; margin-bottom: 7px;">');
				for (var i = 0, n = object.length; i < n; i++)
				{
					$('#middle').append('<div style="float: left; width: 30px; height: 20px; background-color: rgb(' + object[i] + ')"></div>');
				}
				$('#right').append('</div>');
			});
		},
		maxFilesize: 5 // MB
	};
	
	// handle split complement button
	$('#split-comp').on('click', function() {
		
		// grab rgb values
		var r = $('#r').val();
		var g = $('#g').val();
		var b = $('#b').val();
		
		// calculate split complementries
		var splitComps = splitComp(r, g, b);
		
		// if false, returned, display same color
		if (!splitComps) {
			r0 = r1 = r;
			g0 = g1 = g;
			b0 = b1 = b;
		}
		
		else {
			var r0 = splitComps[0].r;
			var g0 = splitComps[0].g;
			var b0 = splitComps[0].b;
		
			var r1 = splitComps[1].r;
			var g1 = splitComps[1].g;
			var b1 = splitComps[1].b;
		}
		
		// determine hex
		console.log(r0 + ',' + g0 + ',' + b0);
		var hex0 = rgbToHex(r0, g0, b0);
		var hex1 = rgbToHex(r1, g1, b1);
		console.log(hex0 + ',' + hex1);
		
		$('#split-swatch0').css('background-color', 'rgb(' + r0 + ',' + g0 + ',' + b0 + ')');
		$('#split-swatch1').css('background-color', 'rgb(' + r1 + ',' + g1 + ',' + b1 + ')');
	});
		
});

// global that stores hex letter values
var hexLetterKey = [
	{'letter': 'A', 'number': 10},
	{'letter': 'B', 'number': 11},
	{'letter': 'C', 'number': 12},
	{'letter': 'D', 'number': 13},
	{'letter': 'E', 'number': 14},
	{'letter': 'F', 'number': 15}
];

/*handles keypress on HEX input*/
/*
function initHandleKeypress()
{
	$('#hex').keypress(function() {
		var hex = $(this).val();
		hexToRgb(hex);
	});
}
*/

/*handles convert form submit*/
function handleConvert()
{
	$('#convert-form').submit(function(event) {
		// prevent page reload
		event.preventDefault();
		
		// store input
		var hex = $('#hex').val();
		
		var r = $('#r').val();
		var g = $('#g').val();
		var b = $('#b').val();
		
		// convert to uppercase
		// TODO
		
		// if hex entered
		if (hex != '')
		{
			var hexValid = /^[0-9a-fA-F]+$/;
			
			// validate hex
			if (hex.length != 6 || !hex.match(hexValid))
			{
				alert('Please enter a valid HEX code.');
			}
			else
			{
				// convert
				var rgb = hexToRgb(hex);
				
				$('#r').val(rgb.r);
				$('#g').val(rgb.g);
				$('#b').val(rgb.b);
				
				$('#swatch').css('background-color', '#' + hex);
			}
		}
		
		// if rgb entered
		else if (r != '' || g != '' || b != '')
		{
			var rgbValid = /^[0-9]+$/;
		
			// check all 3 are entered
			if (r == '' || g == '' || b == '')
			{
				alert('Please enter all 3 RGB values.');
			}
			
			// validate rgb
			else if ((r > 255 || !r.match(rgbValid)) ||
					 (g > 255 || !g.match(rgbValid)) ||
					 (b > 255 || !b.match(rgbValid))
					)
			{
				alert('Please enter valid RGB values.');
			}
			
			else
			{
				// convert
				var hex = rgbToHex(r, g, b);
				$('#hex').val(hex);
				rgbToCmyk(r, g, b);
				$('#swatch').css('background-color', 'rgb(' + r + ',' + g + ',' + b + ')');
			}
		}
		
		// if cmyk entered
		else if (c != '' || m != '' || y != '' || k != '')
		{
			var cmykValid = /^[0-9]+$/;
		
			// check all 4 are entered
			if (c == '' || m == '' || y == '' || k == '')
			{
				alert('Please enter all 4 CMYK values.');
			}
			
			// validate cmyk
			else if ((c > 100 || !c.match(cmykValid)) ||
					 (m > 100 || !m.match(cmykValid)) ||
					 (y > 100 || !y.match(cmykValid)) ||
					 (k > 100 || !k.match(cmykValid))
					)
			{
				alert('Please enter valid CMYK values.');
			}
			
			else
			{
				//convert
				//cmykToRgb(c, m, y, k);
			}
		}
		
		else
		{
			// nothing entered
			alert('Please enter a value to convert.');
		}
	
	});
}

/* returns object with RGB values for the given 6 digit hex value
 * formula derived from http://www.pixel2life.com/publish/tutorials/164/using_php_to_convert_between_hex_and_rgb_values/
 */
function hexToRgb(hex)
{
	// format with uppercase letters
	var hex = hex.toUpperCase();
	
	// split into pairs and convert each
	var r = hexToDec(hex.slice(0,1)) * 16 + parseInt(hexToDec(hex.slice(1,2)));
	console.log(hexToDec(hex.slice(0,1)));
	var g = hexToDec(hex.slice(2,3)) * 16 + parseInt(hexToDec(hex.slice(3,4)));
	var b = hexToDec(hex.slice(4,5)) * 16 + parseInt(hexToDec(hex.slice(5,6)));
	
	// build RGB object
	var rgb = new Object();
	rgb.r = r;
	rgb.g = g;
	rgb.b = b;
	
	return rgb;
}

/* returns 6 digit hex value for the given RGB values
 * formula derived from http://gristle.tripod.com/hexconv.html
 */
function rgbToHex(r, g, b)
{
	// calculate pairs
	var reds = decToHex(Math.floor(r / 16)) + (decToHex(r % 16)).toString();
	var greens = decToHex(Math.floor(g / 16)) + (decToHex(g % 16)).toString();
	var blues = decToHex(Math.floor(b / 16)) + (decToHex(b % 16)).toString();
	
	// build hex
	var hex = reds + greens + blues;
	
	return hex;
}

/*converts rgb values to cmyk values*/
/*formulas derived from http://www.easyrgb.com/index.php?X=MATH&H=11#text11*/
function rgbToCmyk(r, g, b)
{
	/*
	// first convert rgb -> cmy
	var c = 1 - (r / 255);
	var m = 1 - (g / 255);
	var y = 1 - (b / 255);
	
	// then convert cmy -> cmyk
	var k = 1;
	
	if (c < k)
	{
		k = c;
	}
	if (m < k)
	{
		k = m;
	}
	if (y < k)
	{
		k = y;
	}
	if (k == 1)
	{
		// black
		c = 0;
		m = 0;
		y = 0;
	}
	else
	{
		c = (c - k) / (1 - k);
		m = (m - k) / (1 - k);
		y = (y - k) / (1 - k);
	}
	*/
	
	
	// version 2: http://www.rapidtables.com/convert/color/rgb-to-cmyk.htm
	var red = r / 255;
	console.log(red);
	var green = g / 255;
	console.log(green);
	var blue = b / 255;
	console.log(blue);
	
	var k = 1 - Math.max(red, green, blue);
	console.log(Math.max(red, green, blue));
	var c = (1 - red - k) / (1 - k);
	var m = (1 - green - k) / (1 - k);
	var y = (1 - blue - k) / (1 - k);
	
	$('#c').val(c);
	$('#m').val(m);
	$('#y').val(y);
	$('#k').val(k);
	
}

/* returns the hex character for a decimal value */
function decToHex(value)
{
	// leave single digit values
	if (value <= 9)
	{
		return value;
	}
	// double digit values are 
	else
	{
		for (var i = 0; i < hexLetterKey.length; i++)
		{
			if (value == hexLetterKey[i].number)
			{
				return hexLetterKey[i].letter;
			}
		}
	}
}

/*returns the decimal value for a hex character*/
function hexToDec(character)
{	
	// convert character
	if (character >= 0 || character <= 9)
	{
		return character;
	}
	else
	{
		for (var i = 0; i < hexLetterKey.length; i++)
		{
			if (character == hexLetterKey[i].letter)
			{
				return hexLetterKey[i].number;
			}
		}
	}
}

/* returns the HSV values for the given RGB values
 * h = [0, 360], s = [0, 1], v = [0,1]
 * if s == 0, then h = -1 (undefined)
 * formula derived from http://www.cs.rit.edu/~ncs/color/t_convert.html
 * and http://www.rapidtables.com/convert/color/rgb-to-hsv.htm */
function rgbToHsv(r, g, b)
{
	// change rgb range from 0 to 1
	var r = r/255;
	var g = g/255;
	var b = b/255;
	
	var min = Math.min(r, g, b);
	var max = Math.max(r, g, b);
	
	var hsv = new Object();
	
	hsv.v = max;
	
	var delta = max - min;
	
	if (max != 0) {
		hsv.s = delta / max;
	}
	else {
		// r = g = b = 0 (s = 0, v is undefined)
		hsv.s = 0;
		hsv.h = -1;
		return hsv;
	}
	
	if (hsv.r == max) {
		// between yellow and magenta
		hsv.h = (g - b) / delta;
	}
	else if (g == max) {
		// between cyan and yellow
		hsv.h = 2 + (b - r) / delta;
	}
	else {
		// between magenta and cyan
		hsv.h = 4 + (r - g) / delta;
	}
	
	// degrees
	hsv.h = hsv.h * 60;
	if (hsv.h < 0) {
		hsv.h = hsv.h + 360;
	}
	
	return hsv;
}

/* formula derived from http://www.cs.rit.edu/~ncs/color/t_convert.html*/
function hsvToRgb (hsv)
{
	// unpack
	var h = hsv.h;
	var s = hsv.s;
	var v = hsv.v;
	
	var rgb = new Object();
	var i, f, p, q, t;
	
	if (s == 0) {
		// achromatic (grey)
		rgb.r = v;
		rgb.g = v;
		rgb.b = v;
		return rgb;
	}
	
	// sector 0 to 5
	h = h / 60;
	i = Math.floor(h);
	// factorial part of h
	f = h - i;
	p = v * (1 - s);
	q = v * (1 - s * f);
	t = v * (1 - s * (1 - f));
	
	switch (i)
	{
		case 0:
			rgb.r = v;
			rgb.g = t;
			rgb.b = p;
			break;
			
		case 1:
			rgb.r = q;
			rgb.g = v;
			rgb.b = p;
			break;
			
		case 2:
			rgb.r = p;
			rgb.g = v;
			rgb.b = t;
			break;
			
		case 3:
			rgb.r = p;
			rgb.g = q;
			rgb.b = v;
			break;
			
		case 4:
			rgb.r = t;
			rgb.g = p;
			rgb.b = v;
			break;
			
		default:
			rgb.r = v;
			rgb.g = p;
			rgb.b = q;
			break;
	}
	
	rgb.r = Math.round(rgb.r * 255);
	rgb.g = Math.round(rgb.g * 255);
	rgb.b = Math.round(rgb.b * 255);
	
	return rgb;
}

/* returns object containing split complements of a color given its RGB or false if s = 0
 * formula derived from http://stackoverflow.com/questions/9577590/formula-to-find-the-split-complementaries-of-a-color */
function splitComp(r, g, b)
{
	// convert to hsv
	var hsv = rgbToHsv(r, g, b);
	console.log(hsv);
	
	// complement
	// NEED THIS TO WRAP AROUND SO NEVER MORE THAN 360
	if (hsv.s == 0)
	{
		return false;
	}
	var h = hsv.h + 180;
	console.log('h: ' + h);
	var s = hsv.s;
	var v = hsv.v;
	// split complements
	var h0 = (h + 30) % 360;
	console.log('h0: ' + h0);
	var h1 = (h - 30) % 360;
	console.log('h1: ' + h1);
	
	var hsv0 = {
		h: h0,
		s: s,
		v: v
	};
	
	var hsv1 = {
		h: h1,
		s: s,
		v: v
	};
	
	console.log(hsv0);
	console.log(hsv1);
	
	// convert back to rgb
	var rgb0 = hsvToRgb(hsv0);
	//console.log(rgb0);
	var rgb1 = hsvToRgb(hsv1);
	//console.log(rgb1);
	
	var splitComps = new Array();
	splitComps[0] = rgb0;
	splitComps[1] = rgb1;
	
	return splitComps;
}