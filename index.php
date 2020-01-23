<?php


// init basic constants
define('PDF_GALLERY_ROOT',$plugin_cf["pdf_gallery"]["pdf_gallery_pdf"]);
define ('PDF_GALLERY_BASE', $pth["folder"]["plugin"]);


// init autoloader
spl_autoload_register(function ($path) {

	if ($path && strpos($path, "pdf_gallery\\") !== false) {

		$path = "classes/" . str_replace("pdf_gallery\\", "", strtolower($path)) . ".php";
		$path = str_replace("\\", "/", $path);

		include_once $path; 
	}
});


// init multilingual texts
\pdf_gallery\Session::load();
\pdf_gallery\Text::init($plugin_tx["pdf_gallery"]);
\pdf_gallery\Action::exec();


// main plugin function
// path: path to pdf directory
// groups: list of comma separated groups to grant access to edit function
function pdf_gallery($path = false, $groups = false) {

	global $plugin_cf;

	pdf_gallery\Main::init($plugin_cf['pdf_gallery'], $groups);

	pdf_gallery\Main::load($path);

	return pdf_gallery\Main::render();
}

?>