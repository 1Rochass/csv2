<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/style.css">
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
				<label for="file-to-save">File to save
					<input type="text" name="fileToSave" id="file-to-save">
				</label>
				<label for="folder-to-save">Folder to save
					<input type="text" name="folderToSave" id="folder-to-save" value="parse-directory">
				</label>
				<input type="submit" name="curlSubmit" value="Parse">
				<label class="parse-alert">
					<?php 
						if ( isset( $_GET['parseAlert'] ) ) {
							echo $_GET['parseAlert'];
						}
					 ?>
				</label>
			</form>
		</div>
		<div class="PQLinks">
			<h2>PQLinks</h2>
			<form method="POST" action="MainController.php">
				<label for="file">File
					<?php require "show-files.php" ?>	
				</label>
				<label for="folder-to-save">Folder to save
					<input type="text" name="folderToSave" id="folder-to-save" value="parse-directory">
				</label>
				<input type="submit" name="pQLinksSubmit" value="Parse">
				<label class="pq-alert">
					<?php 
						if ( isset( $_GET['pQAlert'] ) ) {
							echo $_GET['pQAlert'];
						}
					 ?>
				</label>
			</form>
		</div>
		<div class="PQGoods">
			<h2>PQGoods</h2>
			<form method="POST" action="MainController.php">
				<label for="file">File
					<?php require "show-files.php" ?>	
				</label>
				<label for="folder-to-save">Folder to save
					<input type="text" name="folderToSave" id="folder-to-save" value="parse-directory">
				</label>
				<input type="submit" name="pQGoodsSubmit" value="Parse">
				<label class="pq-goods-alert">
					<?php 
						if ( isset( $_GET['pQGoodsAlert'] ) ) {
							echo $_GET['pQGoodsAlert'];
						}
					 ?>
				</label>
			</form>
		</div>
	</div>
	<div class="footer">
		Some text
	</div>
</div>
</body>
</html>