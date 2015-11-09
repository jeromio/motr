<?php
/**
 * Marquee View Template
 * The wrapper template for the marquee view plugin. 
 */

if ( !defined('ABSPATH') ) { die('-1'); } ?>

<?php do_action( 'tribe_events_before_template' ); ?>

<!-- Tribe Bar -->
<?php tribe_get_template_part( 'modules/bar' ); ?>

<!-- Main Events Content -->
<?php tribe_get_template_part( 'marquee/content' ); ?>

<div class="tribe-clear"></div>

<?php do_action( 'tribe_events_after_template' ) ?>