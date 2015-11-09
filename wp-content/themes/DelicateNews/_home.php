<?php get_header(); ?>

	<?php if (get_option('delicatenews_featured') == 'on') include(TEMPLATEPATH . '/includes/featured.php'); ?>
	
	<?php if (get_option('delicatenews_show_recent_boxes') == 'on') include(TEMPLATEPATH . '/includes/recent_scroller.php'); ?>

	<div id="content" class="clearfix">
		
		<div id="main-area">
			<h4 id="recent"><?php _e('Recent Posts','DelicateNews'); ?></h4>
			
			<?php $i = 1; ?>
			<?php if (get_option('delicatenews_duplicate') == 'false') {
				$args=array(
					   'showposts'=>get_option('delicatenews_homepage_posts'),
					   'post__not_in' => $ids,
					   'paged'=>$paged,
					   'category__not_in' => get_option('delicatenews_exlcats_recent'),
				);
			} else {
				$args=array(
				   'showposts'=>get_option('delicatenews_homepage_posts'),
				   'paged'=>$paged,
				   'category__not_in' => get_option('delicatenews_exlcats_recent'),
				);
			};
			query_posts($args); ?>
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<?php include(TEMPLATEPATH . '/includes/entry.php'); ?>
				<?php $i++; ?>
			<?php endwhile; ?>
				<div class="clear"></div>
				<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); }
				else { ?>
					 <?php include(TEMPLATEPATH . '/includes/navigation.php'); ?>
				<?php } ?>
				
			<?php else : ?>
				<?php include(TEMPLATEPATH . '/includes/no-results.php'); ?>
			<?php endif; wp_reset_query(); ?>
					
		</div> <!-- end #main-area -->
			
		<?php get_sidebar(); ?>	
			
<?php get_footer(); ?>			
