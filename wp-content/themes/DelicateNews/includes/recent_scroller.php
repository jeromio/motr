<div id="recent-posts" class="clearfix">
	<a href="#" id="left-arrow"><?php esc_html_e('Previous','DelicateNews'); ?></a>

	<div id="recent-container">

		<?php
		$featCats = get_option('delicatenews_feat_categories');
		$featItems = count($featCats)-1;


		for ($i=0; $i<=$featItems; $i++) {
			if ($i % 3 == 0) echo ('<div class="slide">');

			query_posts(array
					 ( 'order' => 'DESC',
					 'cat' => $featCats[$i],
					 'posts_per_page' => '1' )
					 );
				if (have_posts()) : while (have_posts()) : the_post(); ?>

					<div class="cat-entry">
						<h4 class="cat-title"><?php esc_html_e('Recent From','DelicateNews'); ?> <?php echo(get_cat_name($featCats[$i])); ?></h4>
						<h3 class="title"><a href="<?php the_permalink(); ?>"><?php truncate_title(40); ?></a></h3>

						<span class="thumbnail">
							<?php $thumb = '';

							$width = 279;
							$height = 105;
							$classtext = '';
							$titletext = get_the_title();

							$thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext);
							$thumb = $thumbnail["thumb"];?>
							<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext , $width, $height, $classtext); ?>
							<span class="metainfo"><?php esc_html_e('Posted','DelicateNews'); ?> <?php esc_html_e('by','DelicateNews'); ?> <?php the_author_posts_link(); ?> <?php esc_html_e('on','DelicateNews'); ?> <?php the_time(get_option('delicatenews_date_format')) ?></span>

							<span class="overlay"></span>
						</span> <!-- end .thumbnail -->
					</div> <!-- end .cat-entry -->

				<?php endwhile; endif; wp_reset_query();

				if (($i+1) % 3 == 0) echo ('</div><!-- end .slide-->');
		}; ?>

		<?php if (count($featCats) % 3 <> 0) echo('</div><!-- end .slide-->'); ?>

	</div> <!-- end #recent-container -->

	<a href="#" id="right-arrow"><?php esc_html_e('Next','DelicateNews'); ?></a>
</div> <!-- end #recent-posts-->