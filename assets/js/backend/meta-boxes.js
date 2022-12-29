jQuery(document).ready(function ($) {
	"use strict";

	// Show/hide settings for post format when choose post format
	var $format = $('#post-formats-select').find('input.post-format'),
		$formatBox = $('#post-format-settings');

	$format.on('change', function () {
		var type = $format.filter(':checked').val();

		$formatBox.hide();
		if ($formatBox.find('.rwmb-field').hasClass(type)) {
			$formatBox.show();
		}

		$formatBox.find('.rwmb-field').slideUp();
		$formatBox.find('.' + type).slideDown();
	});
	$format.filter(':checked').trigger('change');

	// Show/hide settings for custom layout settings
	$('#custom_layout').on('change', function () {
		if ($(this).is(':checked')) {
			$('.rwmb-field.custom-layout').slideDown();
		}
		else {
			$('.rwmb-field.custom-layout').slideUp();
		}
	}).trigger('change');

	// Show/hide settings for custom layout settings
	$('#page_header_custom_layout').on('change', function () {
		if ($(this).is(':checked')) {
			$('.rwmb-field.page-header-text-color, .rwmb-field.page-header-bg, .rwmb-field.page-header-parallax').slideDown();
		}
		else {
			$('.rwmb-field.page-header-text-color, .rwmb-field.page-header-bg, .rwmb-field.page-header-parallax').slideUp();
		}
	}).trigger('change');

	// Show/hide settings for header settings
	$('#custom_header').on('change', function () {
		if ($(this).is(':checked')) {
			$('.rwmb-field.disable-header-transparent, .rwmb-field.disable-header-sticky, .rwmb-field.header-text-color, .rwmb-field.header-color, .rwmb-field.header-border').slideDown();
		}
		else {
			$('.rwmb-field.disable-header-transparent, .rwmb-field.disable-header-sticky, .rwmb-field.header-text-color, .rwmb-field.header-color, .rwmb-field.header-border').slideUp();
		}
	}).trigger('change');


	$( '#header_text_color' ).on( 'change', function() {
		var headerTextColor = $(this).val();

		if ( headerTextColor == 'custom' ) {
			$('.rwmb-field.header-color').slideDown();
		} else {
			$('.rwmb-field.header-color').slideUp();
		}

	}).trigger('change');

	// Show/hide settings for template settings
	$('#page_template').on('change', function () {

		if ($(this).val() == 'template-homepage.php' ||
			$(this).val() == 'template-coming-soon-page.php' ||
			$(this).val() == 'template-home-boxed.php' ||
			$(this).val() == 'template-home-left-sidebar.php' ||
			$(this).val() == 'template-home-no-footer.php'
		) {
			$('#page-header-settings').hide();
		} else {
			$('#page-header-settings').show();
		}

		if ( $(this).val() == 'template-home-left-sidebar.php' ) {
			$('#home-left-sidebar-settings').show();
			$('#header-settings').hide();
		} else {
			$('#home-left-sidebar-settings').hide();
			$('#header-settings').show();
		}

		if ( $(this).val() == 'template-home-no-footer.php' ) {
			$('#page-background-settings').hide();
			$('#home-full-slider-settings').show();
		} else {
			$('#page-background-settings').show();
			$('#home-full-slider-settings').hide();
		}

	}).trigger('change');
});
