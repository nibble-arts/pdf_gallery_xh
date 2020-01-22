<?php


define('PDF_GALLERY_ROOT',$plugin_cf["pdf_gallery"]["pdf_gallery_pdf"]);


include_once 'pdf_gallery.php';


function pdf_gallery($path = '') {

	global $plugin_cf;

	$pdf_gallery = new pdf_gallery\Plugin_Pdf_Gallery($plugin_cf['pdf_gallery']);

	$pdf_gallery->load($path);

	return $pdf_gallery->render();
}

?>