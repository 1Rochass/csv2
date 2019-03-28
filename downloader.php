<?php 
/*
* Show files
*/

// Show files
if (isset($_POST['showFiles'])){
	showFiles();
}
// Delete files
if (isset($_POST['deleteFiles'])) {

	$fileName = $_POST['deleteFiles'];
	deleteFiles($fileName);	
}

/*
* Show files
*/
function showFiles() {
	$path =  'csv/';
	// Get all files
	$files = scandir($path); 
	// Path to file to save from server
	$filePath = 'http://' . $_SERVER['SERVER_NAME'] . '/csv/';

		/*
		* Loop csv files
		*/
		echo '<table>';
		foreach ($files as $value) {
			if ($value == '.' || $value == '..') {
			}
			else {
				echo '<tr>';
				echo '<td><a href="' . $filePath . $value . '">' . $value . '</a></td>';
				echo '<td><input type="button" id="'. $value .'" class="deleteFiles" value="Удалить"></td>';
				echo '</tr>';
			}
			
		}
		echo '</table>';
}

/*
* Delete files
*/
function deleteFiles($fileName) {

	$fileName;

	// Make cleaned file
	$cleanedFile = str_replace(".csv", "", $fileName);
	// Add link_ html_ .txt to delete in parse-directory
	$linkFile = 'links_' . $cleanedFile . '.txt';
	$htmlFile = 'html_' . $cleanedFile . '.txt';


	if (unlink('csv/' . $fileName)) {
		echo 'Файл <span style="color: lightgreen">' . $fileName . '</span> удален из папки scv </br>';
	}
	if (unlink('parse-directory/' . $linkFile)) {
		echo 'Файл <span style="color: lightgreen">' . $linkFile . '</span> удален из папки parse-directory </br>';
	}
	if (unlink('parse-directory/' . $htmlFile)) {
		echo 'Файл <span style="color: lightgreen">' . $htmlFile . '</span> удален из папки parse-directory </br>';
	}
	
}

	 


		
	
// }


