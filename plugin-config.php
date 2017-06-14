<?php
add_action( 'admin_init', 'wp_yt_plugin_settings' );

function wp_yt_plugin_settings() {
	register_setting( 'wp-yt-plugin-settings-group', 'client_ID' );
	register_setting( 'wp-yt-plugin-settings-group', 'client_secret' );
	register_setting( 'wp-yt-plugin-settings-group', 'app_name' );
	register_setting( 'wp-yt-plugin-settings-group', 'redirect_URI' );
	register_setting( 'wp-yt-plugin-settings-group', 'refresh_token' );
	register_setting( 'wp-yt-plugin-settings-group', 'API_key' );
	register_setting( 'wp-yt-plugin-settings-group', 'max_number_of_images' );
	register_setting( 'wp-yt-plugin-settings-group', 'user_url_base' );
	register_setting( 'wp-yt-plugin-settings-group', 'video_tab_name' );
	register_setting( 'wp-yt-plugin-settings-group', 'rand_pass' );
	
	create_table_video();
	create_table_QR();
	create_table_purchases();
}

add_action('admin_menu', 'wp_yt_plugin_menu');
 
function wp_yt_plugin_menu(){
 	add_menu_page('WP YouTube Plugin Settings', 'RMM SETTINGS', 'administrator', 'rmm-plugin-settings', 'wp_yt_plugin_settings_page', 'dashicons-admin-generic');
 	add_submenu_page ( 'rmm-plugin-settings', 'WP YouTube Plugin Settings', 'Settings', 'administrator', 'rmm-plugin-settings', 'wp_yt_plugin_settings_page' );
 	//add_submenu_page ( 'wp-yt-plugin-settings', 'WP YouTube Plugin Settings', 'Upload', 'administrator', 'wp-yt-plugin-upload', 'wp_yt_plugin_upload_page' );
}

function wp_yt_plugin_upload_page(){
	include_once('upload-page.php');
}

function wp_yt_plugin_settings_page() { 
	include_once('settings-page.php');	
}

function create_table_video(){

        global $wpdb;
        $table_name= $wpdb->prefix . "yt_video_uploads";
        
        //$wpdb->hide_errors();
        if ($wpdb->get_var("show tables like '$table_name'") != $table_name) {
	        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	        $collate = '';
	        
	        if ( $wpdb->has_cap( 'collation' ) ) {
	            if ( ! empty( $wpdb->charset ) ) {
	                $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
	            }
	            if ( ! empty( $wpdb->collate ) ) {
	                $collate .= " COLLATE $wpdb->collate";
	            }
	        }
	        
	        $sql = 'CREATE TABLE ' . $table_name . ' (
	                  id bigint(20) NOT NULL auto_increment,
	                  user_id bigint(20) NOT NULL,
	                  video_type varchar(20) NOT NULL,
	                  video_title varchar(200) NOT NULL,
	                  video_desc longtext NOT NULL,
	                  video_id varchar(100) NOT NULL,
	                  video_status boolean,
	                  PRIMARY KEY  (id)
	                ) '.$collate.';';
				  
	        
	        dbDelta( $sql );
        }
}

function create_table_QR(){
	global $wpdb;
        $table_name= $wpdb->prefix . "qr_codes";
        
        //$wpdb->hide_errors();
        if ($wpdb->get_var("show tables like '$table_name'") != $table_name) {
	        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	        $collate = '';
	        
	        if ( $wpdb->has_cap( 'collation' ) ) {
	            if ( ! empty( $wpdb->charset ) ) {
	                $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
	            }
	            if ( ! empty( $wpdb->collate ) ) {
	                $collate .= " COLLATE $wpdb->collate";
	            }
	        }
	        
	        $sql = 'CREATE TABLE ' . $table_name . ' (
	                  id bigint(20) NOT NULL auto_increment,
	                  user_id bigint(20) NOT NULL,
	                  qr_code varchar(200),
	                  PRIMARY KEY  (id)
	                ) '.$collate.';';
				  
	        
	        dbDelta( $sql );
        }
}
function create_table_purchases(){
	global $wpdb;
        $table_name= $wpdb->prefix . "profile_purchases";
        
        //$wpdb->hide_errors();
        if ($wpdb->get_var("show tables like '$table_name'") != $table_name) {
	        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	        $collate = '';
	        
	        if ( $wpdb->has_cap( 'collation' ) ) {
	            if ( ! empty( $wpdb->charset ) ) {
	                $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
	            }
	            if ( ! empty( $wpdb->collate ) ) {
	                $collate .= " COLLATE $wpdb->collate";
	            }
	        }
	        
	        $sql = 'CREATE TABLE ' . $table_name . ' (
	                  id bigint(20) NOT NULL auto_increment,
	                  member_id bigint(20) NOT NULL,
	                  child_id bigint(20) NOT NULL,
	                  PRIMARY KEY  (id)
	                ) '.$collate.';';
				  
	        
	        dbDelta( $sql );
        }
}
?>