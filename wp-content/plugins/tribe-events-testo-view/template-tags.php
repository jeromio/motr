<?php

	/**
	 * testo view conditional tag
	 *
	 * @return bool
	 */
	function tribe_is_testo()  {
		global $wp_query;
		return $wp_query->get( 'eventDisplay' ) == 'testo' ? true : false;
	}

	/**
	 * Get agenda view permalink
	 * 
	 * @param string $set_date
	 * @return string $permalink
	 */
	function tribe_get_testo_permalink( $set_date = null ){
          $tec = TribeEvents::instance();
          $permalink = get_site_url() . '/' . $tec->rewriteSlug . '/testo/';
          return $permalink;
	}
