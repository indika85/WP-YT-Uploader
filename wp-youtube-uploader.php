<?php
/**
 * Plugin Name: WP Youtube Uploader
 * Description: Plugin developed for Remember Me. Handles YouTube API, Video upload forms, image restrictions, QR code, userprofile header. 
 * Version: 1.0.0
 * Author: Indika Ratnayake
 * Author URI: http://niratnayake.com
 */
ini_set('display_errors', 1);
error_reporting(E_ERROR);

if (!class_exists('UM_API')) {
	add_action( 'admin_notices', 'show_admin_notice__error' );
	//deactivate_plugins( plugin_basename( __FILE__ ) );
	wp_die( 'This plugin requires Ultimate Member plugin.' );
} 
function show_admin_notice__error() {
	$class = 'notice notice-error';
	$message = __( 'YouTube video uploader requires the Ultimate Member plugin installed and activated!.', 'sample-text-domain' );

	printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
}

function test_ajax_load_scripts() {

	if ( is_ultimatemember() ) {
		$image_url = get_image_rul();
		$doChange=false;
		
		if($image_url != ''){
			$doChange = true;
		}
		
		$show_years=false;
		$year_string = '';
		$current_url=$_SERVER['REQUEST_URI'];
		$rul_base_name = '/' . get_option('user_url_base') . '/';
		
		//check if the current url contains the word member.
		//If true, show the year string
		if(strpos($current_url, $rul_base_name) === 0 || strpos($current_url, '/account') === 0){
			
			um_fetch_user( um_profile_id() );
		
			if(um_user('dob') && um_user('dod')){
				$year_string = '(' . date('Y', strtotime(um_user('dob'))) .' - ' . date('Y', strtotime(um_user('dod'))) . ')';
				$show_years=true;
			}
			
			wp_enqueue_script( "image-add", plugin_dir_url( __FILE__ ) . 'js/image-add.js', array( 'jquery' ) );
			wp_localize_script( 'image-add', 'image_adder', array( 
					'image_url' => $image_url,
					'do_change' => $doChange,
					'show_years' => $show_years,
					'year_string' => $year_string
					)
			);
			wp_enqueue_script( "profile-creator", plugin_dir_url( __FILE__ ) . 'js/profile-creator.js', array( 'jquery' ) );
			wp_localize_script( 'profile-creator', 'profile_creator', array( 
				'ajaxurl' => admin_url( 'admin-ajax.php' ), 
				'pass_key' => wp_create_nonce('rmm_add_profile'),
				'user_status' => get_query_var( 'status', 1 )
				) 
			);
		}
		

	}
	//Load only on video page
	if(get_query_var( 'profiletab', 1 ) == get_option('video_tab_name')){
		
		wp_enqueue_script( "ajax-test", plugin_dir_url( __FILE__ ) . 'js/upload.js', array( 'jquery' ) );
		
		//wp_enqueue_script( "player-controls", plugin_dir_url( __FILE__ ) . 'js/player-controls.js', array( 'jquery' ) );
		wp_enqueue_script( "status-checker", plugin_dir_url( __FILE__ ) . 'js/status-checker.js', array( 'jquery' ) );
	 	
		// make the ajaxurl var available to the above script
		wp_localize_script( 'ajax-test', 'the_ajax_script', array( 
			'ajaxurl' => admin_url( 'admin-ajax.php' ), 
			'yt_key' => get_option('API_key'),
			'pass_key' => wp_create_nonce('rmm_pass_code')
			) 
		);
		
		wp_localize_script( 'status-checker', 'status_checker', array( 
			'ajaxurl' => admin_url( 'admin-ajax.php' ), 
			'pass_key' => wp_create_nonce('rmm_check_code')
			) 
		);
	}
	
	wp_enqueue_style( 'yt-style', plugin_dir_url( __FILE__ ) . 'css/yt-style.css' );
	wp_enqueue_style( 'fontawesome', '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css' );	
}
add_action('wp_print_scripts', 'test_ajax_load_scripts');

function add_query_vars_filter( $vars ){
  $vars[] = "status";
  return $vars;
}
add_filter( 'query_vars', 'add_query_vars_filter' );

require_once('file_upload_parser.php');

include_once('plugin-config.php');
//include_once('yt-config.php');


require_once('um-qr-code-tab.php');

require_once('um-profile-purchases-tab.php');


function video_upload_section(){
	//echo admin_url( 'admin-ajax.php' );
	include_once('upload-page.php');
}
add_shortcode('video-section', 'video_upload_section');

//gets the profile image
function get_image_rul(){
	$image_url='';
	$profile_id=0;
	if ( is_ultimatemember() ) {
		$profile_id = um_profile_id();
	} 
	$image_path = wp_upload_dir()['basedir'] . '/ultimatemember/' . $profile_id .'/cover_photo.*';
	$files = glob($image_path);
	
	if($files){
		$site_url= get_site_url();
		$image_url = $site_url . '/wp-content/uploads/ultimatemember/' . $profile_id . '/' . basename($files[0]);
	}
	
	return $image_url;
}
//http://www.test.rememberme.memorial/wp-content/uploads/ultimatemember/16/cover_photo.png