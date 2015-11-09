<?php 
/**
 * marquee View Single Event
 * This file contains one event in the marquee view
 */
require_once(dirname(__FILE__) . "/image.php");
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

<!-- Event Title -->
<div class="marquee-event-heading">
	
	<?php do_action( 'tribe_events_before_the_event_title' ) ?>
	<h2 class="entry-title summary">
		<a class="url" href="<?php echo tribe_get_event_link() ?>" title="<?php the_title() ?>" rel="bookmark"><?php the_title() ?></a>
		<?php if ( $has_venue ) : ?>
			@ <?php echo $venue_name; ?>
		<?php endif; ?>
	</h2>
	<?php do_action( 'tribe_events_after_the_event_title' ) ?>

	<?php $width = 1900;
		$height = 400;;
		$classtext = '';
		$titletext = get_the_title();
		//$thumbnail = tribe_event_featured_image(null, 'large');
		echo "title is ". $titletext ."</br>";
		$thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext);
		$thumb = $thumbnail["thumb"]; //this is the URL
		$datetag = tribe_get_start_date(null, false, 'l F jS Y' );// the_event_start_date( null, false, 'l F jS Y' );
		$filename = tribe_get_start_date( null, false, 'm-d-Y' ) . substr($titletext, 0, 4);
		if( !empty($thumb) ) { 
			echo "thumbnail url is ".$thumb, "</br>\n ";
		} else {
			$thumb = "http://motorcomusic.com/images/flyingMsc.jpg";
			echo "thumbnail is empty! Setting to: ".$thumb." \n ";
		}
		$thumb = ".".parse_url($thumb, PHP_URL_PATH);
		echo "thumbnail url is ".$thumb;
		$cost = tribe_get_cost();
		marquee($titletext, $datetag, $cost, $thumb, $filename);
	?>
	
	<!-- Event Meta -->
	<?php do_action( 'tribe_events_before_the_meta' ) ?>
	<div class="tribe-events-event-meta">
		<h3 class="updated published time-details">
			<?php
			global $post;
			if ( !empty( $post->distance ) ) { ?>
				<strong><?php echo '['. tribe_get_distance_with_unit( $post->distance ) .']'; ?></strong>
			<?php } ?>
		</h3>

		<?php if ( $venue_details ) : ?>
			<!-- Venue Display Info -->
			<div class="tribe-events-venue-details">
				<?php  ?>
			</div> <!-- .tribe-events-venue-details -->
		<?php endif; ?>

	</div><!-- .tribe-events-event-meta -->
	<?php do_action( 'tribe_events_after_the_meta' ) ?>
</div> <!-- .marquee-event-heading -->

<!-- Event Content -->
<?php do_action( 'tribe_events_before_the_content' ) ?>
<div class="tribe-marquee-event-description tribe-content">

</div><!-- .tribe-marquee-event-description -->
<?php do_action( 'tribe_events_after_the_content' ) ?>
