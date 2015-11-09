<?php
add_action( 'admin_enqueue_scripts', 'import_epanel_javascript' );
function import_epanel_javascript( $hook_suffix ) {
	if ( 'admin.php' == $hook_suffix && isset( $_GET['import'] ) && isset( $_GET['step'] ) && 'wordpress' == $_GET['import'] && '1' == $_GET['step'] )
		add_action( 'admin_head', 'admin_headhook' );
}

function admin_headhook(){ ?>
	<script type="text/javascript">
		jQuery(document).ready(function($){
			$("p.submit").before("<p><input type='checkbox' id='importepanel' name='importepanel' value='1' style='margin-right: 5px;'><label for='importepanel'>Replace ePanel settings with sample data values</label></p>");
		});
	</script>
<?php }

add_action('import_end','importend');
function importend(){
	global $wpdb, $shortname;

	#make custom fields image paths point to sampledata/sample_images folder
	$sample_images_postmeta = $wpdb->get_results(
		$wpdb->prepare( "SELECT meta_id, meta_value FROM $wpdb->postmeta WHERE meta_value REGEXP %s", 'http://et_sample_images.com' )
	);
	if ( $sample_images_postmeta ) {
		foreach ( $sample_images_postmeta as $postmeta ){
			$template_dir = get_template_directory_uri();
			if ( is_multisite() ){
				switch_to_blog(1);
				$main_siteurl = site_url();
				restore_current_blog();

				$template_dir = $main_siteurl . '/wp-content/themes/' . get_template();
			}
			preg_match( '/http:\/\/et_sample_images.com\/([^.]+).jpg/', $postmeta->meta_value, $matches );
			$image_path = $matches[1];

			$local_image = preg_replace( '/http:\/\/et_sample_images.com\/([^.]+).jpg/', $template_dir . '/sampledata/sample_images/$1.jpg', $postmeta->meta_value );

			$local_image = preg_replace( '/s:55:/', 's:' . strlen( $template_dir . '/sampledata/sample_images/' . $image_path . '.jpg' ) . ':', $local_image );

			$wpdb->update( $wpdb->postmeta, array( 'meta_value' => esc_url_raw( $local_image ) ), array( 'meta_id' => $postmeta->meta_id ), array( '%s' ) );
		}
	}

	if ( !isset($_POST['importepanel']) )
		return;

	$importOptions = 'YTo5MDp7czowOiIiO047czoxNzoiZGVsaWNhdGVuZXdzX2xvZ28iO3M6MDoiIjtzOjIwOiJkZWxpY2F0ZW5ld3NfZmF2aWNvbiI7czowOiIiO3M6MjU6ImRlbGljYXRlbmV3c19jb2xvcl9zY2hlbWUiO3M6MzoiUmVkIjtzOjIzOiJkZWxpY2F0ZW5ld3NfYmxvZ19zdHlsZSI7TjtzOjIzOiJkZWxpY2F0ZW5ld3NfZ3JhYl9pbWFnZSI7TjtzOjI1OiJkZWxpY2F0ZW5ld3NfY2F0bnVtX3Bvc3RzIjtzOjE6IjYiO3M6Mjk6ImRlbGljYXRlbmV3c19hcmNoaXZlbnVtX3Bvc3RzIjtzOjE6IjUiO3M6Mjg6ImRlbGljYXRlbmV3c19zZWFyY2hudW1fcG9zdHMiO3M6MToiNSI7czoyNToiZGVsaWNhdGVuZXdzX3RhZ251bV9wb3N0cyI7czoxOiI1IjtzOjI0OiJkZWxpY2F0ZW5ld3NfZGF0ZV9mb3JtYXQiO3M6NjoiTSBqLCBZIjtzOjI0OiJkZWxpY2F0ZW5ld3NfdXNlX2V4Y2VycHQiO047czoxODoiZGVsaWNhdGVuZXdzX2N1Zm9uIjtzOjI6Im9uIjtzOjI4OiJkZWxpY2F0ZW5ld3NfZmVhdF9jYXRlZ29yaWVzIjthOjM6e2k6MDtzOjE6IjMiO2k6MTtzOjE6IjQiO2k6MjtzOjE6IjUiO31zOjMwOiJkZWxpY2F0ZW5ld3Nfc2hvd19yZWNlbnRfYm94ZXMiO3M6Mjoib24iO3M6Mjc6ImRlbGljYXRlbmV3c19ob21lcGFnZV9wb3N0cyI7czoxOiI3IjtzOjI3OiJkZWxpY2F0ZW5ld3NfZXhsY2F0c19yZWNlbnQiO047czoyMToiZGVsaWNhdGVuZXdzX2ZlYXR1cmVkIjtzOjI6Im9uIjtzOjIyOiJkZWxpY2F0ZW5ld3NfZHVwbGljYXRlIjtOO3M6MjE6ImRlbGljYXRlbmV3c19mZWF0X2NhdCI7czo0OiJCbG9nIjtzOjI1OiJkZWxpY2F0ZW5ld3NfZmVhdHVyZWRfbnVtIjtzOjE6IjUiO3M6MjI6ImRlbGljYXRlbmV3c191c2VfcGFnZXMiO047czoyMzoiZGVsaWNhdGVuZXdzX2ZlYXRfcGFnZXMiO047czoyNjoiZGVsaWNhdGVuZXdzX3NsaWRlcl9lZmZlY3QiO3M6NDoiZmFkZSI7czoyMjoiZGVsaWNhdGVuZXdzX21lbnVwYWdlcyI7TjtzOjI5OiJkZWxpY2F0ZW5ld3NfZW5hYmxlX2Ryb3Bkb3ducyI7czoyOiJvbiI7czoyMjoiZGVsaWNhdGVuZXdzX2hvbWVfbGluayI7czoyOiJvbiI7czoyMzoiZGVsaWNhdGVuZXdzX3NvcnRfcGFnZXMiO3M6MTA6InBvc3RfdGl0bGUiO3M6MjM6ImRlbGljYXRlbmV3c19vcmRlcl9wYWdlIjtzOjM6ImFzYyI7czozMDoiZGVsaWNhdGVuZXdzX3RpZXJzX3Nob3duX3BhZ2VzIjtzOjE6IjMiO3M6MjE6ImRlbGljYXRlbmV3c19tZW51Y2F0cyI7TjtzOjQwOiJkZWxpY2F0ZW5ld3NfZW5hYmxlX2Ryb3Bkb3duc19jYXRlZ29yaWVzIjtzOjI6Im9uIjtzOjI5OiJkZWxpY2F0ZW5ld3NfY2F0ZWdvcmllc19lbXB0eSI7czoyOiJvbiI7czozNToiZGVsaWNhdGVuZXdzX3RpZXJzX3Nob3duX2NhdGVnb3JpZXMiO3M6MToiMyI7czoyMToiZGVsaWNhdGVuZXdzX3NvcnRfY2F0IjtzOjQ6Im5hbWUiO3M6MjI6ImRlbGljYXRlbmV3c19vcmRlcl9jYXQiO3M6MzoiYXNjIjtzOjI0OiJkZWxpY2F0ZW5ld3Nfc3dhcF9uYXZiYXIiO047czoyODoiZGVsaWNhdGVuZXdzX2Rpc2FibGVfdG9wdGllciI7TjtzOjIxOiJkZWxpY2F0ZW5ld3NfcG9zdGluZm8iO2E6NDp7aTowO3M6NjoiYXV0aG9yIjtpOjE7czo0OiJkYXRlIjtpOjI7czoxMDoiY2F0ZWdvcmllcyI7aTozO3M6ODoiY29tbWVudHMiO31zOjIzOiJkZWxpY2F0ZW5ld3NfdGh1bWJuYWlscyI7czoyOiJvbiI7czozMDoiZGVsaWNhdGVuZXdzX3Nob3dfcG9zdGNvbW1lbnRzIjtzOjI6Im9uIjtzOjI4OiJkZWxpY2F0ZW5ld3NfcGFnZV90aHVtYm5haWxzIjtOO3M6MzE6ImRlbGljYXRlbmV3c19zaG93X3BhZ2VzY29tbWVudHMiO047czoyMjoiZGVsaWNhdGVuZXdzX3Bvc3RpbmZvMiI7YTo0OntpOjA7czo2OiJhdXRob3IiO2k6MTtzOjQ6ImRhdGUiO2k6MjtzOjEwOiJjYXRlZ29yaWVzIjtpOjM7czo4OiJjb21tZW50cyI7fXM6Mjk6ImRlbGljYXRlbmV3c190aHVtYm5haWxzX2luZGV4IjtzOjI6Im9uIjtzOjI2OiJkZWxpY2F0ZW5ld3NfY3VzdG9tX2NvbG9ycyI7TjtzOjIyOiJkZWxpY2F0ZW5ld3NfY2hpbGRfY3NzIjtOO3M6MjU6ImRlbGljYXRlbmV3c19jaGlsZF9jc3N1cmwiO3M6MDoiIjtzOjI2OiJkZWxpY2F0ZW5ld3NfY29sb3JfYmdjb2xvciI7czowOiIiO3M6Mjc6ImRlbGljYXRlbmV3c19jb2xvcl9tYWluZm9udCI7czowOiIiO3M6Mjc6ImRlbGljYXRlbmV3c19jb2xvcl9tYWlubGluayI7czowOiIiO3M6Mjc6ImRlbGljYXRlbmV3c19jb2xvcl9wYWdlbGluayI7czowOiIiO3M6Mjc6ImRlbGljYXRlbmV3c19jb2xvcl9jYXRzbGluayI7czowOiIiO3M6MzM6ImRlbGljYXRlbmV3c19jb2xvcl9zaWRlYmFyX3RpdGxlcyI7czowOiIiO3M6MzI6ImRlbGljYXRlbmV3c19jb2xvcl9mb290ZXJfdGl0bGVzIjtzOjA6IiI7czozMToiZGVsaWNhdGVuZXdzX2NvbG9yX2Zvb3Rlcl9saW5rcyI7czowOiIiO3M6Mjc6ImRlbGljYXRlbmV3c19zZW9faG9tZV90aXRsZSI7TjtzOjMzOiJkZWxpY2F0ZW5ld3Nfc2VvX2hvbWVfZGVzY3JpcHRpb24iO047czozMDoiZGVsaWNhdGVuZXdzX3Nlb19ob21lX2tleXdvcmRzIjtOO3M6MzE6ImRlbGljYXRlbmV3c19zZW9faG9tZV9jYW5vbmljYWwiO047czozMToiZGVsaWNhdGVuZXdzX3Nlb19ob21lX3RpdGxldGV4dCI7czowOiIiO3M6Mzc6ImRlbGljYXRlbmV3c19zZW9faG9tZV9kZXNjcmlwdGlvbnRleHQiO3M6MDoiIjtzOjM0OiJkZWxpY2F0ZW5ld3Nfc2VvX2hvbWVfa2V5d29yZHN0ZXh0IjtzOjA6IiI7czoyNjoiZGVsaWNhdGVuZXdzX3Nlb19ob21lX3R5cGUiO3M6Mjc6IkJsb2dOYW1lIHwgQmxvZyBkZXNjcmlwdGlvbiI7czozMDoiZGVsaWNhdGVuZXdzX3Nlb19ob21lX3NlcGFyYXRlIjtzOjM6IiB8ICI7czoyOToiZGVsaWNhdGVuZXdzX3Nlb19zaW5nbGVfdGl0bGUiO047czozNToiZGVsaWNhdGVuZXdzX3Nlb19zaW5nbGVfZGVzY3JpcHRpb24iO047czozMjoiZGVsaWNhdGVuZXdzX3Nlb19zaW5nbGVfa2V5d29yZHMiO047czozMzoiZGVsaWNhdGVuZXdzX3Nlb19zaW5nbGVfY2Fub25pY2FsIjtOO3M6MzU6ImRlbGljYXRlbmV3c19zZW9fc2luZ2xlX2ZpZWxkX3RpdGxlIjtzOjk6InNlb190aXRsZSI7czo0MToiZGVsaWNhdGVuZXdzX3Nlb19zaW5nbGVfZmllbGRfZGVzY3JpcHRpb24iO3M6MTU6InNlb19kZXNjcmlwdGlvbiI7czozODoiZGVsaWNhdGVuZXdzX3Nlb19zaW5nbGVfZmllbGRfa2V5d29yZHMiO3M6MTI6InNlb19rZXl3b3JkcyI7czoyODoiZGVsaWNhdGVuZXdzX3Nlb19zaW5nbGVfdHlwZSI7czoyMToiUG9zdCB0aXRsZSB8IEJsb2dOYW1lIjtzOjMyOiJkZWxpY2F0ZW5ld3Nfc2VvX3NpbmdsZV9zZXBhcmF0ZSI7czozOiIgfCAiO3M6MzI6ImRlbGljYXRlbmV3c19zZW9faW5kZXhfY2Fub25pY2FsIjtOO3M6MzQ6ImRlbGljYXRlbmV3c19zZW9faW5kZXhfZGVzY3JpcHRpb24iO047czoyNzoiZGVsaWNhdGVuZXdzX3Nlb19pbmRleF90eXBlIjtzOjI0OiJDYXRlZ29yeSBuYW1lIHwgQmxvZ05hbWUiO3M6MzE6ImRlbGljYXRlbmV3c19zZW9faW5kZXhfc2VwYXJhdGUiO3M6MzoiIHwgIjtzOjM2OiJkZWxpY2F0ZW5ld3NfaW50ZWdyYXRlX2hlYWRlcl9lbmFibGUiO3M6Mjoib24iO3M6MzQ6ImRlbGljYXRlbmV3c19pbnRlZ3JhdGVfYm9keV9lbmFibGUiO3M6Mjoib24iO3M6Mzk6ImRlbGljYXRlbmV3c19pbnRlZ3JhdGVfc2luZ2xldG9wX2VuYWJsZSI7czoyOiJvbiI7czo0MjoiZGVsaWNhdGVuZXdzX2ludGVncmF0ZV9zaW5nbGVib3R0b21fZW5hYmxlIjtzOjI6Im9uIjtzOjI5OiJkZWxpY2F0ZW5ld3NfaW50ZWdyYXRpb25faGVhZCI7czowOiIiO3M6Mjk6ImRlbGljYXRlbmV3c19pbnRlZ3JhdGlvbl9ib2R5IjtzOjA6IiI7czozNToiZGVsaWNhdGVuZXdzX2ludGVncmF0aW9uX3NpbmdsZV90b3AiO3M6MDoiIjtzOjM4OiJkZWxpY2F0ZW5ld3NfaW50ZWdyYXRpb25fc2luZ2xlX2JvdHRvbSI7czowOiIiO3M6MjM6ImRlbGljYXRlbmV3c180NjhfZW5hYmxlIjtOO3M6MjI6ImRlbGljYXRlbmV3c180NjhfaW1hZ2UiO3M6MDoiIjtzOjIwOiJkZWxpY2F0ZW5ld3NfNDY4X3VybCI7czowOiIiO3M6MjQ6ImRlbGljYXRlbmV3c180NjhfYWRzZW5zZSI7czowOiIiO30=';

	/*global $options;

	foreach ($options as $value) {
		if( isset( $value['id'] ) ) {
			update_option( $value['id'], $value['std'] );
		}
	}*/

	$importedOptions = unserialize(base64_decode($importOptions));

	foreach ($importedOptions as $key=>$value) {
		if ($value != '') update_option( $key, $value );
	}
	update_option( $shortname . '_use_pages', 'false' );
} ?>