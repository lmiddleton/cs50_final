$(document).ready(function(){
	
	setDropOps();
	handlePaletteHover();
	handlePaletteClick();
	handleKeypress();
	handleConvertSubmit();
	
});

/* sets the dropzone options */
function setDropOps()
{
	Dropzone.options.drop = {
		accept: function(file, done) {
			// check file dimensions (stored in hidden div)
			checkDim(file);
			
			// timeout needed to prevent width and height being undefined
			setTimeout(function(){
				var width = $("#width").html();
				var height = $("#height").html();			
			
				if (width > 2500 || height > 2500) {
					done("Width and height must be less than 2500.");
				}
				else { done(); }
			},500);
		},
		acceptedFiles: '.jpeg, .jpg, .gif, .png',
		init: function() {
			this.on("success", function(file, response) {
				
				// parse the response
				var object = $.parseJSON(response);
				
				// print the color palette
				$('#middle').append('<div class="palette">');
				for (var i = 0, n = object.length; i < n; i++)
				{
					$('#middle').append('<div class="palette-swatch" style="background-color: rgb(' + object[i] + ')"></div>');
				}
				$('#right').append('</div>');
			});
		},
		maxFilesize: 5 // MB
	};
}

/* sets the palette swatch hover behavior */
function handlePaletteHover()
{
	$(document).on('mouseover', '.palette-swatch, #comp-swatch, .split-swatch', function() {
		$(this).addClass('palette-swatch-hover');
	});
	
	$(document).on('mouseleave', '.palette-swatch, #comp-swatch, .split-swatch', function() {
		$(this).removeClass('palette-swatch-hover');
	});
}

/* sets the palette swatch click event */
function handlePaletteClick()
{
	// on click
	$(document).on('click', '.palette-swatch, #comp-swatch, .split-swatch', function(event) {
		event.stopImmediatePropagation();
		
		// show swatches
		$('#swatches').show();
	
		// grab color (rgb(?,?,?)' format)
		var rgb = $(this).css('background-color');
		
		// trim extra characters
		rgb = rgb.substring(4, rgb.length - 1);
		
		// split rgb
		rgb = rgb.split(",");
		var r = rgb[0].trim();
		var g = rgb[1].trim();
		var b = rgb[2].trim();
				
		// convert to hex
		var hex = rgbToHex(r, g, b);
		
		updateSwatchPanel(r, g, b, hex);
	});
}

/* handles keypress in hex and rgb inputs */
function handleKeypress()
{
	/* handle hex input */
	// combine change and keyup to handle pasting, etc. without having to wait for focus change
	// snippet from http://stackoverflow.com/questions/7316283/trigger-change-event-and-keyup-event-in-select-element
	$('#hex').bind("change keyup", function(event) {
		
		// prevent arrow keys triggering submit
		// from http://stackoverflow.com/questions/17807300/keypress-event-ignore-arrow-keys
		// get keycode of current keypress event
    	var code = (event.keyCode ? event.keyCode : event.which);

    	// do nothing if it's an arrow key
   		if(code == 37 || code == 38 || code == 39 || code == 40) {
        	return;
    	}

		// submit the form for validation
		$("#convert-form").submit();
	
		// get new hex
		var hex = $(this).val();
		
		// convert to rgb
		var rgb = hexToRgb(hex);
		var r = rgb.r;
		var g = rgb.g;
		var b = rgb.b;
		
		updateSwatchPanel(r, g, b, hex);
	});
	
	/* handle rgb inputs */
	$('#r, #g, #b').bind("change keyup", function(event) {
		// get keycode of current keypress event
    	var code = (event.keyCode ? event.keyCode : event.which);

    	// do nothing if it's an arrow key
   		if(code == 37 || code == 38 || code == 39 || code == 40) {
        	return;
    	}
		
		// submit the form for validation
		$("#convert-form").submit();
				
		// grab rgb values
		var r = $('#r').val();
		var g = $('#g').val();
		var b = $('#b').val();
				
		// convert to hex
		var hex = rgbToHex(r, g, b);
		
		updateSwatchPanel(r, g, b, hex);
	});
}

