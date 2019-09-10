<?php
/**
 * Plugin Name: SegWitz - Course Certificate Verification
 * Plugin URI: https://segwitz.com
 * Description: Admin can enter course certificate codes , and details in the panel and user can verify their certificate using the cource code in the front end.
 * Version: 1.0
 * Author: SegWitz
 * Author URI: https://segwitz.com
 * Text Domain: seg-witz
 */

if (! defined( 'ABSPATH' )) {
	exit;
}

function plugin_styles_scripts(){

    wp_enqueue_style('dataTable-css', plugin_dir_url( __FILE__ ) . 'inc/css/jquery.dataTables.css' );
    wp_enqueue_script('dataTable-js', plugin_dir_url( __FILE__ ) . 'inc/js/jquery.dataTables.js' );
    wp_enqueue_script('segwitz-js', plugin_dir_url( __FILE__ ) . 'inc/js/plugin.js' );
}
add_action('admin_enqueue_scripts', 'plugin_styles_scripts');

function include_bs_datatables(){
	wp_enqueue_script('jquery');
	wp_enqueue_style('datepicker-css', plugin_dir_url( __FILE__ ) . 'inc/css/jquery-ui.css' );
    wp_enqueue_script('datepicker-js', plugin_dir_url( __FILE__ ) . 'inc/js/jquery-ui.js' );
	wp_enqueue_script('admin-bs', plugin_dir_url( __FILE__ ) . 'inc/js/bootstrap.min.js' );
    wp_enqueue_style('admin-css', plugin_dir_url( __FILE__ ) . 'inc/css/bootstrap.min.css' );
    wp_enqueue_style('plugin-css', plugin_dir_url( __FILE__ ) . 'inc/css/plugin.css' );
}
if( isset($_GET['page']) && $_GET['page'] == 'certificate-codes' ){
	add_action('admin_enqueue_scripts', 'include_bs_datatables');
}

function include_bootsrap(){
	wp_enqueue_style('searchform-css', plugin_dir_url( __FILE__ ) . 'inc/css/search-form.css' );
}
add_action('wp_head', 'include_bootsrap');

if ( is_admin() ) {

	// Include dependencies
	require_once plugin_dir_path( __file__ ).'install.php';
	require_once plugin_dir_path( __file__ ).'uninstall.php';
	require_once plugin_dir_path( __file__ ).'inc/core-functions.php';
	require_once plugin_dir_path( __file__ ).'admin/admin-menu.php'; 
	require_once plugin_dir_path( __file__ ).'admin/settings-page.php';
}

register_activation_hook( __FILE__, 'segwitz_certificate_onActivation' );
register_deactivation_hook( __FILE__, 'segwitz_certificate_onDeactivation' );

// Search certificate
function certificate_search_form(){ 
	$output = '';
	$output .= '<div class="cf-search">
		<form method="POST">
			<input type="text" required class="cf-field" placeholder="'.esc_html__('Enter Certificate Code', 'seg-witz').'" name="certificate_code">
			<input type="submit" class="cf-btn" value="'.esc_html__('Search', 'seg-witz').'" name="code_data">
		</form>
	</div>
	<div class="container">';
	if( isset($_POST['code_data']) ){
		$code = sanitize_text_field($_POST['certificate_code']);
		global $wpdb;
		$rows = $wpdb->get_results( "SELECT * FROM segwitz_course_certificates where certificate_code = '$code'"); 
		if( !empty($rows) ){
		$output .= '<h1 class="rs-heading">'.esc_html__('Search Result', 'seg-witz').'</h1>
		</strong>
	</div>
    <table class="search-table" style="width:100%">
    	<thead>
            <tr>
                <th class="btlr-10">'.esc_html__('Student Name', 'seg-witz').'</th>
                <th>'.esc_html__('Course', 'seg-witz').'</th>
                <th>'.esc_html__('Hours Completed', 'seg-witz').'</th>
                <th>'.esc_html__('Certification No', 'seg-witz').'</th>
                <th>'.esc_html__('Date of Birth', 'seg-witz').'</th>
                <th class="br-0 btrr-10">'.esc_html__('Award Date', 'seg-witz').'</th>
            </tr>
        </thead>
        <tbody>';
		foreach ( $rows as $data ){
        	$output .= '<tr>
        		<td class="bl-1">'.esc_html($data->student_name).'</td>
        		<td>'.esc_html($data->course_name).'</td>
        		<td>'.esc_html($data->course_hours).'</td>
        		<td>'.esc_html($data->certificate_code).'</td>
        		<td>'.esc_html($data->dob).'</td>
        		<td>'.esc_html($data->award_date).'</td>
        	</tr>';
        }
       	$output .= ' </tbody>
    </table>';
   		}else{
   			echo '<div class="danger">'.esc_html__('No result found against this code', 'seg-witz').' <strong>'.esc_html($code).'</strong></div>';
   		} 
    } 
	$output .= '</div>';
	return $output;
}
add_shortcode( 'get_certificate_search_form' , 'certificate_search_form' );