<?php
//Exit if file called directly
if (! defined( 'ABSPATH' )) {
	exit;
}

// De-activation
function segwitz_certificate_onDeactivation() {

	if ( ! current_user_can( 'activate_plugins' ) ) return;

	flush_rewrite_rules();
}


// Uninstall
register_uninstall_hook( __FILE__, 'drop_certificate_table' );
function drop_certificate_table(){

	if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) exit ();

	// global $wpdb;
	// $table_name = $wpdb->prefix . 'e34s_clients';
	// $sql = "DROP TABLE IF EXISTS inferno_course_certificates";
	// $wpdb->query($sql);
	// delete_option('e34s_time_card_version');
}