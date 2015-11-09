<?php
/*
Plugin Name: Count Posts
Plugin URI: http://code.andrewhamilton.net/wordpress/plugins/count-posts
Description: A simple plugin that provides a template function to count posts, as well as filtering by category and over time in days. The function can either return or display the result. <em>Based off Clint Howarth's count_posts</em>
Author: Andrew Hamilton
Version: 1.0
Author URI: http://andrewhamilton.net/
Licensed under the The GNU General Public License 2.0 (GPL) http://www.gnu.org/licenses/gpl.html
*/

//---------------------------------------------------------------------------
//	Setup Default Settings
//---------------------------------------------------------------------------

//Detect WordPress version to add compatibility with 2.3 or higher
$wpversion_full = get_bloginfo('version');
$wpversion = preg_replace('/([0-9].[0-9])(.*)/', '$1', $wpversion_full); //Boil down version number to X.X

//----------------------------------------------------------------------------
//		MAIN FUNCTION
//----------------------------------------------------------------------------

function count_posts ($category = '', $daysago = '0', $display = true)
{
	global $wpversion, $wpdb;
	
	$now = current_time('mysql');
	
	// Get the date from $daysago
	if ($span != 0) {
		$then = gmdate('Y-m-d H:i:s',(time() + (get_settings('gmt_offset') * 3600) -($daysago * 86400)));
	}
	
	// Get the category ID from category name
	if (!empty($category) && $wpversion < 2.5)
	{
		$catid = $wpdb->get_var("SELECT cat_id FROM $wpdb->categories WHERE cat_name = '$category' ");
	}
	elseif (!empty($category) && $wpversion >= 2.5)
	{
		$catid = $wpdb->get_var("SELECT term_id FROM $wpdb->terms WHERE name = '$category' ");
	}
	
	// Construct Query
	$query = "SELECT COUNT(*) FROM $wpdb->posts ";
	
	if (!empty($category) && $wpversion < 2.5) 
	{
		$query .= "LEFT JOIN $wpdb->post2cat ON($wpdb->posts.ID = $wpdb->post2cat.post_id) ";
	}
	elseif (!empty($category) && $wpversion >= 2.5)
	{
		$query .= "LEFT JOIN $wpdb->term_relationships ON($wpdb->posts.ID = $wpdb->term_relationships.object_id) ";
	}
	
	$query .= "WHERE (post_date <= '$now') ";
	
	if (!empty($category) && $wpversion < 2.5) 
	{
		$query .= "AND (category_id = $catid) ";
	}
	elseif (!empty($category) && $wpversion >= 2.5)
	{
		$query .= "AND (term_taxonomy_id = $catid) ";
	}
	
	if ($span != 0) {
		$query .= "AND (post_date >= '$then') ";
	}
	
	$query .= "AND (post_status = 'publish') ";
	
	//Output
	$output = $wpdb->get_var($query);
	
	if ($display) 
	{
		echo $output;
	}
	else
	{
		return $output;
	}

}
?>