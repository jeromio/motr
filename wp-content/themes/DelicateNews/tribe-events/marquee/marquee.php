<?php
require_once(dirname(__FILE__) . "/image.php");


get_header();
ini_set('max_execution_time', 300);
?>

		<? //phpinfo();
		//clear away the old pics in the folder
			array_map('unlink', glob("marquee/*")); ?>
		<!-- Here comes the posts loop -->
		<?php while ( have_posts() ) : the_post(); ?>

			<div id="post-<?php the_ID() ?>" class="tec-event post clearfix<?php echo $alt ?>">
				<div style="clear:both;"></div>
				        <?php if ( is_new_event_day() ) : ?>
						<h4 class="event-day"><?php echo the_event_start_date( null, false ); ?></h4>
					<?php endif; ?>
					<?php the_title('<h2 class="entry-title"><a href="' . get_permalink() . '" title="' . the_title_attribute('echo=0') . '" rel="bookmark">', '</a></h2>'); ?>
					<!-- Post Thumbnail here -->
					<?php $width = 1900;
                                              $height = 400;;
                                              $classtext = '';
                                              $titletext = get_the_title();
                                              $thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext);
                                              $thumb = $thumbnail["thumb"]; //this is the URL
						$datetag = tribe_get_start_date(null, false, 'l F jS Y' );// the_event_start_date( null, false, 'l F jS Y' );
						$filename = tribe_get_start_date( null, false, 'm-d-Y' ) . substr($titletext, 0, 4);
                        if( !empty($thumb) ) { 
							echo "Have Thumbnail <br>/n ";
					      } else {
							$thumb = "http://motorcomusic.com/images/flyingMsc.jpg";
							echo "thumbnail is empty! Setting to: ".$thumb." <BR>\n ";
					      }
					      $thumb = ".".parse_url($thumb, PHP_URL_PATH);
						echo "Thumbnail url is ".$thumb."<BR>\n";
			            $cost = tribe_get_cost();
						marquee($titletext, $datetag, $cost, $thumb, $filename);
					?>
					
	<?php $alt = ( empty( $alt ) ) ? ' alt' : '';?> 
		<?php endwhile; // posts ?>




	


<?php /* For custom template builders...
	   * The following init method should be called before any other loop happens.
	   */
$wp_query->init(); ?>
<?php get_footer();
ini_set('max_execution_time', 30); ?>
