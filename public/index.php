<!DOCTYPE html>

<html>

<head>
	<title>Palette</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<!-- CSS -->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="js/dropzone/dropzone-3.7.3/downloads/css/dropzone.css" />
	
	<!-- JS -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/dropzone/dropzone-3.7.3/downloads/dropzone.min.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
</head>

<body>
	
	<!--
	<?php
		for ($i = 0; $i < 10; $i++)
		{
			print $i;
		}
	?>
	-->
	
	<h1>Palette</h1>
	
	<div class="col-md-9">
	
		<div class="col-md-8">
			<h2>Upload a file...</h2>
			<form enctype="multipart/form-data" action="drop.php" class="dropzone" id="drop" style=""></form>
		</div>
	
		<div id="middle" class="col-md-4">
		
			<div id="swatch" style="width: 100px; height: 100px; display: inline-block;"></div>
		<div id="split-swatch0" style="width: 100px; height: 100px; display: inline-block;"></div>
		<div id="split-swatch1" style="width: 100px; height: 100px; display: inline-block;"></div>
	
			
	
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
		
				<div>
					<label for="r">R:</label>
					<input id="r" type="number" min="0" max="255" style="background-color: #f18383;" />
				</div>
				
				<div>
					<label for="g">G:</label>
					<input id="g" type="number" min="0" max="255" style="background-color: #83f193;" />
				</div>
			
				<div>
					<label for="b">B:</label>
					<input id="b" type="number" min="0" max="255" style="background-color: #83b7f1;" />
				</div>
				
				<br />
				
				<!--
				<div>
					<label for="c">C:</label>
					<input id="c" type="number" min="0" max="100" />%
				</div>
				
				<div>
					<label for="m">M:</label>
					<input id="m" type="number" min="0" max="100" />%
				</div>
				
				<div>
					<label for="y">Y:</label>
					<input id="y" type="number" min="0" max="100" />%
				</div>
				
				<div>
					<label for="k">K:</label>
					<input id="k" type="number" min="0" max="100" />%
				</div>
				
				<br />
				-->
				
				<div>
					<input type="submit" value="Convert" />
				</div>
			
			</form>
	
			<input id="split-comp" type="submit" value="Split Complement" />
	
			<!--
			<form enctype="multipart/form-data" action="drop.php" method="POST">
				<input type="file" name="file" />
				<input type="submit" />
			</form>
			-->
		
		
	</div>
	
</body>

</html>