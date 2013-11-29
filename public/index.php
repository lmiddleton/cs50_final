<!DOCTYPE html>

<html>

<head>
	<title>Color Convert</title>
	
	<link rel="stylesheet" href="js/dropzone/dropzone-3.7.3/downloads/css/dropzone.css" />
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script type="text/javascript" src="js/dropzone/dropzone-3.7.3/downloads/dropzone.min.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
</head>

<body>
	
	<?php
		for ($i = 0; $i < 10; $i++)
		{
			print $i;
		}
	?>
	
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
		<div>
			<input type="submit" value="Convert" />
		</div>
	</form>
	
	<form enctype="multipart/form-data" action="drop.php" method="POST">
		<input type="file" name="file" />
		<input type="submit" />
	</form>
	
	<div id="swatch" style="width: 100px; height: 100px;"></div>
	
	<form enctype="multipart/form-data" action="drop.php" class="dropzone" id="drop" style="width: 400px; height: 400px;"></form>
	
</body>

</html>