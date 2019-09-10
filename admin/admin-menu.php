<?php
//Exit if file called directly
if (! defined( 'ABSPATH' )) {
	exit;
}


// add top-level administrative menu
function segwitz_certificate_admin_menu() {
	
	/* 
		add_menu_page(
			string   $page_title, 
			string   $menu_title, 
			string   $capability, 
			string   $menu_slug, 
			callable $function = '', 
			string   $icon_url = '', 
			int      $position = null 
		)
	*/
	
	add_menu_page(
		'Certificate Codes',
		'Certificate Codes',
		'manage_options',
		'certificate-codes',
		'admin_certificate_ui',
		'dashicons-admin-generic',
		null
	);
	
}
add_action( 'admin_menu', 'segwitz_certificate_admin_menu' );