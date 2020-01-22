<?php

namespace pdf_gallery;

class Plugin_Pdf_Gallery {

	private $options;
	private $path;
	private $pdf_path;
	private $thumb_path;
	private $files;

	public function __construct($options) {

		$this->options = $options;
		$this->files = [];
	}


	// load pdf files from path
	public function load($path) {

		$this->path = $path;
		$this->pdf_path = $this->options['pdf_gallery_pdf'] . $path . '/';
		$this->thumb_path = $this->options['pdf_gallery_thumb'] . $path . '/';

		$files = scandir($this->pdf_path);

		foreach ($files as $file) {

			// is pdf file
			if (strtolower(pathinfo($file)['extension']) == 'pdf') {

				$this->parse_file($file);
			}
		}
	}


	private function parse_file($file) {

		array_push($this->files, ["file" => $file, "text" => pathinfo($file, PATHINFO_FILENAME)]);

		$this->sort($this->options['sort']);
	}



	// sort entries ASC/DESC
	private function sort($sort) {

		switch (strtolower($sort)) {

			case "asc":
				arsort($this->files);
				break;

			case "desc":
				krsort($this->files);
				break;
		}
	}




	// render pdf list
	public function render() {

		$o = '<div class="pdf_gallery_list">';

		foreach ($this->files as $pdf) {

			$o .= '<div class="pdf_gallery_issue">';
				$o .= '<a href="' . $this->pdf_path . $pdf['file'] . '" target="_blank">';


					$o .= '<div class="pdf_gallery_title">';
						$o .= $pdf['text'];
					$o .= '</div>';

					// create image from pdf
					$o .= '<div>';

						if (class_exists('Imagick')) {
							$o .= $this->get_from_pdf($pdf['file']);
						}

						// load image
						else {
							// $o .= "keine Vorschau möglich";
							$o .= $this->get_thumb($pdf['file']);
						}
					$o .= '</div>';

				$o .= '</a>';
			$o .= '</div>';


		}

		$o .= '</div>';

		return $o;
	}


	private function get_from_pdf($path) {

		// create thumb directory if not exists
		if (!file_exists($this->pdf_path . 'pdf_thumb/')) {
			if (!mkdir ($this->pdf_path . 'pdf_thumb/')) {
				// $o = 'ERROR, can´t create thumb directory';
			}

		}

		else {
			
			$thumb_path = $this->pdf_path . 'pdf_thumb/' . pathinfo($path, PATHINFO_FILENAME) . '.jpg';

			// create thumbnail if don't exist
	// TODO clear thumbnails in admin
			if (!file_exists($thumb_path)) {
		
				$im = new \Imagick(); 
				$im->setResolution(72, 72);
				$im->readimage($this->pdf_path . $path . '[0]');
				$im->setImageFormat('jpeg'); 
				$im->writeImage($thumb_path);
				$im->clear(); 
				$im->destroy();
		
			}

			$o = '<img class="pdf_gallery_thumb" src="' . $thumb_path . '">';
		}

		return $o;
	}


	private function get_thumb($path) {

		return $o;
	}

}
?>