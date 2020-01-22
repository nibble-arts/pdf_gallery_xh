function pdf_gallery_init(text) {

	// add user delete window
	jQuery(".pdf_gallery_delete").click(function (e) {

		e.preventDefault();

		r = confirm(text);

		if (r) {
			window.location = e.currentTarget.href;
		}
	});
}