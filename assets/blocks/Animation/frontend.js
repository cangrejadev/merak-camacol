function itemsShowup() {
	var top = window.innerHeight * 0.9;
	//Do a first check
	var t = jQuery(this).scrollTop();

	jQuery("[data-animation]").each(function () {
		animate(this, top, t);
	})

	jQuery(window).scroll(function () {
		var t = jQuery(this).scrollTop();
		jQuery("[data-animation]").each(function () {
			animate(this, top, t);
		})
	})
}

function animate(element, top, t) {
	delay = parseInt(jQuery(element).attr('data-animationdelay'));
	if (delay && delay > 0) {
		setTimeout(function () {
			t > jQuery(element).offset().top - top ? jQuery(element).addClass("showing") : jQuery(element).removeClass("showing");
		}, delay);
	} else {
		t > jQuery(element).offset().top - top ? jQuery(element).addClass("showing") : jQuery(element).removeClass("showing");
	}
}

jQuery(document).ready(function () {
	itemsShowup();
});
