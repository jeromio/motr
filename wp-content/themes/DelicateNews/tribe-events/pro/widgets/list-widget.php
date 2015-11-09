<?php
/**
 * Events List Widget Template MOTORCO OVERRIDE
 * This is the template for the output of the events list widget. 
 * All the items are turned on and off through the widget admin.
 * There is currently no default styling, which is needed.
 *
 * This view contains the filters required to create an effective events list widget view.
 *
 * You can recreate an ENTIRELY new events list widget view by doing a template override,
 * and placing a list-widget.php file in a tribe-events/widgets/ directory 
 * within your theme directory, which will override the /views/widgets/list-widget.php.
 *
 * You can use any or all filters included in this file or create your own filters in 
 * your functions.php. In order to modify or extend a single filter, please see our
 * readme on templates hooks and filters (TO-DO)
 *
 * @return string
 *
 * @package TribeEventsCalendar
 * @since  2.1 works with 3.6
 * @author Motorco
 *
 */
if ( !defined('ABSPATH') ) die('-1');

// Have taxonomy filters been applied?
$filters = json_decode( $filters, true );

// Is the filter restricted to a single taxonomy?
$single_taxonomy = ( is_array( $filters ) && 1 === count( $filters ) );
$single_term = false;

// Pull the actual taxonomy and list of terms into scope
if ( $single_taxonomy ) foreach ( $filters as $taxonomy => $terms );

// If we have a single taxonomy and a single term, the View All link should point to the relevant archive page
if ( $single_taxonomy && 1 === count( $terms ) ) {
	$link_to_archive = true;
	$link_to_all = get_term_link( absint( $terms[0] ), $taxonomy );
	echo "single taxonomy<br>\n";
}

// Otherwise link to the main events page
else {
	$link_to_archive = false;
	$link_to_all = tribe_get_events_link();
}

// Check if any posts were found
if ( $posts ):
	?>
	<ol class="hfeed vcalendar">
		<?php
		foreach( $posts as $post ) :
			setup_postdata( $post );
			?>
			<li class="<?php tribe_events_event_classes() ?>">

				<?php do_action( 'tribe_events_list_widget_before_the_event_title' ); ?>

				<h3 class="entry-title summary">
					<a href="<?php echo tribe_get_event_link(); ?>" rel="bookmark"><?php the_title(); ?></a>
				</h3>

				<?php do_action( 'tribe_events_list_widget_after_the_event_title' ); ?>

				<?php do_action( 'tribe_events_list_widget_before_the_meta' ) ?>

				<div class="duration">
					<?php echo tribe_events_event_schedule_details(); ?>
				</div>
			<!-- Event cost -->

	<h5 class="entry-cost summary">

		<?php echo tribe_get_cost(); 
			$opener = get_post_meta( get_the_ID(), "_ecp_custom_1", true);//tribe_get_custom_field('_ecp_custom_1');
			if( strlen($opener) > 0 )
			{
				echo " with ";
				echo $opener;
			}
			echo " in the ";
			echo tribe_get_venue(); ?>
		
	</h5>
				<?php do_action( 'tribe_events_list_widget_after_the_meta' ) ?>

			</li>
		<?php
		endforeach;
		?>
	</ol><!-- .hfeed -->

	<p class="tribe-events-widget-link">
		<a href="<?php esc_attr_e( esc_url( $link_to_all ) ) ?>" rel="bookmark">
			<?php _e( 'View More&hellip;', 'tribe-events-calendar' ) ?>
		</a>
	</p>
<?php
// No Events were Found
else:
	?>
	<p><?php _e( 'There are no upcoming featured events.', 'tribe-events-calendar' ) ?></p>
<?php
endif;
