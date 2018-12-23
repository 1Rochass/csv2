<?php

class Curl {

	public $urlToParse; // Url what we must to parse
	public $folderToSave; // Folder where your must to save parsed html
	public $fileToSave; // File where your must to save parsed html 

	public $html; // Parsed html

	public $curl_useragent;
	public $curl_useragent_path = "useragent.txt";

	public $curl_proxy;
	public $curl_proxy_path = "proxy.txt";

	// Curl parse
	public function curlParse( $url )
	{
		$ch = curl_init( $url );

		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); // Сейвит результат в переменную

		//curl_setopt( $ch , CURLOPT_HEADER, true ); // Возвращает в переменную заголовки ( Для отладки )

		//curl_setopt( $ch, CURLOPT_NOBODY, true ); // Получает только заголовки ( HEADER )

		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true ); // Если редирект ( ошибка  302 на странице ) включает эту опцию 

		curl_setopt( $ch, CURLOPT_USERAGENT, 'Opera/9.80 (Windows NT 5.1; U; ru) Presto/2.9.168 Version/11.51' ); // хз

		

		// curl_setopt( $ch, CURLOPT_PROXY, '93.113.6.19' ); // хз 
		// http://free-proxy.cz
	
		curl_setopt( $ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS4 ); // хз

		

		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false ); // Отключает проверки в https

		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false ); // Отключает проверки в https

		$html = curl_exec( $ch );

		curl_close( $ch );

		 $this->html = $html;

	} 

	// Check submit
	public function curlCheck() {
		if ( isset( $_POST['parseSubmit'] ) ) {
			$this->urlToParse = $_POST['urlToParse'];
			$this->folderToSave = $_POST['folderToSave'];
			$this->fileToSave = $_POST['fileToSave']; 

		}
		// Check feeling forms
		if ( empty( $this->urlToParse ) || empty( $this->folderToSave ) ||  empty( $this->fileToSave ) ) {
			echo "<script>";
			echo "window.location.href='http://csv/index.php?parseAlert=URL, file or folder string is empty. Please feel it.'";
			echo "</script>";	
		}

		// Make file name where your must to save parsed html
		$this->fileToSave = "html_" . $this->fileToSave . ".txt";

		$this->curlRun();
	}

	// Curl run
	public function curlRun( )
	{
		$html = $this->curlParse( $this->urlToParse );

		if ( file_put_contents( $this->folderToSave . "/" . $this->fileToSave, $this->html ) ) {
			echo "<script>";
			echo "window.location.href='http://csv/index.php?parseAlert=Parsed html was saved in " . $this->folderToSave . "'";
			echo "</script>";
		}
		else {
			echo "<script>";
			echo "window.location.href='http://csv/index.php?parseAlert=Parsed html was not saved'";
			echo "</script>";	
		}
	}

}

$curl = new Curl();
$curl->curlCheck();
?>