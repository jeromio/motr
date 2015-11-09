<?php
require_once("phurl_config.php");
require_once("functions.php");

db_connect();

//$alias = trim(mysql_real_escape_string($_GET['alias']));
$alias=substr($_SERVER['REQUEST_URI'],1);
$file = 'debug_phurl';
//file_put_contents($file, SITE_URL . " - the Alias is $alias \n", FILE_APPEND | LOCK_EX);
if (!preg_match("/^[a-zA-Z0-9_-]+$/", $alias)) {
  file_put_contents($file, "pregmatch thing happened \n", FILE_APPEND | LOCK_EX);
  header("Location: ".SITE_URL, true, 301);
  exit();
}

if (($url = get_url($alias))) {
//file_put_contents($file, "url is $url and exiting \n", FILE_APPEND | LOCK_EX);

    header("Location: $url", true, 301);
    exit();
}
//file_put_contents($file, SITE_URL . " fell thru \n", FILE_APPEND | LOCK_EX);
// Fall through to WordPress if nothing matched:
include("index.php");
?>
