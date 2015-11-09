<?php
  
function clicky_log( $a ) {
  
  # ATTENTION! you need to change the following 2 values for the site you are going to use this with!
  # these are all available on the main preferences page for any web site!
  $site_id = "66471762";
  $sitekey_admin = "e1ad66f3aae80a14ceaa68b86beb4d69";
  
  $type = $a['type'];
  if( !in_array( $type, array( "pageview", "download", "outbound", "click", "custom", "goal" ))) $type = "pageview";
  
  $file = "http://in.getclicky.com/in.php?site_id=".$site_id."&sitekey_admin=".$sitekey_admin."&type=".$type;
  
  # referrer and user agent - will only be logged if this is the very first action of this session
  if( $a['ref'] ) $file .= "&ref=".urlencode( $a['ref'] );
  if( $a['ua']  ) $file .= "&ua=". urlencode( $a['ua']  );
  
  # we need either a session_id or an ip_address...
  if( is_numeric( $a['session_id'] )) {
    $file .= "&session_id=".$a['session_id'];
  }
  else {
    if( !$a['ip_address'] ) $a['ip_address'] = $_SERVER['REMOTE_ADDR']; # automatically grab IP that PHP gives us.
    if( !preg_match( "#^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$#", $a['ip_address'] )) return false;
    $file .= "&ip_address=".$a['ip_address'];
  }
  
  if( $type == "goal" || $type == "custom" ) {
    # dont do anything, data has already been cat'd
  }
  else {
    if( $type == "outbound" ) {
      if( !preg_match( "#^(https?|telnet|ftp)#", $a['href'] )) return false;
    }
    else {
      # all other action types must start with either a / or a #
      if( !preg_match( "#^(/|\#)#", $a['href'] )) $a['href'] = "/" . $a['href'];
    }
    
    $file .= "&href=".urlencode( $a['href'] );
    if( $a['title'] ) $file .= "&title=".urlencode( $a['title'] );
  }
  
  return file( $file ) ? true:false;
  
}

require_once("config.php");
require_once("functions.php");

db_connect();

$alias = trim(mysql_real_escape_string($_GET['alias']));

if (!preg_match("/^[a-zA-Z0-9_-]+$/", $alias)) {
  header("Location: ".SITE_URL, true, 301);
  exit();
}

if (($url = get_url($alias))) {
        $a = array();
        $a['ref'] = @$_SERVER['HTTP_REFERER'];
        $a['ua'] = $_SERVER['HTTP_USER_AGENT'];
        $a['ip_address'] = $_SERVER['REMOTE_ADDR'];
        $a['session_id'] = '';
        $a['type'] = "outbound";
        $a['href'] = $url;
        $a['title'] = $alias;
        ### $clicky_response = clicky_log( $a );
        if (5 == 4) { 
###$clicky_response == true) {
                header("Location: ".$a['href'], true, 301);
        } else {
                # error logging, redirect anyway.
                header("Location: ".$url, true, 301);
        }
    exit();
}

header("Location: ".SITE_URL, true, 301);

?>
