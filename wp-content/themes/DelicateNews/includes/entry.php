<?php $i = 1; ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<?php $thumb = '';
		$width = 238;

		if (get_option('delicatenews_blog_style') == 'false') $height = 96;
		else $height = 238;

		if (get_option('delicatenews_blog_style') == 'false') $classtext = 'thumb alignleft';
		else $classtext = '';

		$titletext = get_the_title();

		$thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext);
		$thumb = $thumbnail["thumb"]; ?>

	<?php if (get_option('delicatenews_blog_style') == 'false')	{ ?>
		<div class="entry<?php if ($i%2==0) echo(' second'); ?>">
	<?php } else { ?>
		<div class="post clearfix bbottom">
	<?php }; ?>

			<h3 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<?php if ((get_option('delicatenews_blog_style') == 'on') && get_option('delicatenews_postinfo2') ) { ?>
						<p class="post-meta"><?php esc_html_e('Posted','DelicateNews'); ?> 
						<?php if (in_array('author', get_option('delicatenews_postinfo2'))) { ?> <?php esc_html_e('by','DelicateNews'); ?> 
						<?php the_author_posts_link(); ?><?php }; ?>
						<?php if (in_array('categories', get_option('delicatenews_postinfo2'))) { ?> 
						<?php esc_html_e('in','DelicateNews'); ?> <?php the_category(', ') ?><?php }; ?>
						<?php if (in_array('comments', get_option('delicatenews_postinfo2'))) { ?> | 
						<?php comments_popup_link(esc_html__('0 comments','DelicateNews'), esc_html__('1 comment','DelicateNews'), '% '.esc_html__('comments','DelicateNews')); ?><?php }; ?></p>
			<?php }; ?>

			<?php if ($thumb <> '' && get_option('delicatenews_thumbnails_index') == 'on') { ?>
				<div class="<?php if (get_option('delicatenews_blog_style') == 'on') echo('post-'); ?>thumbnail">
					<span class="date">
						<span><?php echo(the_time('M d, y')); ?></span>
					</span> <!-- end .date -->

					<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext , $width, $height, $classtext); ?>
					<span class="overlay"></span>

					<?php if (get_option('delicatenews_blog_style') == 'false') { ?>
						<p class="meta-info"><?php esc_html_e('Posted','DelicateNews'); ?> <?php esc_html_e('by','DelicateNews'); ?> <?php the_author_posts_link(); ?> <?php esc_html_e('in','DelicateNews'); ?> <?php the_category(', ') ?></p>
					<?php }; ?>
				</div> 	<!-- end .thumbnail -->
			<?php }; ?>

			<?php if (get_option('delicatenews_blog_style') == 'on') the_content("");
			else { ?>
				<p><?php truncate_post(335); ?></p>
			<?php }; ?>

			<a href="<?php the_permalink(); ?>" class="readmore"><span><?php esc_html_e('read more','DelicateNews'); ?></span></a>

		</div> <!-- end .entry .post -->
		<?php if ($i % 2 == 0) echo('<div class="clear"></div>'); ?>

	<?php $i++; ?>
<?php endwhile; ?>
	<div class="clear"></div>
	<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); }
	else { ?>
		 <?php get_template_part('includes/navigation'); ?>
	<?php } ?>

<?php else : ?>
	<?php get_template_part('includes/no-results'); ?>
<?php endif; ?>
