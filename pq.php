<?php 
// Require phpQuery
require "phpQuery.php";

class PQ {
	
	public $folderToSave; // Folder to save and get html
	public $file; // File with html for parsing
	
	public $html; // Html what we need to parse

	public $product; // Product data
	
	// Construct
	public function __construct( $folderToSave=NULL, $file=NULL ) {
		$this->folderToSave = $folderToSave;
		$this->file = $file; // File name with html for parsing
		
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

		// Get html for parsing 
		$this->html = file_get_contents( $this->folderToSave . "/" . $this->file );
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


		$element = $pq->find(".woocommerce-product-gallery__image img");
		
		// Get attr from DOM element!!!
		foreach ($element as $value) {
			
			$attr = pq( $value );
			// $href[] = $attr->attr("src")


			// Get image link
			$src = $attr->attr("data-large_image");

			// Basename for saving
			$imgBaseName = basename( $src );

			// Make and save piccture
			$img = file_get_contents( $src );
			file_put_contents( "img/" . $imgBaseName, $img );


			// csv
			$this->product['product_images'] = $src;


		}
	}

	


	// Parse product
	public function pQParseProduct ( $html ) {


		// Assign a value to the property
		$this->html = $html;

		// Make main phpquery object
		$pq = phpQuery::newDocument( $html );


		// ID, Type, article
			$this->product['ID_Type_article'] = ",simple,,";

		// Name
			$element = $pq->find( ".product_title" ); //Find element
			$productName = $element->text(); // Get text from element
			// Add data to product array
			$this->product['name'] = $productName;

		// Published, recommended?, Visibility in directory
			$this->product['Properties_0'] = ",1,0,visible,";

		// Short description
			$this->product['Short_description'] = "";	

		// Description
			$this->product['Description'] = "";	

		// Effective date of the sales price", "effective date of the sales price","tax Status","Tax class", " available?", Stocks,"Small quantity in stock", " Delayed order is possible?", "Sold individually?", "Weight (kg)","Length (cm)","Width (cm)","Height (cm)", " Allow feedback from customers?", "Note to purchase", " sale Price
			$this->product['Properties_1'] = ",,,taxable,,1,,,0,0,,,,,1,,,";

		// Base price
			// !!! Only for st-ok.u delete some data before parse
			$pq->find( ".columns-3" )->remove(); 
			
			$element = $pq->find( ".price .woocommerce-Price-amount" ); //Find element
			$productPrice = $element->text(); // Get text from element
			// New product in csv file begin from \n
			$productPrice = "," . $productPrice;
			// Add data to product array
			$this->product['Base_price'] = $productPrice;

		// Categories
			$this->product['Categories'] = "";

		// Tags, shipping Class
			$this->product['Tags_shippingClass'] = ",,,";

		// Parse images
			$this->pQParseImages();

			return $this->product;

		// Download limit","number of days before overdue download",Parent, "Grouped products", Upsale, cross-Sell, "External URL", "button Text", Position
			$this->product['Properties_2'] = ",,,,,,,,,0,";		
		
		// Meta: yikes_woo_products_tabs
			$this->product['Meta_yikes_woo_products_tabs'] = "";	
	}

}

// $pq = new PQ();
// $pq->testPQParseImages();
// $pq->pQCheck();
// $pq->pQGetHtml();
// // Run pQParse
// // $pq->pQParseImages();
// // $pq->pQParseProduct();
// $pq->pQParseLinks();

?>