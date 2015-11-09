<?php get_header(); ?>

	<?php if (get_option('delicatenews_featured') == 'on') get_template_part('includes/featured'); ?>

	<?php if (get_option('delicatenews_show_recent_boxes') == 'on') get_template_part( 'includes/recent_scroller'); ?>

	<div id="content" class="clearfix">

		<div id="main-area">
			<h4 id="recent"><?php esc_html_e('Information for you:','DelicateNews'); ?></h4>
			<?php get_template_part('includes/entry','home'); ?>
		</div> <!-- end #main-area -->

		<?php get_sidebar(); ?>

<?php get_footer(); ?>
