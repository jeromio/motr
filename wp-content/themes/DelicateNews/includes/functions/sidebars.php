<?php
if ( function_exists('register_sidebar') )
register_sidebar(array(
	'name' => 'Sidebar One Column',
	'before_widget' => '<div id="%1$s" class="widget %2$s"><!-- start mco.widget -->',
	'after_widget' => '</div><!-- end .widget-content --></div> <!-- end mco.widget -->',
	'before_title' => '<h4 class="widgettitle">',
	'after_title' => '</h4><div class="widgetcontent">',
));

if ( function_exists('register_sidebar') )
    register_sidebar(array(
		'name' => 'Sidebar Left Column',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div><!-- end .widget-content --></div> <!-- end .widget -->',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4><div class="widgetcontent">',
    ));

if ( function_exists('register_sidebar') )
register_sidebar(array(
	'name' => 'Sidebar Right Column',
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div><!-- end .widget-content --></div> <!-- end .widget -->',
	'before_title' => '<h4 class="widgettitle">',
	'after_title' => '</h4><div class="widgetcontent">',
));

if ( function_exists('register_sidebar') )
    register_sidebar(array(
		'name' => 'Footer',
		'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
		'after_widget' => '</div> <!-- end .footer-widget -->',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
    ));
?>