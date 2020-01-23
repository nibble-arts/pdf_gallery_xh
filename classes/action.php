<?php

namespace pdf_gallery;

class Action {

	public static function exec() {

		// check session token
		if (Session::session("xh_csrf_token") == Session::param("token")) {

			switch (Session::param("action")) {

				// delete pdf file
				case "del_pdf":

					if (file_exists(Session::param("name")) && unlink(Session::param("name"))) {
						Message::success(Text::file_deleted());
					}

					else {
						Message::failure(Text::file_delete_failure());
					}
					break;
			}
		}
	}
}

?>