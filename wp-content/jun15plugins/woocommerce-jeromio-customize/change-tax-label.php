<?php
/*
Plugin Name: Jeromio change tax label
Plugin URI: http://woocommerce.com
Description: changes the "incl. tax" label to "incl. taxes & fees"
Version: 0.1
Author: jremy roth
Author URI: http://jeromio.com
Requires at least: 3.1
Tested up to: 3.2

        License: GNU General Public License v3.0
        License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

        /**
         * Localisation
         **/
        load_plugin_textdomain('wc_change_tax_label', false, dirname( plugin_basename( __FILE__ ) ) . '/');
	
	class woocommerce_change_tax_label {
			
		var $update_taxlabel;

		public function __construct() { 

			// Filters for checkout actions
			add_filter( 'woocommerce_countries_inc_tax_or_vat', array(&$this, 'filter_tax_label'), 10, 1 );
			add_filter( 'woocommerce_countries_estimated_for_prefix', array(&$this, 'filter_tax_note'), 10, 1 );			
		} 
		
		function filter_tax_label( $stock_label ) {
			return "incl. Transaction Fees";
		}
		function filter_tax_note( $stock_label ) {
			return "Fees are based on the number of tickets purchased.";
		}


	}
		
	$woocommerce_change_tax_label = new woocommerce_change_tax_label();
	add_action('after_setup_theme', 'remove_admin_bar');
	function remove_admin_bar() {
		if (!current_user_can('administrator') && !is_admin()) {
			  show_admin_bar(false);
		}

	}
	/**
	* Auto Complete all WooCommerce orders.
	* Add to theme functions.php file
	*/
 
	add_action( 'woocommerce_thankyou', 'custom_woocommerce_auto_complete_order' );
	function custom_woocommerce_auto_complete_order( $order_id ) {
    	global $woocommerce;
 
    	if ( !$order_id )
        	return;
    	$order = new WC_Order( $order_id );
    	$order->update_status( 'completed' );
	}
	
	/**
	 * re-enable cost field
	*/
	add_filter( 'tribe_events_admin_show_cost_field', '__return_true', 20 ); 
	/**
	* get rid of the "related events" thing on single post display
	*/
	remove_action('tribe_events_single_event_after_the_meta', 'tribe_single_related_events');
}

