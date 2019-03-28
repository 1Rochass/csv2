jQuery(document).ready(function(){

	// Show files
	showFiles();
	function showFiles() {

		var data = {
		showFiles: 'showFiles',
		}

	    $.post("downloader.php", data, viewResult );
	 
		function viewResult(response) {
		   jQuery('#files').html(response);
		}
	} 
	
	


	jQuery('#files').on('click', '.deleteFiles', deleteFiles);

		function deleteFiles(){

			var tagId = jQuery(this).attr('id');
			// alert(deleteFiles);
			var data = {
				deleteFiles: tagId,
			}

			jQuery.post('downloader.php', data, viewResult);

			function viewResult(response) {
				jQuery('#alerts').html(response);

				// Reload showFiles()
				showFiles();
			}
		}
	
});

	
