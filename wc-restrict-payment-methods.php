<?php
/*
Plugin Name: WC Restrict Payment Methods
Plugin URI: https://github.com/dimdavid/wc-restrict-payment-methods
Description: Restrict Woocommerce payment methods by products. If the product is in the cart, the method will not be shown.
Version: 1.0
Author: Davidson Marques
Author URI: http://dimdavid.wordpress.com/
License: GPL2
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WC_RestrictPaymentMethods' ) ) :

class WC_RestrictPaymentMethods {

	const VERSION = '1.0.0';
	protected static $instance = null;

	private function __construct() {
  
  }
  
  public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		
		return self::$instance;
	}

}

add_action( 'plugins_loaded', array( 'WC_RestrictPaymentMethods', 'get_instance' ) );
	
endif;
