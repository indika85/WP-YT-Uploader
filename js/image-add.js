jQuery(document).ready( function($) {
	console.log('Loaded');
	//console.log('Profile ID: ' + image_adder.image_url);
	
	var header_section = $('div.site-banner:first');
	if(header_section && image_adder.do_change){
		header_section.css('background-image', 'none');
		header_section.append('<div class="header-custom-bg" style="background-image: url(' + image_adder.image_url + ') "></div>');
	}
	if(image_adder.show_years){
		var breadcrumbs = $('.breadcrumbs');
		if(breadcrumbs){
			breadcrumbs.prepend('<div class="years-append">' + image_adder.year_string+ '</div>');
		}
	}
})
