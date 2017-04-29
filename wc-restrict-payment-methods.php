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
		if(is_admin()){
			$this->admin_init();
		}
		$this->init();
	}
	
	public function admin_init(){
		add_action('woocommerce_product_write_panel_tabs', array($this, 'restrict_tab'));
		add_action('woocommerce_product_data_panels', array($this, 'restrict_data'));
		add_action('save_post', array($this, 'save_restrict_methods'), 10, 3);
	}
	
	public function init(){
		add_filter('woocommerce_available_payment_gateways', array($this, 'remove_product_payment_method'), 15, 2);
	}
  
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		
		return self::$instance;
	}
	
	public function add_product_field(){
		
	}
	
	public function restrict_tab(){
	
?>
<li class="restrict_options restrict_tab">
	<a href="#restrict_payment_methods">Restrict Payment Methods</a>
</li>
<?php

	}
	
	public function restrict_data(){
	
?>
<script>
function addRestrictMethod(){
	var newMeth = document.getElementById('available_methods').value;
	if(newMet != ''){
		
	}
}
</script>
<div id="restrict_payment_methods" class="panel wc-metaboxes-wrapper">
	<div class="toolbar toolbar-top">
		<?php echo $this->available_methods_options(); ?>
	</div>
</div>
<?php

	}

	public function available_methods_options(){
		$rms = $this->load_restrict_methods();
		$h = '<select multiple id="restrict_methods" name="restrict_methods[]" class="attribute_taxonomy">';
		$payment_gateways = WC()->payment_gateways->payment_gateways();
		foreach($payment_gateways as $pg){
			if(in_array($pg->id, $rms)){
				$s = ' selected ';
			} else {
				$s = '';
			}
			$h .= '<option value="' . $pg->id . '"' . $s . '>' . $pg->title . '</option>';
		}
		$h .= '</select>';
		return $h;
	}
	
	public function load_restrict_methods(){
		$rms = get_post_meta(get_the_ID(), 'restrict_methods', true);
		return $rms;
	}
	
	public function save_restrict_methods($post_id){
		global $post;
		$rm = array();
		if($post->post_type == 'product'){
			if(isset($_POST['restrict_methods'])){
				foreach($_POST['restrict_methods'] as $prm){
					$rm[] = $prm;
				}
				update_post_meta($post_id, 'restrict_methods', $rm);
			}
		}
	}
	
	public function log($msg){
		error_log(print_r($msg, true), 0);
	}
	
	public function remove_product_payment_method($_available_gateways){
		global $woocommerce;
		$item = $woocommerce->cart->get_cart();
		foreach ($item as $it){
			$to_remove = get_post_meta($it['product_id'], 'restrict_methods', true);
			foreach($to_remove as $tr){
				if(isset($_available_gateways[$tr])){
					unset($_available_gateways[$tr]);
				}
			}
		}
		return $_available_gateways;
	}
	
}

add_action( 'plugins_loaded', array( 'WC_RestrictPaymentMethods', 'get_instance' ) );
	
endif;
