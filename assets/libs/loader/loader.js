jQuery(document).ready(function() {
	// ¿Tiene home alguna situación especial para carga?
	if(jQuery('body').hasClass('home')) {
		jQuery('body').imagesLoaded({
			background: true
		}, function() {
			jQuery(".loader").fadeOut();
		});
	} else {
		jQuery('body').imagesLoaded({
			background: true
		}, function() {
			jQuery(".loader").fadeOut();
		});
	}
});
