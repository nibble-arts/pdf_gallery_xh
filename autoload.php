<?php

// init class autoloader
function autoload () {

	spl_autoload_register(function ($path) {
	
		if ($path && strpos($path, "pdf_gallery\\") !== false) {

			$path = "classes/" . str_replace("pdf_gallery\\", "", strtolower($path)) . ".php";

			include_once $path; 
		}
	});
}
	
?>