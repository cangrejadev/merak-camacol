import * as $ from 'jquery';

$(document).ready(function ($) {
	jQuery(".wp-block-mrk-accordions .accordion").each(function () {
		if (jQuery(this).attr('data-unfolded') != 'true') {
			jQuery(this).find('.accordion__content').slideUp(0);
		} else {
			jQuery(this).addClass('open');
			jQuery(this).find('.accordion__header').addClass('open');
			jQuery(this).find('.accordion__content').addClass('open');
		}

		jQuery(this).find('.accordion__header').on("click", function () {
			const parent = jQuery(this).parent();
			jQuery(parent).toggleClass("open").addClass('selected');

			// Si hace parte de un grupo de acordeones
			if (
				jQuery(parent).parent().hasClass("wp-block-mrk-accordions") &&
				jQuery(parent).parent().attr("data-group-toggle") == 'true'
			) {
				jQuery(parent).parent().find(".accordion.open:not(.selected)").each(function () {
					jQuery(this).removeClass('open');
					jQuery(this).children().removeClass('open');
					jQuery(this).find('.accordion__content').slideUp();
				});
			}
			jQuery(parent).removeClass('selected');
			jQuery(parent).children().toggleClass("open");
			
			jQuery(parent).find('.accordion__content').slideToggle();

		});
	});
});
