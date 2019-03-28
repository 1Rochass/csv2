<?php
require_once "curl.php";
require_once "pq.php";

class MainController {


	public $alert;

	public function makeAlert ($response) {
		echo "<script>";
		echo "window.location.href='http://";
		echo $_SERVER['SERVER_NAME'];
		echo "/index.php?Alert= " . $response . "'";
		echo "</script>";	

		die();
	}
	
	// Check submit
	public function mCCheck() {
		if ( isset( $_POST['curlSubmit'] ) ) {
			$this->curlHtml();
		}
		if ( isset( $_POST['pQLinksSubmit'] ) ) {
			$this->pQLinks();
		}
		if ( isset( $_POST['pQGoodsSubmit'] ) ) {
			$this->pQGoods();
		}
	}

	// Curl html
	public function curlHtml() {
		// If isset submit in $this->mCCheck		
		$urlToParse = $_POST['urlToParse'];
		// $folderToSave = $_POST['folderToSave'];
		// $fileToSave = $_POST['fileToSave']; 


		// Check feeling forms
		if ( empty( $urlToParse ) ) {
			$this->makeAlert('Please feel \"Url to parse\" form.');
		}

		// Curl
		$curl = new Curl( $urlToParse); // Make new object
		$curl->curlParse(); // Curl parse
		$curlResponse = $curl->curlSave(); // Curl save

		// If url is not true
		if ($curlResponse['curlAlert'] == 'Parsed html was not saved') {
			$this->makeAlert($curlResponse['curlAlert']);
		}

		// Parse goods links
		$this->pQLinks($curlResponse['folderToSave'], $curlResponse['file']);


	}

	// Parse goods links
	public function pQLinks($folderToSave, $file) {

		// If isset submit in $this->mCCheck
		$folderToSave = $folderToSave;
		$file = $file; // File name with html for parsing
		
		// If variables file and folderToSave are empty 
		if ( empty( $file ) || empty( $folderToSave ) ) {
			$this->makeAlert('File or folder string is empty. Please feel it.');	
		}

		// Pq
		$pQ = new PQ( $folderToSave, $file ); // Make new object
		$pQResponse = $pQ->pQParseLinks(); // Pq parse links
		 

		if ($pQResponse == '0 links has parsed') {
			$this->makeAlert($pQResponse);
		}


		

		// $pQResponse['fileToSave'] == links_fileName.txt
		$this->pQGoods($folderToSave, $pQResponse['fileToSave']);
	}

	/*
	 * Parse goods
	 */ 
	public function pQGoods($folderToSave, $file) {
		
		//$file == $pQResponse['fileToSave'].

		// Check feeling forms
		if ( empty( $file ) || empty( $folderToSave ) ) {

			$this->makeAlert('File or folder string is empty in MakeController->pQGoods.');
			
		}

		// Get html with products links
		$html = file_get_contents( $folderToSave . "/" . $file ); // Get links
		$links = explode( "\r\n", $html ); // Make array of links
		$links = array_diff( $links, array('') ); // Delete empty elements
		
		
		
		foreach ( $links as $link ) {
			// Curl
			$curl = new Curl( $link ); // Make new object
			$curlResponse = $curl->curlParse(); // Curl parse
			
			// Pq
			$pQ = new PQ(); // Make new object
			$pQResponse = $pQ->pQParseProduct( $curlResponse ); // Pq parse and get product
			$pQMakeFileResponse = $pQ->pQMakeFile( $file );
			$pQ->pQsave( $pQResponse ); // Save data to csv file

		}

		// Response
		$this->makeAlert($pQMakeFileResponse);
				
	}

	/*
	* Show files for download from server
	*/
	// public function showFilesForDownload() {

	// 	require 'downloader.php';

	// 	echo "string";


	// 	$downloader = new Downloader();

	// 	$downloader->findFiles();

	// }
}

$MainController = new MainController();
// $MainController->showFilesForDownload();
$MainController->mCCheck();

?>
