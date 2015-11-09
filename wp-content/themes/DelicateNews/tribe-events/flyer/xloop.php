<?php 
/**
 * Flyer View Loop
 * This file sets up the structure for the flyer loop
 */

if ( !defined('ABSPATH') ) { die('-1'); } ?>

<?php 

global $more, $wp_query;
$more = false;
$current_timeslot = null;

?>

<div class="tribe-events-loop hfeed vcalendar">
	<div class="tribe-events-day-time-slot">

	<?php while ( have_posts() ) : the_post(); global $post; ?>
		<?php do_action( 'tribe_events_inside_before_loop'); ?>

		<?php if ( $current_timeslot != $post->timeslot ) : $current_timeslot = $post->timeslot; ?>
			</div><!-- .tribe-events-day-time-slot -->

			<div class="tribe-events-day-time-slot">
				<h3><?php echo $current_timeslot; ?>&nbsp;<?php echo tribe_get_cost(); ?></h3> <!--  -->
		<?php endif; ?>
		


		<!-- Event  -->
		<div id="post-<?php the_ID() ?>">
			<?php tribe_get_template_part( 'flyer/single', 'event' ) ?>
		</div><!-- .hentry .vevent -->


		<?php do_action( 'tribe_events_inside_after_loop' ); ?>
	<?php endwhile; ?>

	</div><!-- .tribe-events-day-time-slot -->
</div><!-- .tribe-events-loop -->





