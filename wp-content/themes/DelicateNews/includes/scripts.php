<?php global $shortname; ?>

	<script src="<?php echo get_template_directory_uri(); ?>/js/cufon-yui.js" type="text/javascript"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/Raleway_250.font.js" type="text/javascript"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/League_Gothic_400.font.js" type="text/javascript"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/ChunkFive_400.font.js" type="text/javascript"></script>

	<?php if ( get_option($shortname.'_cufon') == 'on' ) { ?>
		<script type="text/javascript">
			 Cufon.set('fontFamily', 'League Gothic');

	                Cufon.replace('ul#primary li a',{textShadow: '1px 1px 1px #161515', hover: { textShadow: '1px 1px 1px #161515'}})('span.date',{textShadow: '-1px -1px 1px #820601'})('h4.category-title')('h4#recent')('#recent-posts h3.title')('#tabbed-area li a',{textShadow:'1px 1px 0px #fff'})('h4.widgettitle')('h4.cat-title')('h4.tec-cal-title')('#footer h4.widgettitle',{textShadow:'1px 1px 1px #0C0C0C'})('div#breadcrumbs')(".wp-pagenavi")('.comment-author');


			Cufon.set('fontFamily', 'ChunkFive');

			Cufon.replace('#featured h2.title',{textShadow: '1px 1px 0px #fff'})('#recent-tabbed a', {textShadow: '1px 1px 0px #fff'})('#popular-tabbed a', {textShadow: '1px 1px 0px #fff'})('#random-tabbed a', {textShadow: '1px 1px 0px #fff'})('.entry h1')('.entry h2')('.entry h3')('.entry h4')('.entry h5')('.entry h6')('.slide h3.title',{textShadow: '1px 1px 0px #fff'})('.post h1.title')('.post h2')('.post h3')('.post h4')('.post h5')('.post h6')('h3#comments, h3#reply-title span');
        		</script>
	<?php }; ?>


	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.cycle.all.min.js"></script>
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.easing.1.3.js"></script>
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/superfish.js"></script>
	<script type="text/javascript">
	//<![CDATA[
		jQuery.noConflict();

		jQuery('ul.nav').superfish({
			delay:       300,                            // one second delay on mouseout
			animation:   {opacity:'show',height:'show'},  // fade-in and slide-down animation
			speed:       'fast',                          // faster animation speed
			autoArrows:  true,                           // disable generation of arrow mark-up
			dropShadows: false                            // disable drop shadows
		});

		jQuery('ul.nav > li > a.sf-with-ul').parent('li').addClass('sf-ul');


		var $featured_content = jQuery('#slider .slide'),
			$tabbed_area = jQuery('#tabbed'),
			$tab_content = jQuery('.tab-content'),
			$controllers = jQuery('div#controllers'),
			$all_tabs = jQuery('#all_tabs');

		if ($tabbed_area.length) {
			$tabbed_area.tabs();
		};

		et_search_bar();
		et_footer_improvements('#footer .footer-widget');

		if ($featured_content.length) {
			$featured_content.cycle({
				fx: 'scrollHorz',
				timeout: 0,
				speed: 700,
				cleartypeNoBg: true,
				pager:  'div#controllers'
			});

			var controllersWidth = $controllers.width(),
				controllersLeft = Math.round((280 - controllersWidth) / 2);
				$controllers.css('left',controllersLeft);
		};


		var $recent_cat = jQuery("#recent-container");
		if ($recent_cat.length) {
			$recent_cat.cycle({
				fx: 'scrollHorz',
				timeout: 0,
				speed: 700,
				cleartypeNoBg: true,
				next:   'a#right-arrow',
				prev:   'a#left-arrow'
			});
		};


		<!---- Footer Improvements ---->
		function et_footer_improvements($selector){
			var $footer_widget = jQuery($selector);

			if (!($footer_widget.length == 0)) {
				$footer_widget.each(function (index, domEle) {
					if ((index+1)%3 == 0) jQuery(domEle).addClass("last").after("<div class='clear'></div>");
				});
			};
		};

		<!---- Search Bar Improvements ---->
		function et_search_bar(){
			var $searchform = jQuery('#header-top div#search-form'),
				$searchinput = $searchform.find("input#searchinput"),
				searchvalue = $searchinput.val();

			$searchinput.focus(function(){
				if (jQuery(this).val() === searchvalue) jQuery(this).val("");
			}).blur(function(){
				if (jQuery(this).val() === "") jQuery(this).val(searchvalue);
			});
		};


		var $featured_content = jQuery('#featured #description'),
		$slider_control_tab = jQuery('#featured #controllers a');
		et_cycle_integration();

		<!---- Featured Slider Cycle Integration ---->
		function et_cycle_integration(){
			$featured_content.cycle({
				timeout: 0,
				speed: 300,
				cleartypeNoBg: true,
				fx: '<?php echo(get_option($shortname.'_slider_effect')); ?>'
			});

			var ordernum;
			var pause_scroll = false;


				/*$featured_area.mouseover(function(){
					pause_scroll = true;
				}).mouseout(function(){
					pause_scroll = false;
				});*/


			function gonext(this_element){
				$slider_control_tab.removeClass('active');
				this_element.addClass('active');

				ordernum = this_element.attr("rel");
				$featured_content.cycle(ordernum - 1);
			}

			$slider_control_tab.click(function() {
				clearInterval(interval);
				gonext(jQuery(this));
				return false;
			});

			/*$slider_arrows.click(function() {
				clearInterval(interval);

				if (jQuery(this).attr("id") === 'nextlink') {
					auto_number = $slider_control.children(controlTabString+".active").prevAll(controlTabString).length+1;
					if (auto_number === $slider_control_tab.length) auto_number = 0;
				} else {
					auto_number = $slider_control.children(controlTabString+".active").prevAll(controlTabString).length-1;
					if (auto_number === -1) auto_number = $slider_control_tab.length-1;
				};

				gonext($slider_control_tab.eq(auto_number));
				return false;
			});*/

			var auto_number;
			var interval;

			/*$slider_control_tab.bind('autonext', function autonext(){
				if (!pause_scroll) gonext(jQuery(this));
				return false;
			});


				interval = setInterval(function() {
					auto_number = $slider_control.children(controlTabString+".active").prevAll(controlTabString).length+1;
					if (auto_number === $slider_control_tab.length) auto_number = 0;
					$slider_control_tab.eq(auto_number).trigger('autonext');
				}, 500);
			*/

		};

		<?php if (get_option($shortname.'_disable_toptier') == 'on') echo('jQuery("ul.nav > li > ul").prev("a").attr("href","#");'); ?>

		<?php if ( get_option($shortname.'_cufon') == 'on' ) { ?>
			Cufon.now();
		<?php }; ?>

		jQuery('ul#primary li a, span.date, h4.category-title, h4#recent,#recent-posts h3.title, #tabbed-area li a, h4.widgettitle,h4.cat-title,#footer h4.widgettitle,div#breadcrumbs,.wp-pagenavi,.comment-author,#featured h2.title,#recent-tabbed a, #popular-tabbed a, #random-tabbed a,.entry h1,.entry h2,.entry h3,.entry h4,.entry h5,.entry h6,.slide h3.title,.post h1.title,.post h2,.post h3,.post h4,.post h5,.post h6,h3#comments').css('text-indent','0px');

	//]]>
	</script>
