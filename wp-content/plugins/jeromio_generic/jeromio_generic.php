<?php
/*
Plugin Name: Jeromio Generic Changes
Plugin URI: http://jeromio.com
Description: Couple different tweaks
Version: 0.1
Author: jeremy roth
Author URI: http://jeromio.com
Requires at least: 3.1
Tested up to: 3.6

        License: GNU General Public License v3.0
        License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/


	
	if ($post_type != 'tribe_events') {
		return;
	} else {
		$start_date = get_post_meta($post_id, '_EventStartDate', true);
		update_post_meta($post_id, '_EventEndDate', $start_date);
	}

add_action('pre_post_update', 'rbm_remove_event_date', 99);

/**
 * Helps to change the default start/end times for new event.
 */
class Change_Tribe_Default_Event_Times
{
        const TWELVEHOUR = 12;
        const TWENTYFOURHOUR = 24;
 
        protected $start = 9;
        protected $end = 11;
        protected $mode = self::TWELVEHOUR;
        protected $mode_set = false;
 
        /**
         * Provide the desired default start and end hours in 24hr format (ie 15 = 3pm).
         *
         * @param $start_hour
         * @param $end_hour
         */
        public function __construct($start_hour, $end_hour) {
                $this->settings($start_hour, $end_hour);
                $this->add_filters();
        }
 
 
        protected function settings($start_hour, $end_hour) {
                $this->start = $this->safe_hour($start_hour);
                $this->end = $this->safe_hour($end_hour);
        }
 
 
        protected function add_filters() {
                add_filter('tribe_get_hour_options', array($this, 'change_default_time'), 10, 3);
                add_filter('tribe_get_meridian_options', array($this, 'change_default_meridian'), 10, 3);
        }
 
 
        protected function set_mode() {
                if ( $this->mode_set ) return;

                if ( strstr( get_option('time_format', $this->time_format() ), 'H' ) ){
                        $this->mode = self::TWENTYFOURHOUR;
                }
 
                $this->mode_set = true;
        }

        protected function safe_hour($hour) {
                $hour = absint($hour);
                if ($hour < 0) $hour = 0;
                if ($hour > 23) $hour = 23;
                return $hour;
        }
 
 
        public function change_default_time($hour, $date, $isStart) {
                $this->set_mode();
                if ('post-new.php' !== $GLOBALS['pagenow']) return $hour; // Only intervene if it's a new event
 
                if ($isStart) return $this->corrected_time($this->start);
                else return $this->corrected_time($this->end);
        }
 
 
        /**
         * If working in the 12hr clock, converts the hour appropriately.
         *
         * @param $hour
         * @return int
         */
        protected function corrected_time($hour) {
                $this->set_mode();
                if (self::TWENTYFOURHOUR === $this->mode) return $hour;
                if ($hour > 12) return $hour - 12;
                return $hour;
        }
 
 
        public function change_default_meridian($meridian, $date, $isStart) {
                if ('post-new.php' !== $GLOBALS['pagenow']) return $meridian; // Only intervene if it's a new event
 
                $meridian = 'am';
                if ($isStart && 12 <= $this->start) $meridian = 'pm';
                if (! $isStart && 12 <= $this->end) $meridian = 'pm';
 
                if ( strstr( get_option('time_format', $this->time_format() ), 'A' ) )
                        $meridian = strtoupper($meridian);
 
                return $meridian;
        }
        protected function time_format() {
                if ( class_exists( 'Tribe__Events__Date_Utils' ) ) return Tribe__Events__Date_Utils::TIMEFORMAT;
                if ( class_exists( 'TribeDateUtils') ) return TribeDateUtils::TIMEFORMAT;
                return 'g:i A';
        }
}
 
// If you mostly deal with night time soirees you could set the default start time
// to 7pm and end time to 11pm - but remember to do so with the 24hr clock
new Change_Tribe_Default_Event_Times(20, 20);

/**
 * WooCommerce Extra Feature
 * --------------------------
 *
 * Add custom fee to cart automatically
 *

function woo_add_cart_fee() {
 
  global $woocommerce;
	
  $woocommerce->cart->add_fee( __('Service Charge', 'woocommerce'), 1 );
	
}
add_action( 'woocommerce_before_calculate_totals', 'woo_add_cart_fee' );
 */

/** 
  *  Events Reporting
  */

function add_events_reporting_menus () {
	if ( ( ! defined( 'TRIBE_DISABLE_TOOLBAR_ITEMS' ) || ! TRIBE_DISABLE_TOOLBAR_ITEMS ) && ! is_network_admin() ) {
		global $wp_admin_bar;		
		$wp_admin_bar->add_menu(
			array(
				 'id'     => 'tribe-events-view-calendar',
				 'title'  => __( 'Reports', 'tribe-events-reports' ),
				 'href'   => plugins_url( 'reports.php', __FILE__ ),
				 'parent' => 'tribe-events-group'
			 )
		);
	}
}	
//add_action( 'wp_before_admin_bar_render', 'add_events_reporting_menus' , 20 );
/**
 * WooCommerce Extra Feature
 * --------------------------
 *
 * Add another email recipient to all WooCommerce emails
 *
 */
function woo_cc_all_emails() {
  return 'Bcc: tickets@motorcomusic.com' . "\r\n";
}
add_filter('woocommerce_email_headers', 'woo_cc_all_emails' );


//FIx for quantity buttons in cart
add_action( 'wp_enqueue_scripts', 'wcqi_enqueue_polyfill' );
function wcqi_enqueue_polyfill() {
    wp_enqueue_script( 'wcqi-number-polyfill' );
}