/* handles hex/rgb form submit */
function handleConvertSubmit()
{
	$('#convert-form').submit(function(event) {
		// prevent page reload
		event.preventDefault();
		
		clearError();
		$('#swatches').show();
		
		// store input
		var hex = $('#hex').val();
		
		var r = $('#r').val();
		var g = $('#g').val();
		var b = $('#b').val();
		
		// if hex entered
		if (hex != '')
		{
			var hexValid = /^[0-9a-fA-F]+$/;
						
			// validate hex
			if ((hex.length != 6 && hex.length != 3) || !hex.match(hexValid))
			{
				error('Invalid Hex');
			}
		}
		
		// if rgb entered
		if (r != '' && g != '' && b != '')
		{
			var rgbValid = /^[0-9]+$/;
		
			// validate rgb
			if ((r > 255 || !r.match(rgbValid)) ||
				(g > 255 || !g.match(rgbValid)) ||
				(b > 255 || !b.match(rgbValid))
				)
			{
				error('Invalid RGB value.');
			}
		}
	});
}

/* updates the swatch panel with the selected or entered color */
function updateSwatchPanel(r, g, b, hex)
{		
	// load into rgb inputs
	updateRgbInputs(r, g, b);
		
	// load into hex input
	updateHexInput(hex);
		
	// update main swatch
	updateMainSwatch(r, g, b);
	
	// update comp
	updateComp(r, g, b);
		
	// update split comps
	updateSplitComps(r, g, b);	
}

/* returns example hex css snippet for the given hex */
function getHexCss(hex) {
	return 'background-color: #' + hex + ';';
}

/* returns example rgb css snippet for the given values*/
function getRgbCss(r, g, b) {
	return 'color: rgb(' + r + ',' + g + ',' + b + ');';
}

/* updates the css snippets in the given html element*/
function updateCssSnip(container, hex, r, g, b) {
	$(container).html(getRgbCss(r, g, b) + '<br />' + getHexCss(hex));
}

/* updates the rgb inputs with the provided values */
function updateRgbInputs(r, g, b) {
	$('#r').val(r);
	$('#g').val(g);
	$('#b').val(b);
}

/* updates the main swatch color */
function updateMainSwatch(r, g, b) {
	var hex = rgbToHex(r, g, b);
	$('#swatch').css('background-color', '#' + hex);
	// update main swatch css
	updateCssSnip('#swatch-code', hex, r, g, b);
}

/* updates the hex input with the provided value */
function updateHexInput(hex) {
	$('#hex').val(hex);
}

/* updates the split complement swatch colors */
function updateSplitComps(r, g, b)
{		
		// calculate split complements
		var splitComps = splitComp(r, g, b);
		
		var r0, g0, b0;
		var r1, g1, b1;
		
		// if false, returned, display same color
		if (!splitComps) {
			r0 = r1 = r;
			g0 = g1 = g;
			b0 = b1 = b;
		}
		
		else {
			r0 = splitComps[0].r;
			g0 = splitComps[0].g;
			b0 = splitComps[0].b;
		
			r1 = splitComps[1].r;
			g1 = splitComps[1].g;
			b1 = splitComps[1].b;
		}
		
		// determine hex
		var hex0 = rgbToHex(r0, g0, b0);
		var hex1 = rgbToHex(r1, g1, b1);
		
		// update swatches
		$('#split-swatch0').css('background-color', '#' + hex0);
		$('#split-swatch1').css('background-color', '#' + hex1);
}

/* updates the complement swatch color */
function updateComp(r, g, b)
{
	// calculate complement
	var comp = complement(r, g, b);
	
	var r0, g0, b0;
	
	// if false, returned, display same color
	if (!comp) {
		r0 = r;
		g0 = g;
		b0 = b;
	}
	
	else {
		r0 = comp.r;
		g0 = comp.g;
		b0 = comp.b;
	}
	
	// determine hex
	var hex = rgbToHex(r0, g0, b0);
	
	// update swatch
	$('#comp-swatch').css('background-color', '#' + hex);
}

/* returns JS object with width and height of provided image file
 * modified from: http://stackoverflow.com/questions/12570834/how-to-preview-image-get-file-size-image-height-and-width-before-upload
 */
function checkDim(file) {
	var reader = new FileReader();
    var image = new Image();

    reader.readAsDataURL(file);  
    reader.onload = function(_file) {
        image.src = _file.target.result; // url.createObjectURL(file);
        image.onload = function() {
            var w = this.width;
            var h = this.height;
            $("#width").html(w);
            $("#height").html(h);
        };
    };
    image.onerror = function() {
        alert('Invalid file type: '+ file.type);
    };   
}

/* triggers swatch panel error */
function error(message) {
	$('#error').html(message);
	$('#swatches').hide();
}

/* clears swatch panel error */
function clearError() {
	$('#error').html('');
}