<?php 
/**
 * List View Single Event
 * This file contains one event in the list view
 *
 * file location: DelicateNews/tribe-events/list/single-event.php
 *
 * @package TribeEventsCalendar
 * @since  3.0
 * @author Motorco
 *
 */
if ( !defined('ABSPATH') ) { die('-1'); } ?>

<?php 

// Setup an array of venue details for use later in the template
$venue_details = array();

if ($venue_name = tribe_get_meta( 'tribe_event_venue_name' ) ) {
	$venue_details[] = $venue_name;	
}

if ($venue_address = tribe_get_meta( 'tribe_event_venue_address' ) ) {
	$venue_details[] = $venue_address;	
}
// Venue microformats
$has_venue = ( $venue_details ) ? ' vcard': '';
$has_venue_address = ( $venue_address ) ? ' location': '';
?>


<!-- Schedule & Recurrence Details -->
<h3 class="updated published time-details">
    <?php
        echo tribe_events_event_schedule_details();
        echo ", ";
        echo tribe_get_cost(); ?>
    <?php //echo tribe_events_recurrence_tooltip() ?>
</h3>

<!-- Event Title -->
<?php do_action( 'tribe_events_before_the_event_title' ) ?>
<h1 class="tribe-events-list-event-title summary">
    <a class="url" href="<?php echo tribe_get_event_link() ?>" title="<?php the_title() ?>" rel="bookmark">
        <?php the_title() ?>
    </a>
</h1>
<?php do_action( 'tribe_events_after_the_event_title' ) ?>

<!-- Event Meta -->
<?php do_action( 'tribe_events_before_the_meta' ) ?>
<div class="tribe-events-event-meta <?php echo $has_venue . $has_venue_address; ?>">

<!-- Event cost -->
	<h3>
		<?php $opener = get_post_meta( get_the_ID(), "_ecp_custom_1", true);//tribe_get_custom_field('_ecp_custom_1');
			if( strlen($opener) > 0 )
			{
				echo " w/ ";
				echo $opener;
			}
			echo " in the ";
			echo tribe_get_venue(); ?>
	</h3>


</div><!-- .tribe-events-event-meta -->
<?php do_action( 'tribe_events_after_the_meta' ) ?>

<!-- .tribe-events-list-event-description -->
<?php do_action( 'tribe_events_after_the_content' ) ?>

