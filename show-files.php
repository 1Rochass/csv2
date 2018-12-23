<?php 

$files = scandir( "parse-directory" );

foreach ($files as $value) {

	if ( $value == "." || $value == "..") {
	
	}
	else {
		echo "<input type='radio' name='file' id='" . $value . "' value='" . $value . "' ><span class='pq-radio'>" . $value . "</span>";	
	}
}
 ?>