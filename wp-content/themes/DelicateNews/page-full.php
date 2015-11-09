<?php
/*
Template Name: Full Width Page
*/
?>
<?php get_header(); ?>
	<?php if (get_option('delicatenews_integration_single_top') <> '' && get_option('delicatenews_integrate_singletop_enable') == 'on') echo(get_option('delicatenews_integration_single_top')); ?>

	<div id="content" class="clearfix fullwidth">

		<div id="main-area">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<?php get_template_part('includes/breadcrumbs'); ?>

				<div class="post clearfix">
					<h1 class="title"><?php the_title(); ?></h1>

					<?php $width = 238;
						  $height = 238;
						  $classtext = '';
						  $titletext = get_the_title();

						  $thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext);
						  $thumb = $thumbnail["thumb"]; ?>

					<?php if($thumb <> '' && get_option('delicatenews_page_thumbnails') == 'on') { ?>
						<div class="post-thumbnail">
							<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext , $width, $height, $classtext); ?>
							<span class="overlay"></span>
						</div> 	<!-- end .thumbnail -->
					<?php }; ?>

					<?php the_content(); ?>
					<?php wp_link_pages(array('before' => '<p><strong>'.esc_html__('Pages','DelicateNews').':</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
					<?php edit_post_link(esc_html__('Edit this page','DelicateNews')); ?>

				</div> <!-- end .post -->

				<?php if (get_option('delicatenews_show_pagescomments') == 'on') comments_template('', true); ?>
			<?php endwhile; endif; ?>
		</div> <!-- end #main-area -->

<?php get_footer(); ?>