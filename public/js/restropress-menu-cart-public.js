(function ($) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	 


})(jQuery);

jQuery(document).ready(function ($) {

	setInterval(function() {
		var data = {
			'action': 'restropress_menu_cart_get_cart_details',
		};
		$.ajax({
			url: ajax_object.ajax_url,
			type: 'post',
			data: data,
			success: function ( response ) {
				// Access the properties in the response object
				
				var subtotal 	= response.subtotal;
				var cartDetails = response.cart_details;
				var menuOption 	= response.menu_items_option;
				var checkoutUrl = response.checkout_url;
				var getCurrency = response.currency_sign;
				var check_cart_data = response.check_cart_data;
				var dData 		= '';
				
				if ( check_cart_data == true ) {

					if ( menuOption == 'items-only' ) {
						dData += "<a class='rpress-checkout-cart'  href='"+ checkoutUrl+"'> " + cartDetails + " Items </a>";
	
						jQuery(".rpmenu-basket").show();		
						 
					}
					if ( menuOption == 'price-only') {
	
						dData +=  "<a class='rpress-checkout-cart' href='"+ checkoutUrl + "'>" + getCurrency + " " + subtotal + "</a>";
						jQuery(".rpmenu-basket").show();
						 
					}
					if ( menuOption == 'both-items-price') {
						dData += "<a class='rpress-checkout-cart' href='"+ checkoutUrl + "'>" + cartDetails + " Items "+ getCurrency +" "+ subtotal + "</a>";
						jQuery(".rpmenu-basket").show();
						 
					}

					$('.cart-details').html( dData );


				} else {

					if ( menuOption == 'items-only' ) {
						dData += "<a class='rpress-checkout-cart'  href='"+ checkoutUrl+"'> " + cartDetails + " Items </a>";
	
						jQuery(".rpmenu-basket").show();		
						 
					}
					if ( menuOption == 'price-only') {
	
						dData +=  "<a class='rpress-checkout-cart' href='"+ checkoutUrl + "'>" + getCurrency + " " + subtotal + "</a>";
						jQuery(".rpmenu-basket").show();
						 
					}
					if ( menuOption == 'both-items-price') {
						dData += "<a class='rpress-checkout-cart' href='"+ checkoutUrl + "'>" + cartDetails + " Items "+ getCurrency +" "+ subtotal + "</a>";
						jQuery(".rpmenu-basket").show();
						 
					}
					
					if ( cartDetails == '0' ) {
	
						jQuery(".menu_cart_heading").hide();
	
					} else {
	
						$('.cart-details').html( dData );
	
						jQuery(".menu_cart_heading").show();
					}
				}
				
				
				
				
			},
			error: function (error) {

				console.error('error:', error);

			}
		});
	}, 1000); // Check every 1 second (adjust as needed)
});