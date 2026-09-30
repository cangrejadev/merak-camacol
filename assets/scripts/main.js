jQuery(document).ready(function () {
	//Calculte offset
	var barBottom = -100;
	if (jQuery("body").hasClass("admin-bar")) {
		barBottom = barBottom + 32;
	}

	//Waypoints
	jQuery("body #main_content").waypoint(
		function (direction) {
			switchMenuScroll(direction, "down");
		},
		{
			offset: barBottom,
		}
	);

	// Menu mobile
	jQuery("#navbar__toogle").on("click", function () {
		jQuery(this).toggleClass("active");
		jQuery(this).parents(".navbar--mobile").toggleClass("open");
			if (jQuery(this).hasClass("active")) {
				jQuery(".navbar__menu").stop(true, true).slideDown()
			} else {
				jQuery(".navbar__menu").stop(true, true).slideUp()
			}
	});
	jQuery(".navbar--mobile #dropdown-item .arrow").on("click", function () {
		jQuery(this).parent().find("ul").stop(true, true).slideToggle();
		jQuery(this).toggleClass("active");
	});
	jQuery('.nav-toggle-submenu').on('click', function() {
		jQuery(this).toggleClass('toggled');
		if(jQuery(this).hasClass('toggled')) {
			jQuery(this).next().stop(true, true).slideDown()
		} else {
			jQuery(this).next().stop(true, true).slideUp()
		}
	});


	// Fadesearch
	jQuery("#fadesearch .close").on("click", function () {
		jQuery("#fadesearch").fadeOut(400);
		jQuery('body').removeClass('stop-scrolling');
	});
	jQuery("#fadesearch").on("click", function () {
		jQuery("#fadesearch").fadeOut(400);
		jQuery('body').removeClass('stop-scrolling');
	});
	jQuery("#fadesearch form").on("click", function (event) {
		event.stopPropagation();
	});
	jQuery(".search-button").on("click", function () {
		jQuery("#fadesearch").fadeIn(400);
		jQuery("#fadesearch input.clave").focus();
		jQuery('body').addClass('stop-scrolling');
	});

	// Detectar scroll
	var direction = "none";

	jQuery(window).on("wheel", function (event) {
		new_direction = "down";
		if (event.originalEvent.deltaY < 0) {
			new_direction = "up";
		}

		if (direction != new_direction) {
			direction = new_direction;
			if (direction == "up") {
				jQuery(".navbar-brand + .navbar").removeClass("scroll-down");
			} else {
				jQuery(".navbar-brand + .navbar").addClass("scroll-down");
			}
		}
	});

	// Agregar una clase al hacer clic en un boton
	// jQuery(".accordion-header").on("click", function () {
	// 	jQuery(this).toggleClass("selected");
	// });
	// Agregar una clase al hacer clic en un boton
	jQuery(".filter-button span").on("click", function () {
		jQuery(".container-fluid .filter-category").toggleClass("active");
		jQuery(".container-fluid .filter-results").toggleClass("col-9");
	});

	// Filtro de tienda
	var filtrar = false;
	jQuery(".filter-category .form-check-input").on("click", function () {
		if (filtrar) {
			clearTimeout(filtrar);
		}
		filtrar = setTimeout(function () {
			jQuery(".filter-category form").submit();
		}, 1500);
	});

	var rdoActiveIndex = -1;
	jQuery(".filter-category .form-check-input[type=radio]:checked").each(function () {
		rdoActiveIndex = jQuery(this).val();
	});
	jQuery(".filter-category .form-check-input[type=radio]").on("click", function (e) {
		var oldActiveIndex = rdoActiveIndex;
		rdoActiveIndex = jQuery(this).val();

		// Reset checking status
		jQuery(".filter-category .form-check-input[type=radio]").prop("checked", false);

		if (oldActiveIndex != rdoActiveIndex) {
			jQuery(".filter-category .form-check-input[type=radio][value=" + rdoActiveIndex + "]").prop("checked", true);
		} else {
			// reset index
			rdoActiveIndex = -1;
		}
	});

	// Spinner
	var options = {};
	jQuery(".qty").each(function () {
		jQuery(this).attr("type", "text");
		options.value = jQuery(this).attr("value") ? parseInt(jQuery(this).attr("value")) : 1;
		options.min = jQuery(this).attr("min") ? parseInt(jQuery(this).attr("min")) : 0;
		options.max = jQuery(this).attr("max") ? parseInt(jQuery(this).attr("max")) : 10;
		jQuery(this).spinner(options);

		// Atender cambio
		jQuery(this).on("update", function () {
			// Tomar los valores
			var adults = jQuery(".personas input[name=adults]").val();
			var text_adults_plural = jQuery(".personas input[name=adults]").attr("data-plural");
			var text_adults_singular = jQuery(".personas input[name=adults]").attr("data-singular");

			// var childs = jQuery('.personas input[name=childs]').val();
			// var text_childs_plural = jQuery('.personas input[name=childs]').attr('data-plural');
			// var text_childs_singular = jQuery('.personas input[name=childs]').attr('data-singular');

			var text = "";
			text = adults == 1 ? "1 " + text_adults_singular : adults + " " + text_adults_plural;
			// text = text + " &middot; ";
			// text = (childs == 1) ? text + " 1 " + text_childs_singular : text + childs + " " + text_childs_plural;

			jQuery(".div_personas div.input").html(text);
		});
	});
});

// Function to attend the change trigered by the waypoint
function switchMenuScroll(direction, direction_to_black) {
	if (direction == direction_to_black) {
		jQuery("header").addClass("header--fixed-top");
	} else {
		jQuery("header").removeClass("header--fixed-top");
	}
}
