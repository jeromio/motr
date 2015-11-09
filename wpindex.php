<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define('WP_USE_THEMES', true);

/** Phurl stuff: */
require_once("config.php");
require_once("functions.php");

db_connect();

//$alias = trim(mysql_real_escape_string($_GET['alias']));
$alias=substr($_SERVER['REQUEST_URI'],1);
$file = 'debug_phurl';
file_put_contents($file, "the Alias is $alias \n", FILE_APPEND | LOCK_EX);
/** Dunno what this is for
 if (!preg_match("/^[a-zA-Z0-9_-]+$/", $alias)) {
  file_put_contents($file, "pregmatch thing happened \n", FILE_APPEND | LOCK_EX);
  header("Location: ".SITE_URL, true, 301);
  exit();
} 
*/
if (($url = get_url($alias))) {
file_put_contents($file, "url is $url and exiting \n", FILE_APPEND | LOCK_EX);

    header("Location: $url", true, 301);
    exit();
}


/** Loads the WordPress Environment and Template */
require( dirname( __FILE__ ) . '/wp-blog-header.php' );
