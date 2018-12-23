<?php 
// Require phpQuery
require "phpQuery.php";

class PQ {
	
	public $folderToSave; // Folder to save and get html
	public $file; // File with html for parsing
	
	public $html; // Html what we need to parse

	public $product; // Product data
	
	// Construct
	public function __construct( $folderToSave, $file ) {
		$this->folderToSave = $folderToSave;
		$this->file = $file; // File name with html for parsing
		
		// Get html for parsing 
		$this->html = file_get_contents( $this->folderToSave . "/" . $this->file );
	}	

	// pQ save text to csv file
	public function pQsave( $data=NULL) {

		// Make string from array
		$string = implode( "", $data );

		$file = fopen( "csv/products.csv", "a+" );
		fwrite( $file, $string );
		fclose( $file );
	}



	// Parse links
	public function pQParseLinks () {
		// Make main object
		$pq = phpQuery::newDocument( $this->html );
		// Find "a" tags from class ".products"
		$elements = $pq->find( ".woocommerce-LoopProduct-link" );
		
		foreach ($elements as $a) {
			// Make phqQuery object	
			$a = pq( $a );	
			// Get attr
			$links[] = $a->attr( "href" );
		}

		// Check parsing links
		if ( count( $links ) == 0 ) {
			return "0 links has parsed";
		}

		// Save products links
		$links = array_unique( $links ); // Make unique links

		// Make, open and save file with data
		$fileName =  str_replace( "html_", "", $this->file ); // Delete prefix "html_"

		$file = fopen( $this->folderToSave . "/links_" . $fileName, "w+" ); // Open file
		fwrite( $file, implode("\r\n", $links) ); // Write data
		fclose( $file ); // Close file

		return count( $links ) . " links has parsed";
	} 

	// Parse images 
	public function pQParseImages() {

		// Make main object
		$pq = phpQuery::newDocument( $this->html );


		$element = $pq->find(".views2-img img");
		
		// Get attr from DOM element!!!
		foreach ($element as $value) {
			
			$attr = pq( $value );
			// $href[] = $attr->attr("src")


			// Get image link
			$src = $attr->attr("src");
			

			$src = "http://csv/sait-to-parse/" . $src; 

			// Basename for saving
			$imgBaseName = basename( $src );

			// Make and save piccture
			$img = file_get_contents( $src );
			file_put_contents( "img/" . $imgBaseName, $img );

		}
	}


	// Parse product
	public function pQParseProduct () {

		// Make main phpquery object
		$pq = phpQuery::newDocument( $this->html );

		// Parse product-name
		$element = $pq->find( ".product_title" ); //Find element
		$productName = $element->text(); // Get text from element
		// New product in csv file begin from \n
		$productName = "\r" . $productName;
		// Add data to product array
		$this->product[] = $productName;


		// Parse product-price         ,product-short-description
		
		// !!! Only for st-ok.u delete some data before parse
		$pq->find( ".columns-3" )->remove(); 
		

		$element = $pq->find( ".price .woocommerce-Price-amount" ); //Find element
		$productPrice = $element->text(); // Get text from element
		// New product in csv file begin from \n
		$productPrice = "," . $productPrice;
		// Add data to product array
		$this->product[] = $productPrice;



		// Save data to csv file
		$this->pQsave( $this->product );



	}

}

// $pq = new PQ();
// $pq->pQCheck();
// $pq->pQGetHtml();
// // Run pQParse
// // $pq->pQParseImages();
// // $pq->pQParseProduct();
// $pq->pQParseLinks();

?>