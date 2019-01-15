<?php 
// Require phpQuery
require "phpQuery.php";

class PQ {
	
	public $folderToSave; // Folder to save and get html
	public $file; // File with html for parsing
	public $csvFolder = "csv/"; // Folder where you can save csv file
	public $csvFile; //  Csv file
	public $imgFolder; // Folder for img files
	
	public $html; // Html what we need to parse

	public $product; // Product data
	
	// Construct
	public function __construct( $folderToSave=NULL, $file=NULL ) {
		// Folder
		$this->folderToSave = $folderToSave; // Folder to save and get html
		$this->file = $file; // File name with html for parsing

		// Make csv filename
		$arrayToReplace = ['links_','.txt']; // We must delete this sufix and prefix to make csv filename
		$csvFilename = str_replace( $arrayToReplace, "", $file ); // del unnesessary sufix and prefix
		$this->csvFile = $csvFilename . ".csv"; // add type to file

		// Make img folder
		$this->imgFolder = $csvFilename; 

	}	

	public function pQMakeFile (){
		if ( !file_exists( $this->csvFolder . $this->csvFile ) ) {
			// Save csv part of the begin into file
			$properties = "ID,Тип,Артикул,Имя,Опубликован,рекомендуемый?,\"Видимость в каталоге\",\"Короткое описание\",Описание,\"Дата начала действия продажной цены\",\"Дата окончания действия продажной цены\",\"Статус налога\",\"Налоговый класс\",\"В наличии?\",Запасы,\"Малое количество на складе\",\"Задержанный заказ возможен?\",\"Продано индивидуально?\",\"Вес (kg)\",\"Длина (cm)\",\"Ширина (cm)\",\"Высота (cm)\",\"Разрешить отзывы от клиентов?\",\"Примечание к покупке\",\"Цена распродажи\",\"Базовая цена\",Категории,Метки,\"Класс доставки\",Изображения,\"Лимит загрузок\",\"Число дней до просроченной загрузки\",Родительский,\"Сгруппированные товары\",Апсейл,Кросселы,\"Внешний URL\",\"Текст кнопки\",Позиция,\"Мета: yikes_woo_products_tabs\" \r\n";

			// We must add this properties only once
			$file = fopen( $this->csvFolder . $this->csvFile, "a+" );
			fwrite( $file, $properties );
			fclose( $file );
		}
	}

