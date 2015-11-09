<?php
/**
 * Single Event Template
 * A single event. This displays the event title, description, meta, and
 * optionally, the Google map for the event.
 * 
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/single-event.php
 *
 * @package TribeEventsCalendar
 * @since  2.1
 * @author Modern Tribe Inc.
 *
*/

if ( !defined('ABSPATH') ) { die('-1'); }

$event_id = get_the_ID();

?>
	<div id="content" class="clearfix">
	
		<div id="main-area">
<!--<div id="tribe-events-content" class="tribe-events-single"> -->

	
<div class="post clearfix">
	<!-- Notices -->
	<?php tribe_events_the_notices() ?>

	<?php the_title( '<h1 class="title">', '</h1>' ); ?>

	<div class="tribe-events-schedule updated published tribe-clearfix">
		<h3><?php echo tribe_events_event_schedule_details(); ?></h3>
		<?php echo tribe_events_recurrence_tooltip(); ?>
		<?php  $cost = tribe_get_cost(); ?>
			<span class="tribe-events-divider">|</span>
			<span class="tribe-events-cost"><?php echo tribe_get_cost( null, true ) ?></span>	
			<?php function int($s){return(int)preg_replace('/[^\-\d]*(\-?\d*).*/','$1',$s);} //stolen from example in docs
			if ( int($cost) > 0 ) :  ////JER Added tickets link ?>

				<span class="gettickets" ><A href="#tickets">Get Tickets</A></span>

		<?php endif; ?>
	</div>
<?php /*-- Jeremy Removed entirely
	<!-- Event header -->
	<div id="tribe-events-header" <?php tribe_events_the_header_attributes() ?>>
		<!-- Navigation -->
		<h3 class="tribe-events-visuallyhidden"><?php _e( 'Event Navigation', 'tribe-events-calendar' ) ?>jer hidden nav</h3>
		<ul class="tribe-events-sub-nav">
			<li class="tribe-events-nav-previous"><?php tribe_the_prev_event_link( '&laquo; %title%' ) ?></li>
			<li class="tribe-events-nav-next"><?php tribe_the_next_event_link( '%title% &raquo;' ) ?></li>
		</ul><!-- .tribe-events-sub-nav -->
	</div><!-- #tribe-events-header -->
*/ ?>
	<?php while ( have_posts() ) :  the_post(); ?>
 	<!--	<div id="post-<?php the_ID(); ?>" <?php post_class('vevent'); ?>> -->
			<!-- Event featured image -->
	        <?php 
                $thumb = tribe_event_featured_image( null, 'full' ) ;
do_action( 'log', 'tribe event featured_image', 'woo', $thumb );
				$classtext = '';
                $width = 500;
                $height = 238;
                $titletext = get_the_title();
				/* function get_thumbnail($width=100, $height=100, $class='', $alttext='', $titletext='', $fullpath=false, $custom_field='', $post='') */
				$thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext);
do_action( 'log', 'single-event using DN get_thumbnail', 'woo', $thumbnail );
				$thumb = $thumbnail["thumb"];
	        ?>
			<div class="post-thumbnail">
                <span class="date">
					<span><?php echo(tribe_get_start_date( null, false, 'M d' )); ?></span>
				</span> <!-- end .date -->
				<?php
			/* DelicateNews/epanel/custom_functions.php
			function print_thumbnail($thumbnail = '', $use_timthumb = true, $alttext = '', $width = 100, $height = 100, $class = '', $echoout = true, $forstyle = false, $resize = true, $post='', $et_post_id = '') {
	print_thumbnail($thumb, false, $titletext, $width, $height, $titletext); */
		    print_thumbnail($thumb, 
					 true, 
					 $titletext, 
					 $width, 
					 $height, 
					 "thumbnail",
					 true, false, false
					); 
			?>
                 <span class="overlay"></span>
			</div>  <!-- end .thumbnail -->
			<!-- SINGLE -->
			<!-- Event content -->
			<?php do_action( 'tribe_events_single_event_before_the_content' ) ?>

 			<div class="tribe-events-single-event-description tribe-events-content entry-content description">
			<?php the_content(); ?>
			</div><!-- .tribe-events-single-event-description -->
		
			<?php do_action( 'tribe_events_single_event_after_the_content' ) ?>
			</div> <!-- end #main-area -->



			<!-- Event meta -->
			<?php 
			//JER changed to make password protection work
			if ( ! post_password_required() ) {
				do_action( 'tribe_events_single_event_before_the_meta' ); 
			
				 //echo tribe_events_single_event_meta() 
			
				do_action( 'tribe_events_single_event_after_the_meta' ); 
			} 
			?>
			
			</div><!-- .hentry .vevent -->
			<?php get_sidebar(); ?>
		<?php if( get_post_type() == TribeEvents::POSTTYPE && tribe_get_option( 'showComments','no' ) == 'yes' ) { comments_template(); } ?>
	
	<?php endwhile; ?>
	
	<!-- Event footer -->
    <div id="tribe-events-footer">
		<!-- Navigation -->
		
	</div><!-- #tribe-events-footer -->

</div><!-- #tribe-events-content -->

