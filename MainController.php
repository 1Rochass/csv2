<?php
require_once "curl.php";
require_once "pq.php";

class MainController {
	
	// Check submit
	public function mCCheck() {
		if ( isset( $_POST['curlSubmit'] ) ) {
			$this->curlHtml();
		}
		if ( isset( $_POST['pQLinksSubmit'] ) ) {
			$this->pQLinks();
		}
		// if ( isset( $_POST['pQSubmit'] ) ) {
		// 	$this->pQGoods();
		// }
	}

	// Curl html
	public function curlHtml() {
		// If isset submit in $this->mCCheck		
		$urlToParse = $_POST['urlToParse'];
		$folderToSave = $_POST['folderToSave'];
		$fileToSave = $_POST['fileToSave']; 

		// Check feeling forms
		if ( empty( $urlToParse ) || empty( $folderToSave ) ||  empty( $fileToSave ) ) {
			echo "<script>";
			echo "window.location.href='http://csv/index.php?parseAlert=URL, file or folder string is empty. Please feel it.'";
			echo "</script>";	
		}

		// Curl
		$curl = new Curl( $urlToParse, $folderToSave, $fileToSave ); // Make new object
		$curl->curlParse(); // Curl parse
		$curlResponse = $curl->curlSave(); // Curl save



		echo "<script>";
		echo "window.location.href='http://csv/index.php?parseAlert=" . $curlResponse . "'";
		echo "</script>";

	}

	// Parse goods links
	public function pQLinks() {
		// If isset submit in $this->mCCheck
		$folderToSave = $_POST['folderToSave'];
		$file = $_POST['file']; // File name with html for parsing
		
		// Check feeling forms
		if ( empty( $file ) || empty( $folderToSave ) ) {
			echo "<script>";
			echo "window.location.href='http://csv/index.php?pQAlert=File or folder string is empty. Please feel it.'";
			echo "</script>";	
		}

		// Pq
		$pQ = new PQ( $folderToSave, $file ); // Make new object
		$pQResponse = $pQ->pQParseLinks(); // Pq parse links
		 
		echo "<script>";
		echo "window.location.href='http://csv/index.php?pQAlert=" . $pQResponse . "'";
		echo "</script>";		
	}
}

$mc = new MainController();
$mc->mCCheck();
?>
