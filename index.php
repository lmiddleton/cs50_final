<!DOCTYPE html>

<html>

<head>
	<title>Palette</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<!-- CSS -->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="js/dropzone/dropzone-3.7.3/downloads/css/dropzone.css" />
	<link rel="stylesheet" href="css/styles.css" />
	
	<!-- JS -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/dropzone/dropzone-3.7.3/downloads/dropzone.min.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
</head>

<body>
	
	<h1>Palette</h1>
	
	<div class="col-md-9">
	
		<div class="col-md-7">
			<h2>Upload an image...</h2>
			<form enctype="multipart/form-data" action="php/drop.php" class="dropzone" id="drop" style=""></form>
		</div>
	
		<div id="middle" class="col-md-5">
			<h2>...to see its palette...</h2>
		</div>
	
	</div>
	
	<div class="col-md-3">
		<h2>...or enter a color.</h2>
		
		<form id="convert-form">
			<div>
				<label for="hex">HEX:</label>
				#<input id="hex" type="text" maxlength="7" />
			</div>
				
			<br />
		
			<div class="rgb">
				<label for="r">R:</label>
				<input id="r" type="number" min="0" max="255" />
			</div>
				
			<div class="rgb">
				<label for="g">G:</label>
				<input id="g" type="number" min="0" max="255" />
			</div>
			
			<div class="rgb">
				<label for="b">B:</label>
				<input id="b" type="number" min="0" max="255" />
			</div>
		</form>
		
		<div id="swatch"></div>
		
		<h4>Complement</h4>
		<div>
			<div class="comp-swatch" id="comp-swatch"></div>
		</div>
		
		<h4>Split Complement</h4>
		<div>
			<div class="split-swatch" id="split-swatch0"></div>
			<div class="split-swatch" id="split-swatch1"></div>
		</div>
		
	</div>
	
</body>

</html>