<?php class TabbedWidget extends WP_Widget
{
    function TabbedWidget(){
		$widget_ops = array('description' => 'Displays Recent-Popular-Random widget');
		$control_ops = array('width' => 400, 'height' => 500);
		parent::WP_Widget(false,$name='ET Tabbed',$widget_ops,$control_ops);
	}

  /* Displays the Widget in the front-end */
    function widget($args, $instance){
		extract($args);
		$recentPostsNumber = empty($instance['recentPostsNumber']) ? '' : (int) $instance['recentPostsNumber'];
		$popularPostsNumber = empty($instance['popularPostsNumber']) ? '' : (int) $instance['popularPostsNumber'];
		$randomNumber = empty($instance['randomNumber']) ? '' : (int) $instance['randomNumber'];

?>

<div id="tabbed" class="sidebar-block clearfix">
	<ul id="tabbed-area" class="clearfix">
		<li class="first"><a href="#recent-tabbed"><span><?php esc_html_e('Recent','DelicateNews'); ?></span></a></li>
		<li class="second"><a href="#popular-tabbed"><span><?php esc_html_e('Popular','DelicateNews'); ?></span></a></li>
		<li class="last"><a href="#random-tabbed"><span><?php esc_html_e('Random','DelicateNews'); ?></span></a></li>
	</ul>

	<div id="all_tabs">
		<div id="recent-tabbed" class="tab">
			<ul>
				<?php query_posts("posts_per_page=$recentPostsNumber");
				if (have_posts()) : while (have_posts()) : the_post(); ?>
					<?php get_template_part('includes/fromblog_post'); ?>
				<?php endwhile; endif; wp_reset_query(); ?>
			</ul>
		</div> <!-- end #recent-tabbed -->

		<div id="popular-tabbed" class="tab">
			<ul>
				<?php global $wpdb;
					$result = $wpdb->get_results("SELECT comment_count,ID,post_title FROM $wpdb->posts ORDER BY comment_count DESC LIMIT 0 , $popularPostsNumber");
					foreach ($result as $post) {
						#setup_postdata($post);
						$postid = (int) $post->ID;
						$title = $post->post_title;
						$commentcount = (int) $post->comment_count;
						if ($commentcount != 0) { ?>
							<?php query_posts("p=$postid"); ?>
							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
								<?php get_template_part('includes/fromblog_post'); ?>
							<?php endwhile; endif; wp_reset_query(); ?>
						<?php };
					}; ?>
			</ul>
		</div> <!-- end #recent-tabbed -->

		<div id="random-tabbed" class="tab">
			<ul>
				<?php query_posts("posts_per_page=$randomNumber&caller_get_posts=1&orderby=rand");
					if (have_posts()) : while (have_posts()) : the_post(); ?>
						<?php get_template_part('includes/fromblog_post'); ?>
					<?php endwhile; endif; wp_reset_query(); ?>
			</ul>
		</div> <!-- end #recent-tabbed -->
	</div> <!-- end #all_tabs -->
</div> <!-- end .sidebar-block-->

<?php
	}

  /*Saves the settings. */
    function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['recentPostsNumber'] = (int) $new_instance['recentPostsNumber'];
		$instance['popularPostsNumber'] = (int) $new_instance['popularPostsNumber'];
		$instance['randomNumber'] = (int) $new_instance['randomNumber'];

		return $instance;
	}

  /*Creates the form for the widget in the back-end. */
    function form($instance){
		//Defaults
		$instance = wp_parse_args( (array) $instance, array('recentPostsNumber'=>'3', 'popularPostsNumber'=>'3', 'randomNumber'=>'3') );

		$recentPostsNumber = (int) $instance['recentPostsNumber'];
		$popularPostsNumber = (int) $instance['popularPostsNumber'];
		$randomNumber = (int) $instance['randomNumber'];

		# Number of Recent Posts
		echo '<p><label for="' . $this->get_field_id('recentPostsNumber') . '">' . 'Number of Recent Posts:' . '</label><input class="widefat" id="' . $this->get_field_id('recentPostsNumber') . '" name="' . $this->get_field_name('recentPostsNumber') . '" type="text" value="' . esc_attr($recentPostsNumber) . '" /></p>';

		# Number of Popular Posts
		echo '<p><label for="' . $this->get_field_id('popularPostsNumber') . '">' . 'Number of Popular Posts:' . '</label><input class="widefat" id="' . $this->get_field_id('popularPostsNumber') . '" name="' . $this->get_field_name('popularPostsNumber') . '" type="text" value="' . esc_attr($popularPostsNumber) . '" /></p>';

		# Number of Comments
		echo '<p><label for="' . $this->get_field_id('randomNumber') . '">' . 'Number of Random Posts:' . '</label><input class="widefat" id="' . $this->get_field_id('randomNumber') . '" name="' . $this->get_field_name('randomNumber') . '" type="text" value="' . esc_attr($randomNumber) . '" /></p>';

	}

}// end TabbedWidget class

function TabbedWidgetInit() {
	register_widget('TabbedWidget');
}

add_action('widgets_init', 'TabbedWidgetInit');