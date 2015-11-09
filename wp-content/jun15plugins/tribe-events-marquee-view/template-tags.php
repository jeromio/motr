<?php

	/**
	 * Marquee view conditional tag
	 *
	 * @return bool
	 */
	function tribe_is_marquee()  {
		global $wp_query;
		return $wp_query->get( 'eventDisplay' ) == 'marquee' ? true : false;
	}

	/**
	 * Get agenda view permalink
	 * 
	 * @param string $set_date
	 * @return string $permalink
	 */
	function tribe_get_marquee_permalink( $set_date = null ){
          $tec = TribeEvents::instance();
          $permalink = get_site_url() . '/' . $tec->rewriteSlug . '/marquee/';
          return $permalink;
	}