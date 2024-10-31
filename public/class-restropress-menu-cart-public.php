<?php

class Restropress_Menu_Cart_Public {
	//$data = get_option( 'rpress_settings' );

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name 	= $plugin_name;
		$this->version 		= $version;
		$data 				= get_option( 'rpress_settings' );
		$enable_setting 	= isset( $data['enable_menu_cart' ] ) ? true : false;
		if( $enable_setting ) {
			add_filter( 'wp_nav_menu_items', [ $this, "add_menu_cart" ], 10, 2 );
			add_action( 'wp_ajax_nopriv_restropress_menu_cart_get_cart_details', [ $this,"restropress_menu_cart_get_cart_details"] );
			add_action( 'wp_ajax_restropress_menu_cart_get_cart_details', [  $this,"restropress_menu_cart_get_cart_details"] );	 
		}
		
	}

	public function restropress_menu_cart_get_cart_details() {

		$data 				= get_option( 'rpress_settings' );
		$cart_details 		= count( rpress_get_cart_content_details() );
		$subtotal 			= round(rpress_get_cart_subtotal(), 2);
		$currency_sign		= rpress_get_currency();
		$menu_items_option 	= $data['display_menu'];
		$check_cart_data 	= isset( $data['always_display_cart'] ) ? true : false; 
		$checkout_url 		= esc_url( rpress_get_checkout_uri() );
		
		$response = array(
			'subtotal' 			=> $subtotal,
			'cart_details' 		=> $cart_details,
			'menu_items_option' => $menu_items_option,
			'checkout_url' 		=> $checkout_url,
			'currency_sign'		=> $currency_sign,
			'check_cart_data'	=> $check_cart_data
		);
		if ( $check_cart_data && $subtotal > 0  || $check_cart_data  > 0 ) {

			wp_send_json( $response );

		} else {

			wp_send_json( $response );
		}
		
	}
	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Restropress_Menu_Cart_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Restropress_Menu_Cart_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/restropress-menu-cart-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Restropress_Menu_Cart_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Restropress_Menu_Cart_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/restropress-menu-cart-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php') ) );

	}
     
	public function add_menu_cart( $items, $args ) {
		$data               = get_option( 'rpress_settings' );
		$menu_icon_option   = $data['display_icon'];
		$icon               = '';
	
		// Define the paths to the icons
		$icons = [
			1 => 'img/basket.svg',
			2 => 'img/opencart.svg',
			3 => 'img/shopping-bag.svg',
			4 => 'img/shopping-cart.svg',
			5 => 'img/basket-3.svg',
		];
	
		// Get the appropriate icon based on the menu icon option
		if ( isset( $icons[ $menu_icon_option ] ) ) {
			$cart_icon = plugins_url( $icons[ $menu_icon_option ], __FILE__ );
			$response  = wp_remote_get( $cart_icon );
	
			if ( is_wp_error( $response ) ) {
				// Handle error appropriately
				$icon_content = '<!-- Error loading icon -->';
			} else {
				$icon_content = wp_remote_retrieve_body( $response );
			}
		}
	
		$items  .= '<li class="menu-item menu_cart_heading">'
				. '<label>'
				. '<span class="rpmenu-basket">'
				. $icon_content
				. '<span class="cart-details">'
				. '</span>'
				. '</span>'
				. '</label>'
				. '</li>';
	
		return $items;
	}	

}