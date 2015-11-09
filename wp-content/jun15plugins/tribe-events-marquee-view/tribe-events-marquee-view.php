<?php
/*
 Plugin Name: The Events Calendar: marquee View
 Description: This plugin adds an marquee view to your Tribe The Events Calendar suite.
 Version: 0.1
 Author: jeromio, Inc.
 Author URI: http://jeromio.com
 Text Domain: 'tribe-event-marquee-view'
 License: GPLv2 or later

Copyright 2009-2014 by jeromio and the contributors

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

if ( ! class_exists( 'TribeEvents' ) )
	return;

/* Include our other files */
require_once( 'template-tags.php' );
require_once( 'tribe-marquee-view-template-class.php' );

/* Add Hooks */

// hook in to add the rewrite rules
add_action( 'generate_rewrite_rules', 'tribe_events_marquee_add_routes' );

// specify the template class
add_filter( 'tribe_events_current_template_class', 'tribe_events_marquee_setup_template_class' );

// load the proper template for marquee view
add_filter( 'tribe_events_current_view_template', 'tribe_events_marquee_setup_view_template' );

// inject marquee view into events bar & (display) settings
add_filter( 'tribe-events-bar-views', 'tribe_events_marquee_setup_in_bar', 39, 1 );

/**
 * Add the marquee view rewrite rule
 *
 * @param $wp_rewrite the WordPress rewrite rules object
 * @return void
 **/
function tribe_events_marquee_add_routes( $wp_rewrite ) {

	// Get the instance of the TribeEvents plugin, and the rewriteSlug that the plugin uses
	$tec = TribeEvents::instance();
	$tec_rewrite_slug = trailingslashit( $tec->rewriteSlug );

	// create new rule for the marquee view
	$newRules = array(
		$tec_rewrite_slug . 'marquee/?$' => 'index.php?post_type=' . TribeEvents::POSTTYPE . '&eventDisplay=marquee',
	);

     // Add the new rule to the global rewrite rules array
	$wp_rewrite->rules = $newRules + $wp_rewrite->rules;
}

/**
 * Specify the template class for marquee view
 *
 * @param $class string containing the current template classname
 * @return string
 **/
function tribe_events_marquee_setup_template_class( $class ) {
	if ( tribe_is_marquee() ) {
		$class = 'Tribe_Events_Marquee_Template';
	}
	return $class;
}

/**
 * Specify the template for marquee view
 *
 * @param $template string containing the current template file
 * @return string
 **/
function tribe_events_marquee_setup_view_template( $template ){
	// marquee view
	if( tribe_is_marquee() ) {
		$template = TribeEventsTemplates::getTemplateHierarchy('marquee');
	}
	return $template;
}


/**
 * Register the marquee view alongside the other views
 *
 * @param $views array of registered views
 * @return array
 **/
function tribe_events_marquee_setup_in_bar( $views ) {
     $views[] = array( 
          'displaying' => 'marquee',
          'anchor'     => 'Marquee',
          'url'        => tribe_get_marquee_permalink() 
     );
     return $views;
}