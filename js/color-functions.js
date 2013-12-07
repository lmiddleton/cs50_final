/* functions for converting and manipulating color formats */

// global that stores hex letter values
var hexLetterKey = [
	{'letter': 'A', 'number': 10},
	{'letter': 'B', 'number': 11},
	{'letter': 'C', 'number': 12},
	{'letter': 'D', 'number': 13},
	{'letter': 'E', 'number': 14},
	{'letter': 'F', 'number': 15}
];

/* returns object with RGB values for the given 6 digit hex value
 * formula derived from http://www.pixel2life.com/publish/tutorials/164/using_php_to_convert_between_hex_and_rgb_values/
 */
function hexToRgb(hexCode)
{
	// format with uppercase letters
	var hex = hexCode.toUpperCase();
			
	// if 3 digit hex, convert to 6 digit
	if (hex.length == 3)
	{
		var h0 = hex.slice(0,1);
		var h1 = hex.slice(1,2);
		var h2 = hex.slice(2,3);
		
		hex = h0 + h0 + h1 + h1 + h2 + h2;
	}
	
	// split into pairs and convert each
	var rgbPairs = new Object();
	rgbPairs.r0 = hex.slice(0,1);
	rgbPairs.r1 = hex.slice(1,2);
	
	rgbPairs.g0 = hex.slice(2,3);
	rgbPairs.g1 = hex.slice(3,4);
	
	rgbPairs.b0 = hex.slice(4,5);
	rgbPairs.b1 = hex.slice(5,6);
	
	// fill in 0s for missing values
	for (var i in rgbPairs)
	{
		if (rgbPairs[i] == '')
		{
			rgbPairs[i] = 0;
		}
	}
	
	var r = hexToDec(rgbPairs.r0) * 16 + parseInt(hexToDec(rgbPairs.r1));
	var g = hexToDec(rgbPairs.g0) * 16 + parseInt(hexToDec(rgbPairs.g1));
	var b = hexToDec(rgbPairs.b0) * 16 + parseInt(hexToDec(rgbPairs.b1));
	
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
function rgbToHex(red, green, blue)
{
	var r = red;
	var g = green;
	var b = blue;
	
	// fill in 0s for missing values
	if (r == '') {
		r = 0;
	}
	if (g == '') {
		g = 0;
	}
	if (b == '') {
		b = 0;
	}

	// calculate pairs
	var rgbPairs = {
		
		red0: decToHex(Math.floor(r / 16)),
		red1: decToHex(r % 16).toString(),
	
		green0: decToHex(Math.floor(g / 16)),
		green1: decToHex(g % 16).toString(),
	
		blue0: decToHex(Math.floor(b / 16)),
		blue1: decToHex(b % 16).toString()
	};
	
	// if undefined b/c of bad rgb value, set to empty string
	for (var i in rgbPairs)
	{
		if(rgbPairs[i] == undefined)
		{
			rgbPairs[i] = '';
		}
	}
	
	var reds = rgbPairs.red0 + rgbPairs.red1;
	var greens = rgbPairs.green0 + rgbPairs.green1;
	var blues = rgbPairs.blue0 + rgbPairs.blue1;
		
	// build hex
	var hex = reds + greens + blues;
	
	return hex;
}

/* returns the hex character for a decimal value */
function decToHex(value)
{
	// leave single digit values as is
	if (value <= 9)
	{
		return value;
	}
	// double digit values translated into letter
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
	var r = r / 255;
	var g = g / 255;
	var b = b / 255;
	
	// fix for certain yellows
	if (g >= 0.8)
	{
		g = 1;
	}
	
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

function getComplementHsv(r, g, b)
{
	// convert to hsv
	var hsv = rgbToHsv(r, g, b);
	
	// complement
	if (hsv.s == 0)
	{
		return false;
	}
	var h = (hsv.h + 180) % 360;
	var s = hsv.s;
	var v = hsv.v;
	
	var hsv = {
		h: h,
		s: s,
		v: v
	}
	
	return hsv;
}

function complement(r, g, b)
{
	var hsv = getComplementHsv(r, g, b);
	if (!hsv) {
		return false;
	}
	
	// convert back to rgb
	var rgb = hsvToRgb(hsv);
	
	return rgb;
}

/* returns object containing split complements of a color given its RGB or false if s = 0
 * formula derived from http://stackoverflow.com/questions/9577590/formula-to-find-the-split-complementaries-of-a-color */
function splitComp(r, g, b)
{
	var hsv = getComplementHsv(r, g, b);
	if (!hsv) {
		return false;
	}
	
	var h = hsv.h;
	var s = hsv.s;
	var v = hsv.v;
	
	// split complements
	var h0 = (h + 30) % 360;
	var h1 = (h - 30) % 360;
	
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
		
	// convert back to rgb
	var rgb0 = hsvToRgb(hsv0);
	var rgb1 = hsvToRgb(hsv1);
	
	var splitComps = new Array();
	splitComps[0] = rgb0;
	splitComps[1] = rgb1;
	
	return splitComps;
}