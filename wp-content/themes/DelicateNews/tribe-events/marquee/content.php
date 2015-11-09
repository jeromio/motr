<?php
/**
 * marquee View Content Template
 * The content template for the marquee view. 
 */

if ( !defined('ABSPATH') ) { die('-1'); } ?>

<div id="tribe-events-content" class="tribe-events-list tribe-events-marquee">

	<!-- marquee Title -->
	<?php do_action( 'tribe_events_before_the_title' ); ?>
	<h2 class="tribe-events-page-title"><?php echo tribe_get_events_title() ?></h2>
	<?php do_action( 'tribe_events_after_the_title' ); ?>

	<!-- Notices -->
	<?php tribe_events_the_notices() ?>

	<!-- marquee Header -->
    <?php do_action( 'tribe_events_before_header' ); ?>
	<div id="tribe-events-header" <?php tribe_events_the_header_attributes() ?>>
	</div><!-- #tribe-events-header -->
	<?php do_action( 'tribe_events_after_header' ); ?>

	<!-- Events Loop -->
	<?php if ( have_posts() ) : ?>
		<?php do_action( 'tribe_events_before_loop' ); 
			//clear away the old pics in the folder
			array_map('unlink', glob("marquee/*")); ?>
		<?php tribe_get_template_part( 'marquee/loop' ) ?>
		<?php do_action( 'tribe_events_after_loop' ); ?>
	<?php endif; ?>

	<!-- marquee Footer -->
	<?php do_action( 'tribe_events_before_footer' ); ?>
	<div id="tribe-events-footer">

	</div><!-- #tribe-events-footer -->
	<?php do_action( 'tribe_events_after_footer' ) ?>

</div><!-- #tribe-events-content -->
