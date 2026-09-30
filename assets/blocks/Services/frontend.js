import * as $ from 'jquery';

$(document).ready(function ($) {
	jQuery(".carousel-servicios").each(function () {
		const slider = jQuery(this);
		const columns = jQuery(slider).attr('data-columns');
		const autoplay = (jQuery(slider).attr('data-autoplay') == '1') ? true : false;
		const dots = (jQuery(slider).attr('data-dots') == '1') ? true : false;
		const nav = (jQuery(slider).attr('data-nav') == '1') ? true : false;
		jQuery(slider).owlCarousel({
			autoplay: autoplay,
			margin: 10,
			dots: dots,
			nav: nav,
			loop: autoplay,
			responsiveClass: true,
			responsive: {
				0: {
					items: Math.min(columns, 1),
				},
				600: {
					items: Math.min(columns, 3),
				},
				1000: {
					items: columns
				}
			}
		});
	});
});
