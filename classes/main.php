<?php

namespace pdf_gallery;

class Main {

	private static $options;
	private static $path;
	private static $pdf_path;
	private static $thumb_path;
	private static $files;

	private static $edit;

	public static function init ($options, $groups = false) {

		self::$edit = false;

		self::$options = $options;
		self::$files = [];

		// memberaccess integration
		if ($groups && class_exists("\ma\Access") && \ma\Access::has_rights($groups)) {
			self::$edit = true;
		}
	}


	// load pdf files from path
	public static function load($path) {

		self::$path = $path;
		self::$pdf_path = self::$options['pdf_gallery_pdf'] . $path . '/';
		self::$thumb_path = self::$options['pdf_gallery_thumb'] . $path . '/';

		$files = scandir(self::$pdf_path);

		foreach ($files as $file) {

			// is pdf file
			if (strtolower(pathinfo($file, PATHINFO_EXTENSION)) == 'pdf') {

				self::parse_file($file);
			}
		}
	}


	private static function parse_file($file) {

		array_push(self::$files, ["file" => $file, "text" => pathinfo($file, PATHINFO_FILENAME)]);

		if (isset(self::$options["sort"])) {
			self::sort(self::$options['sort']);
		}
	}



	// sort entries ASC/DESC
	private static function sort($sort) {

		switch (strtolower($sort)) {

			case "asc":
				arsort(self::$files);
				break;

			case "desc":
				krsort(self::$files);
				break;
		}
	}




	// render pdf list
	public static function render() {

		global $onload, $su;

		// return script include
		$o = '<script type="text/javascript" src="' . PDF_GALLERY_BASE . 'script/edit.js"></script>';
		$onload .= "pdf_gallery_init('" . Text::delete_confirm() . "');";

		$o .= Message::render();

		$o .= '<div class="pdf_gallery_list">';

		foreach (self::$files as $pdf) {

			$o .= '<div class="pdf_gallery_issue">';

				if (self::$edit) {

					$o .= '<div class="pdf_gallery_edit">';
						$o .= '<a class="pdf_gallery_delete" href="?' . $su . '&action=del_pdf&name=' . self::$pdf_path . $pdf["file"] . '&token=' . Session::session("xh_csrf_token") . '">';

							$o .= '<img src="' . PDF_GALLERY_BASE . '/images/del.gif">';
						$o .= '</a>';
					$o .= '</div>';
				}

				$o .= '<a href="' . self::$pdf_path . $pdf['file'] . '" target="_blank">';


					$o .= '<div class="pdf_gallery_title">';
						$o .= $pdf['text'];
					$o .= '</div>';

					// create image from pdf
					$o .= '<div>';

						if (class_exists('Imagick')) {
							$o .= self::get_from_pdf($pdf['file']);
						}

						// load image
						else {
							// $o .= "keine Vorschau möglich";
							$o .= self::get_thumb($pdf['file']);
						}
					$o .= '</div>';

				$o .= '</a>';
			$o .= '</div>';


		}

		$o .= '</div>';

		return $o;
	}


	private static function get_from_pdf($path) {

		// create thumb directory if not exists
		if (!file_exists(self::$pdf_path . 'pdf_thumb/')) {
			if (!mkdir (self::$pdf_path . 'pdf_thumb/')) {
				// $o = 'ERROR, can´t create thumb directory';
			}

		}

		else {
			
			$thumb_path = self::$pdf_path . 'pdf_thumb/' . pathinfo($path, PATHINFO_FILENAME) . '.jpg';

			// create thumbnail if don't exist
	// TODO clear thumbnails in admin
			if (!file_exists($thumb_path)) {
		
				$im = new \Imagick(); 
				$im->setResolution(72, 72);
				$im->readimage(self::$pdf_path . $path . '[0]');
				$im->setImageFormat('jpeg'); 
				$im->writeImage($thumb_path);
				$im->clear(); 
				$im->destroy();
		
			}

			$o = '<img class="pdf_gallery_thumb" src="' . $thumb_path . '">';
		}

		return $o;
	}


	private static function get_thumb($path) {

		return false;
	}

}
?>