	// pQ save text to csv file
	public function pQsave( $product=NULL ) {

		
		

		// Save csv properties into file
		foreach ( $product as $property) {
			// If array 
			if ( is_array( $property ) ) {
				
					// Make string from array 
					$string = implode( ", ", $property ); // ", " - for img hrefs in csv
					// It is need for woocommerce csv file
					$string = "\"" . $string . "\"";

					// Save data to file
					$file = fopen( $this->csvFolder . $this->csvFile, "a+" );
					fwrite( $file, $string );
					fclose( $file );			
				
			}
			else {
				// Save data to file
				$file = fopen( $this->csvFolder . $this->csvFile, "a+" );
				fwrite( $file, $property );
				fclose( $file );
			}
		}	

		// Save csv part of the end into file
		$string = "\r\n";

		$file = fopen( $this->csvFolder . $this->csvFile, "a+" );
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

			// Make folder to save img
			if ( !is_dir( "img/" . $this->imgFolder )) {
				mkdir( "img/" . $this->imgFolder );	
			}
			
			// Save img
			file_put_contents( "img/" . $this->imgFolder . "/" . $imgBaseName, $img );


			// csv
			$this->product['product_images'][] = $src;


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
			$this->product['name'] = "\"" . $productName . "\"";

		// Published, recommended?, Visibility in directory
			$this->product['Properties_0'] = ",1,0,visible,";

		// Short description
			$element = $pq->find( ".woocommerce-product-details__short-description" );
			$ShortDescription = $element->text();
			$this->product['Short_description'] = "\"" . $ShortDescription . "\"";

		// Description
			$element = $pq->find( ".woocommerce-Tabs-panel--description p" );
			$Description = $element->text();
			$this->product['Description'] = ",\"" . $Description . "\"";	

		// Effective date of the sales price", "effective date of the sales price","tax Status","Tax class", " available?", Stocks,"Small quantity in stock", " Delayed order is possible?", "Sold individually?", "Weight (kg)","Length (cm)","Width (cm)","Height (cm)", " Allow feedback from customers?", "Note to purchase", " sale Price
			$this->product['Properties_1'] = ",,,taxable,,1,,,0,0,,,,,1,,,";
											
		// Base price
			// !!! Only for st-ok.u delete some data before parse
			$pq->find( ".columns-3" )->remove(); 
			
			$element = $pq->find( ".price .woocommerce-Price-amount" ); //Find element
			$productPrice = $element->text(); // Get text from element
			// Del unnesessary parts from record of price

			$productPrice = htmlentities( $productPrice );
			$del = [',', '&nbsp;', 'руб.'];
			$productPrice = str_replace( $del, "", $productPrice );

			// Add data to product array
			$this->product['Base_price'] = $productPrice;

		// Categories
			$element = $pq->find( ".posted_in a" );
			$Categories = $element->text();
			$this->product['Categories'] = "," . $Categories;

		// Tags, shipping Class
			$this->product['Tags_shippingClass'] = ",,,";

		// Parse images
			$this->pQParseImages();

		// Download limit","number of days before overdue download",Parent, "Grouped products", Upsale, cross-Sell, "External URL", "button Text", Position
			$this->product['Properties_2'] = ",,,,,,,,,0,";		
		
		// Meta: yikes_woo_products_tabs
			$element = $pq->find( ".woocommerce-Tabs-panel" );

			foreach ($element as $value) {
				$div = pq( $value );	
				$woocommerceTabs[] = $div->html();
			}
			
			// Because all of tabs have the same classes name we must highlight second and 
			// other divs by count
			// Second div will be technic haracteristic and third will be passport 
			// These all for st-ok.ru site
			if ( array_key_exists( 1, $woocommerceTabs ) ) {

				


				// // Begin of tab plugin export/import text
				// $begin = "\"a:1:{i:0;a:3:{s:5:'title';s:51:'Технические характеристики';s:2:'id';s:27:'tehnicheskie-harakteristiki';s:7:'content';s:5429:'";
				// $begin = str_replace( "'", "\"\"", $begin );
				// // Content of tab plugin export/import text

				// $content =  $woocommerceTabs[1];
				// $content = str_replace( "\n", "", $woocommerceTabs[1] ); // Del all \n

				// $content = str_replace( "\"", "\"\"", $content ); 
				// End of tab plugin export/import text
				// $end = "\"\";}}\"";

				// $string = $content;
				// $len = strlen( $string );
				// echo $len;
				// exit();

				// Connect all parts of tab plugin text
				// $woocommerceTab = $begin . $content . $end;

				// We must serialize our data for WP Tabs plugin 
				$woocommerceTab[0]['title'] = "Технические характеристики"; // For serialize
				$woocommerceTab[0]['id'] = "tehnicheskie-harakteristiki"; // For serialize

				// phpQuery here we get <table> with properities. It is content for product tab
				$content = phpQuery::newDocument( $woocommerceTabs[1] );
				$content = $content->find( "table" );
				//$content = $content->html();

				
				$content = str_replace( "\n", "", $content ); // Del all \n
				$woocommerceTab[0]['content'] = $content; // For serialize

				$woocommerceTab = serialize( $woocommerceTab ); // Serialize our data 
				$woocommerceTab = str_replace( "\"", "\"\"", $woocommerceTab ); // It is need for WP Tabs plugin

				$this->product['Meta_yikes_woo_products_tabs'] = "\"" . $woocommerceTab . "\"";	
			}
			if ( array_key_exists( 2, $woocommerceTabs ) ) {
				
			}

			


		// Return data to MainConroller
		return $this->product;
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