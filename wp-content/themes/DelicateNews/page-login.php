<?php
/*
Template Name: Login Page
*/
?>
<?php
	$et_ptemplate_settings = array();
	$et_ptemplate_settings = maybe_unserialize( get_post_meta(get_the_ID(),'et_ptemplate_settings',true) );

	$fullwidth = isset( $et_ptemplate_settings['et_fullwidthpage'] ) ? (bool) $et_ptemplate_settings['et_fullwidthpage'] : false;
?>

<?php get_header(); ?>
	<?php if (get_option('delicatenews_integration_single_top') <> '' && get_option('delicatenews_integrate_singletop_enable') == 'on') echo(get_option('delicatenews_integration_single_top')); ?>

	<div id="content" class="clearfix<?php if($fullwidth) echo(' fullwidth');?>">

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

					<div id="et-login">
						<div class='et-protected'>
							<div class='et-protected-form'>
								<?php $scheme = apply_filters( 'et_forms_scheme', null ); ?>

								<form action='<?php echo esc_url( home_url( '', $scheme ) ); ?>/wp-login.php' method='post'>
									<p><label><span><?php esc_html_e('Username','DelicateNews'); ?>: </span><input type='text' name='log' id='log' value='<?php echo esc_attr($user_login); ?>' size='20' /><span class='et_protected_icon'></span></label></p>
									<p><label><span><?php esc_html_e('Password','DelicateNews'); ?>: </span><input type='password' name='pwd' id='pwd' size='20' /><span class='et_protected_icon et_protected_password'></span></label></p>
									<input type='submit' name='submit' value='Login' class='etlogin-button' />
								</form>
							</div> <!-- .et-protected-form -->
						</div> <!-- .et-protected -->
					</div> <!-- end #et-login -->

					<div class="clear"></div>

					<?php edit_post_link(esc_html__('Edit this page','DelicateNews')); ?>

				</div> <!-- end .post -->
			<?php endwhile; endif; ?>
		</div> <!-- end #main-area -->

		<?php if (!$fullwidth) get_sidebar(); ?>

<?php get_footer(); ?>