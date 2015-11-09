<?php
/**
 * @for Flyer View Template
 * This file contains hooks and functions required to set up the flyer view.
 *
 * @package TribeFlyer
 * @author MotorMike
 *
 */

if ( ! defined( 'ABSPATH' ) ) 
     die('-1');

if( !class_exists('Tribe_Events_Flyer_Template')){
	class Tribe_Events_Flyer_Template extends Tribe_Template_Factory {

		protected $body_class = 'tribe-events-flyer';

		/**
		 * Constructor
		 *
		 * @return void
		 * @author Modern Tribe
		 **/
		public function __construct() {
			parent::__construct();
			wp_enqueue_style( 'tribe-flyer-view', get_stylesheet_directory_uri() . "/tribe-events/flyer.css", array(), '0.1', 'screen' );
			wp_enqueue_script( 'tribe-flyer-view-scripts', get_stylesheet_directory_uri() . "/tribe-events/flyer-view.js", array('jquery'), null, true );
		}

		/**
		 * Set up hooks for this template
		 *
		 * @return void
		 * @since 1.0
		 **/
		public function hooks() {
			parent::hooks();
			add_filter( 'tribe_get_events_title',  array( $this, 'the_title' ) );
		}

		/**
		 * Organize and reorder the events posts according to time slot
		 *
		 * @return void
		 * @since 1.0
		 **/
		public function setup_view() {

			global $wp_query;

			if ( $wp_query->have_posts() ) {
				foreach ( $wp_query->posts as &$post ) {
					$post->timeslot = tribe_event_is_all_day( $post->ID )
						? __( 'All Day', 'tribe-events-calendar-pro' )
						: $post->timeslot = tribe_get_start_date( $post, false, 'D M j \@ g:i A,' ); //Start-date formating = DAY MONth @ 8:00 PM,
				}
				$wp_query->rewind_posts();
			}
		}



		/**
		 * Filter the view title for Flyer view
		 *
		 * @return void
		 * @author 
		 **/
		function the_title( $title ) {
			if ( tribe_is_flyer() ) {
				global $wp_query;
				$title = sprintf( '%s %s',
				__('Flyer starting ', 'tribe-event-flyer-view'), 
				Date("l, F jS Y", strtotime($wp_query->get('start_date')
				)));
			}
	    	return $title;
    	}
	}
}