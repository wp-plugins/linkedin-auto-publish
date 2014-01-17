<?php
function lnap_free_network_install($networkwide) {
	global $wpdb;

	if (function_exists('is_multisite') && is_multisite()) {
		// check if it is a network activation - if so, run the activation function for each blog id
		if ($networkwide) {
			$old_blog = $wpdb->blogid;
			// Get all blog ids
			$blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
			foreach ($blogids as $blog_id) {
				switch_to_blog($blog_id);
				lnap_install_free();
			}
			switch_to_blog($old_blog);
			return;
		}
	}
	lnap_install_free();
}

function lnap_install_free()
{
	
	global $current_user;
	get_currentuserinfo();
	if(get_option('xyz_credit_link')=="")
	{
		add_option("xyz_credit_link", '0');
	}
	
	add_option('xyz_lnap_application_lnarray', '');
	add_option('xyz_lnap_ln_shareprivate', '0');
	add_option('xyz_lnap_ln_sharingmethod', '0');
	add_option('xyz_lnap_lnapikey', '');
	add_option('xyz_lnap_lnapisecret', '');
	
	add_option('xyz_lnap_lnoauth_verifier', '');
	add_option('xyz_lnap_lnoauth_token', '');
	add_option('xyz_lnap_lnoauth_secret', '');
	add_option('xyz_lnap_lnpost_permission', '1');
	add_option('xyz_lnap_lnpost_image_permission', '1');
	add_option('xyz_lnap_lnaf', '1');
	add_option('xyz_lnap_lnmessage', '{POST_TITLE} - {PERMALINK}');
	
	

	$version=get_option('xyz_lnap_free_version');
	$currentversion=xyz_lnap_plugin_get_version();
	update_option('xyz_lnap_free_version', $currentversion);
	
	add_option('xyz_lnap_include_pages', '0');
	add_option('xyz_lnap_include_categories', 'All');
	add_option('xyz_lnap_include_customposttypes', '');

}


register_activation_hook(XYZ_LNAP_PLUGIN_FILE,'lnap_free_network_install');
?>