<?php

add_action('wp_ajax_xyz_lnap_ajax_backlink', 'xyz_lnap_ajax_backlink_call');

function xyz_lnap_ajax_backlink_call() {


	global $wpdb;

	if($_POST){

		update_option('xyz_credit_link','lnap');
	}
	die();
}


?>