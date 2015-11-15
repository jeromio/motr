<!-- Start Featured -->
<div id="featured" class="clearfix">

	<?php
	$arr = array();
	$i=1;
	$postsperpage = 5;
	$width = 249;
	$height = 158;
	$width_small = 104;
	$height_small = 104;

	$featured_cat = get_option('delicatenews_feat_cat');
	$featured_num = (int) get_option('delicatenews_featured_num');

	$events = tribe_get_events( array
                               ('showposts' => $featured_num,
								'eventDisplay'   => 'list',
								'posts_per_page' => $postsperpage,
								'post_type' => "tribe_events",
								'post_status' => "publish",
								'meta_query' => array(
									array(
										'key' => '_ecp_custom_2',
										'value' => 'true',
									)
								)
								/*'tax_query' => array( array(
									'taxonomy' =>  'featured'                                
									))*/
								)

	);
	//Now what if there's less than posts_per_page events flagged as featured?
	//in that case, let's just pull up other upcoming events that aren't flagged featured:
	$retnum = $postsperpage - count( $events );
	if ( $retnum > 0 )
	{
		//query for more posts bc we want a full box
		$moreevents = tribe_get_events( array
                               ('showposts' => $featured_num,
								'eventDisplay'   => 'list',
								'posts_per_page' => $retnum,
								'post_type' => "tribe_events",
								'post_status' => "publish",
								'meta_query' => array(
									array(
										'key' => '_ecp_custom_2',
										'value' => '',
										)
									)
								)

		);
		$events = array_merge($events, $moreevents);
	}
	foreach( $events as $event )
	{
		global $post;
		$arr[$i]["title"] = wp_trim_words( $event->post_title, 40, "..." ); //truncate_title(50,false);
		$arr[$i]["fulltitle"] = wp_trim_words ($event->post_title, 250, "..."); //truncate_title(250,false);

		$arr[$i]["excerpt"] = wp_trim_words ($event->post_content, 260 ); //truncate_post(330,false);
		$arr[$i]["permalink"] = get_permalink($event);
do_action( 'log', 'featured events loop event permalink', 'woo', $event->permalink );
		$arr[$i]["postinfo"] = esc_html__("Posted by", "DelicateNews")." ". get_the_author_meta('display_name') . esc_html__(' on ','DelicateNews') . get_the_time(get_option('delicatenews_date_format')) . ' | ' . '<a href="'. esc_attr( $arr[$i]["permalink"] ) . '#comments' . '">' . esc_html($post->comment_count) . ' ' . esc_html__('comments','DelicateNews') . '</a>';
		if ( $i <3 ) {	
			$img_size = 'full';
		} else {
			$img_size = 'medium';
		}			
		//Jeremy nixed this $arr[$i]["thumbnail"] = tribe_event_featured_image( $event->ID, $img_size);
		//in favor of this:
		$arr[$i]["thumbnail"] = wp_get_attachment_image_src( get_post_thumbnail_id( $event->ID ), $img_size );
			//echo "___________ $event->ID FeaturedImg is ". $arr[$i]["thumbnail"][0] ." \n<br>";
			/* some gobbledeegook that messes shit up: 
			 if ($i < 3) $arr[$i]["thumbnail"] = get_thumbnail($width,$height,'',$arr[$i]["fulltitle"],$arr[$i]["fulltitle"]);
			 else $arr[$i]["thumbnail"] = get_thumbnail($width_small,$height_small,'',$arr[$i]["fulltitle"],$arr[$i]["fulltitle"]);
	
			$arr[$i]["thumb"] = $arr[$i]["thumbnail"]["thumb"];
			$arr[$i]["use_timthumb"] = false ; // $arr[$i]["thumbnail"]["use_timthumb"];
			*/
		$arr[$i]['post_id'] = (int) $event->ID;
		$arr[$i]["date"] = tribe_get_start_date( $event, false, 'M d' );
       	        $arr[$i]["startdate"] = tribe_get_start_date( $event, false, 'l, F j, Y, g:i a' );
               	$arr[$i]["starttime"] = tribe_get_start_date( $event, false, 'g:i a' );
       	       	$arr[$i]["doortime"] = strtotime( '-1 hour', strtotime( $arr[$i]["starttime"] ) );
       		$arr[$i]["doortime"] = date( 'g:i a', $arr[$i]["doortime"] ); 
               	$arr[$i]["eventcost"] = tribe_get_cost($event->ID);
		$arr[$i]["opening_act"] = tribe_get_custom_fields("Opener", $event->ID);
		$i++;
		} // endwhile; 
		wp_reset_query();	?>

	<div id="description">
		
		<?php
		 $maxcount = count($events);
		 if ( $featured_num > $maxcount ) $featured_num = $maxcount;
		 for ($i = 1; $i <= $featured_num; $i++) { 
		   if($arr[$i]){ ?>
			<div class="slide">
				<h2 class="title">
					<a href="<?php echo esc_url($arr[$i]["permalink"]); ?>">
					<?php echo($arr[$i]["title"]); ?></a></h2>
				<p><?php echo ($arr[$i]["excerpt"]); ?></p>

				<a href="<?php echo esc_url($arr[$i]["permalink"]); ?>" class="readmore">
					<span><?php esc_html_e('read more','DelicateNews'); ?></span></a>
				<div class="clear"></div>
 <!-- Mike edited this START then Jeremy edited more :) -->
                               <p class="event_info"><strong>Date:</strong> <?php echo $arr[$i]["date"]; ?>
					<strong>Show Time: </strong> <?php echo $arr[$i]["starttime"]; ?> 
			       <!--hour -1 doesn't always apply	<strong>Doors at: </strong> <?php echo $arr[$i]["doortime"]; ?> 	-->
				</p>
                        <p class="event_info"><strong>Cost:</strong> <?php echo $arr[$i]["eventcost"]; ?></p>
                        <?php $opener = $arr[$i]["opening_act"];
						if ( $opener ) {
							echo '<p class="event_info"><strong>With:</strong> ' .$opener ;
						} ?>     
				<!-- Jeremy says postinfo is stupid <p class="meta"><?php echo($arr[$i]["postinfo"]); ?></p> -->
			</div> <!-- end .slide -->
 		   
		<?php }}; ?>

	</div> <!-- end #description -->

	<div id="controllers">
		<div class="alignleft">
			<?php 
			 $two = 2;
			 if ( $two > $maxcount ) $two = $maxcount;
			 for ($i = 1; $i <= $two; $i++) { ?>
				<a href="#" <?php if ($i==1) echo('class="active"'); if ($i==$two) echo('class="second"'); ?> rel="<?php echo esc_attr( $i ); ?>">
					<?php print_thumbnail( array(
						'thumbnail' 	=> $arr[$i]["thumbnail"][0]/*["thumb"]*/,
						'use_timthumb' 	=> true /*$arr[$i]["thumbnail"]["use_timthumb"]*/,
						'alttext'		=> $arr[$i]["fulltitle"],
						'width'			=> (int) $width,
						'height'		=> (int) $height,
						'et_post_id'	=> $arr[$i]['post_id'],
					) ); ?>

					<span class="overlay"></span>
					<span class="date"><?php echo $arr[$i]["date"]; ?></span>
				</a>
			<?php }; ?>
		</div>

		<div class="alignright">
			<?php 
				for ($i = 3; $i <= 5; $i++) { ?>
				<?php if ($i <= $featured_num) { ?>
					<a href="#" rel="<?php echo esc_attr( $i ); ?>">
						<?php if($arr[$i]['post_id']) { 
							print_thumbnail( array(
							'thumbnail' 	=> $arr[$i]["thumbnail"][0]/*["thumb"]*/,
							'use_timthumb' 	=> true /*$arr[$i]["thumbnail"]["use_timthumb"]*/,
							'alttext'		=> $arr[$i]["fulltitle"],
							'width'			=> (int) $width_small,
							'height'		=> (int) $height_small,
							'et_post_id'	=> $arr[$i]['post_id'],));
							echo '<span class="overlay"></span>';
							echo '<span class="date">';
							echo $arr[$i]["date"]; 
							echo "</span>";
						} else {echo "FAIL!!";} ?>

					</a>
				<?php }; ?>
			<?php }; ?>
		</div>
	</div> <!-- end #controllers -->

</div> <!-- end #featured -->
<!-- End Featured -->
