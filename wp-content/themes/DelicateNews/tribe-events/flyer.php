<?php
/**
 * Flyer View Template
 * The wrapper template for the sample flyer view plugin. 
 */

if ( !defined('ABSPATH') ) { die('-1'); } ?>


<!-- Main Events Content -->
<?php tribe_get_template_part( 'flyer/content' ); ?>

<div class="tribe-clear"></div>

<?php do_action( 'tribe_events_after_template' ) ?>