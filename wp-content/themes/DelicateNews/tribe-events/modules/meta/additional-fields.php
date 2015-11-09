<?php
/**
 * Single Event Meta (Additional Fields) Template
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe-events/modules/meta/details.php
 *
 * @package TribeEventsCalendarPro
 */

if ( ! isset( $fields ) || empty( $fields ) || ! is_array( $fields ) ) {
	return;
}
?>

<div class="tribe-events-meta-group tribe-events-meta-group-other">
	<!--<h3 class="tribe-events-single-section-title"> <?php _e( 'Other', 'tribe-events-calendar-pro' ) ?> </h3>-->
	<dl>
		<?php /* JER */	
		foreach ( $fields as $name => $value ): 
			echo "<dt>";		
			if ( $name != "Featured" ) { 
				echo "$name  </dt>		<dd class=\"tribe-meta-value\">  $value </dd>";
			} else {
				echo "</dt>";
			}
		endforeach; ?>
		
		<?php
		echo tribe_get_event_categories(
			get_the_id(), array(
				'before'       => '',
				'sep'          => ', ',
				'after'        => '',
				'label'        => null, // An appropriate plural/singular label will be provided
				'label_before' => '<dt>',
				'label_after'  => '</dt>',
				'wrap_before'  => '<dd class="tribe-events-event-categories">',
				'wrap_after'   => '</dd>'
			)
		);
		?>
	
	</dl>
</div>