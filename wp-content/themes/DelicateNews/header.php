<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title><?php elegant_titles(); ?></title>
<?php elegant_description(); ?>
<?php elegant_keywords(); ?>
<?php elegant_canonical(); ?>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<!--[if lt IE 7]>
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/ie6style.css" />
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/DD_belatedPNG_0.0.8a-min.js"></script>
	<script type="text/javascript">DD_belatedPNG.fix('img#logo, #search-form, #featured, span.date, .footer-widget ul li, span.overlay, a.readmore, a.readmore span, #recent-posts a#left-arrow, #recent-posts a#right-arrow, h4#recent, div#breadcrumbs, #sidebar h4');</script>
<![endif]-->
<!--[if IE 7]>
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/ie7style.css" />
<![endif]-->
<!--[if IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/ie8style.css" />
<![endif]-->

<script type="text/javascript">
	document.documentElement.className = 'js';
</script>

<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>

</head>
<?php $et_body_class = get_option('deepfocus_cufon') == 'false' ? 'cufon-disabled' : ''; ?>
<body<?php if (is_home()) echo(' id="home"'); ?><?php if ( get_option('delicatenews_cufon') == 'false' ) echo(' class="cufon-disabled"'); ?> <?php body_class( $et_body_class ); ?>>
	<div id="header-top">
		<div class="container clearfix" style="z-index: 995;">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php global $default_colorscheme;
				$colorSchemePath = '';
				$colorScheme = get_option('delicatenews_color_scheme');
				if ($colorScheme <> $default_colorscheme) $colorSchemePath = strtolower($colorScheme) . '/';
				$colorFolder = ($colorScheme <> $default_colorscheme) ? $colorSchemePath . '/' : '';
				$logo = (get_option('delicatenews_logo') <> '') ? get_option('delicatenews_logo') : get_template_directory_uri().'/images/'.$colorFolder.'logo.png'; ?>
				<img src="<?php echo esc_attr( $logo ); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" id="logo"/>
			</a>
			<!-- Jeremy Removed search form
			<div id="search-form">
				<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<input type="text" value="<?php esc_attr_e('search this site...','DelicateNews'); ?>" name="s" id="searchinput" />

					<input type="image" src="<?php echo get_template_directory_uri(); ?>/images/search_btn.png" id="searchsubmit" />
				</form>
			</div> ><!-- end #search-form -->
			<?php $menuClass = 'nav';
			$menuID = 'primary';
			$primaryNav = '';
			if (function_exists('wp_nav_menu')) {
				$primaryNav = wp_nav_menu( array( 'theme_location' => 'primary-menu', 'container' => '', 'fallback_cb' => '', 'menu_class' => $menuClass, 'menu_id' => $menuID, 'echo' => false ) );
			};
			if ($primaryNav == '') { ?>
				<ul id="<?php echo esc_attr( $menuID ); ?>" class="<?php echo esc_attr( $menuClass ); ?>" style="z-index: 920;">
					<?php if (get_option('delicatenews_swap_navbar') == 'false') { ?>
						<?php if (get_option('delicatenews_home_link') == 'on') { ?>
							<li <?php if (is_home()) echo('class="current_page_item"') ?>><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e('Home','DelicateNews') ?></a></li>
						<?php }; ?>

						<?php show_page_menu($menuClass,false,false); ?>
					<?php } else { ?>
						<?php show_categories_menu($menuClass,false); ?>
					<?php } ?>
				</ul> <!-- end ul#nav -->
			<?php }
			else echo($primaryNav); ?>

		</div> 	<!-- end .container -->
<!-- Jeremy added FB & Twitter -->
<div id="socialmedia" align="right">
<a href="http://facebook.com/motorco" target="_blank"><img src="http://motorcomusic.com/wp-content/themes/DelicateNews/images/facebook-24x24.png" title="You know you Like Motorco on FB"></a>
<a href="http://twitter.com/motorcomh" target="_blank"><img src="http://motorcomusic.com/wp-content/themes/DelicateNews/images/twitter-24x24.png" title="Follow Motorco on Twitter"></a>
<a href="http://youtube.com/view_play_list?p=54DAF2389AEC725A" target="_blank"><img src="http://motorcomusic.com/wp-content/themes/DelicateNews/images/youtube-24x24.png" title="Motorco on YouTube"></a>
<a href="http://www.flickr.com/search/?q=motorco" target="_blank"><img src="http://motorcomusic.com/wp-content/themes/DelicateNews/images/flickr-24x24.png" title="Motorco on Flickr"></a>
<div class="g-plusone" data-size="medium" data-annotation="none"></div>
</div>	
<!-- /Jeremy added FB & Twitter -->

	</div> 	<!-- end #header-top -->

	<div id="bg">
		<div id="bg2">
			<div class="container">
				<div id="header" class="clearfix">
					<a href="http://motr.co/partslabor/"><img src="/wp-content/uploads/pnl.png" 
								style="z-index: 900;position: relative;left: 480px; margin: 20px 80px 0px 0px;"></a>
				
					<?php global $default_colorscheme,$shortname;
					$colorSchemePath = '';
					$colorScheme = get_option($shortname . '_color_scheme');
					if ($colorScheme <> $default_colorscheme) $colorSchemePath = strtolower($colorScheme) . '/'; ?>

					<?php $menuClass = 'nav';
					$menuID = 'secondary';
					$secondaryNav = '';
					if (function_exists('wp_nav_menu')) {
						$secondaryNav = wp_nav_menu( array( 'theme_location' => 'secondary-menu', 'container' => '', 'fallback_cb' => '', 'menu_class' => $menuClass, 'menu_id' => $menuID, 'echo' => false ) );
					};
					if ($secondaryNav == '') { ?>
						<ul id="<?php echo esc_attr( $menuID ); ?>" class="<?php echo esc_attr( $menuClass ); ?>">
							<?php if (get_option('delicatenews_swap_navbar') == 'false') { ?>
								<?php show_categories_menu($menuClass,false); ?>
							<?php } else { ?>
								<?php if (get_option('delicatenews_home_link') == 'on') { ?>
									<li <?php if (is_home()) echo('class="current_page_item" style="z-index:910;"') ?>><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e('Home','DelicateNews') ?></a></li>
								<?php }; ?>

								<?php show_page_menu($menuClass,false,false); ?>
							<?php } ?>
						</ul> <!-- end ul#nav -->
					<?php }
					else echo($secondaryNav); ?>
				</div> 	<!-- end #header -->
