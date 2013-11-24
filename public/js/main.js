$(document).ready(function(){
	//initHandleKeypress();
	initHandleSubmit();
	
	// configure dropzone
	/*
	$("div#drop").dropzone({
		url: "/file/post",
		init: function() {
			this.on("addedfile", function(file) {
				console.log('YEAH');
			});
		}
	});
	*/
});

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

/*handles form submit*/
function initHandleSubmit()
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
				hexToRgb(hex);
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
				rgbToHex(r, g, b);
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

/*converts a 6 digit hex value to rgb values*/
/*formula derived from http://www.pixel2life.com/publish/tutorials/164/using_php_to_convert_between_hex_and_rgb_values/*/
function hexToRgb(hex)
{
	// split into pairs and convert each
	var r = hexToDec(hex.slice(0,1)) * 16 + parseInt(hexToDec(hex.slice(1,2)));
	$('#r').val(r);
	var g = hexToDec(hex.slice(2,3)) * 16 + parseInt(hexToDec(hex.slice(3,4)));
	$('#g').val(g);
	var b = hexToDec(hex.slice(4,5)) * 16 + parseInt(hexToDec(hex.slice(5,6)));
	$('#b').val(b);
}

/*converts rgb values to 6 digit hex*/
/*formula derived from http://gristle.tripod.com/hexconv.html*/
function rgbToHex(r, g, b)
{
	// calculate pairs
	var reds = decToHex(Math.floor(r / 16)) + (decToHex(r % 16)).toString();
	var greens = decToHex(Math.floor(g / 16)) + (decToHex(g % 16)).toString();
	var blues = decToHex(Math.floor(b / 16)) + (decToHex(b % 16)).toString();
	
	var hex = reds + greens + blues;
	
	$('#hex').val(hex); 
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

/*returns the hex character for a decimal value*/
function decToHex(value)
{
	if (value <= 9)
	{
		return value;
	}
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