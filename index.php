<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<script src="http://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
	<title></title>
</head>
<body>
<div class="wrapper">
	<div class="content">
		<div class="curl">
			<h2>Curl</h2>
			<form method="POST" action="MainController.php">
				<label for="url-to-parse">URL to parse
					<input type="text" name="urlToParse" id="url-to-parse">
				</label>
				<!-- <label for="file-to-save">File to save
					<input type="text" name="fileToSave" id="file-to-save">
				</label>
				<label for="folder-to-save">Folder to save
					<input type="text" name="folderToSave" id="folder-to-save" value="parse-directory">
				</label> -->
				<input type="submit" name="curlSubmit" value="Parse">
				<label class="parse-alert">
					<?php 
						if ( isset( $_GET['Alert'] ) ) {
							echo $_GET['Alert'];
						}
					 ?>
				</label>
			</form>
		</div>
		
		<div class="PQGoods">
			<h2>Files</h2>
			<div id="files"></div>
			<div id="alerts"></div>
		</div>
	</div>
	<div class="footer">
		Some text
	</div>
</div>
<script type="text/javascript" src="script.js"></script>
</body>
</html>