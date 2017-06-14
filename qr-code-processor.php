<?php

$user_id = um_user('ID');

//echo('File name: ' . $qr_file_name);

$WP_user_id = get_current_user_id();

//echo ('Profile ID:' .  $profile_id);
//echo ('</br>User ID:' .  $user_id);
//echo ('</br>Link name:' . $link_name);
//echo ('</br>WP ser ID:' . $WP_user_id);


if($user_id == $WP_user_id){
	//echo('User Logged in.');
	global $wpdb;
        $table_qr= $wpdb->prefix . "qr_codes";
        
        if(um_user('role') == 'child') {
	        $qr_code = $wpdb->get_var( "SELECT qr_code FROM ". $table_qr . " WHERE user_id= " . $user_id);
	        //echo('QR: ' . $qr_code);
	        
	        if($qr_code == ''){
			$user_name = wp_get_current_user()->user_login;
	        	$qr_code = createQR($user_name, $user_id);
		}
		
		$image_path = wp_upload_dir()[baseurl] . "/QR_codes/" . $qr_code;
		echo('<h4>QR code to your profile video</h4>');
		echo('<p>You can test the QR code by scanning it using your mobile device</p>');
		echo('<img src="' . $image_path . '" />');
		echo('<p> You can download the QR code as an image <a href="' .$image_path. '" target="_blank" download="'.$qr_code.'"> here </a> </p>');
	}
	else if(um_user('role') == 'member') {
		$table_profiles= $wpdb->prefix . "profile_purchases";
        	$profiles = $wpdb->get_results( "SELECT child_id FROM ". $table_profiles . " WHERE member_id= " . $user_id);
        	
        	//Shsow list of profiles
		$site_url= get_site_url();
		$url_base = get_option('user_url_base');
		
		if(count($profiles)>0){
			echo('<h4>QR codes of your profiles</h4>');
			echo('<p>You can test the QR code by scanning it using your mobile device</p>');
			echo('<div class="ps-row">');
			echo('<table class="list-table">');
			foreach($profiles as $profile){
				$child_id = $profile->child_id;
				$user_data = get_userdata($child_id);
				$child_username = $user_data->user_login;
				$child_fname = $user_data->first_name;
				$child_lname = $user_data->last_name;
				
				 $qr_code = $wpdb->get_var( "SELECT qr_code FROM ". $table_qr . " WHERE user_id= " . $child_id);
			        //echo('QR: ' . $qr_code);
			       
			        if($qr_code == ''){
					$user_name = wp_get_current_user()->user_login;
			        	$qr_code = createQR($child_username, $child_id);
				}
				
				$image_path = wp_upload_dir()[baseurl] . "/QR_codes/" . $qr_code;
				
				echo('<tr>');
					echo('<td>');
						echo($child_fname .' ' . $child_lname);
						
					echo('</td>');
					echo('<td>');
						echo('<a href="'. $image_path .'" target="_blank">View</a>');
					echo('</td>');
					echo('<td>');
						echo('<a href="'. $image_path .'" download="'.$qr_code.'">Download</a></div>');
					echo('</td>');
				echo('</tr>');
			}
			echo('</table>');
			echo('</div>');
		}
		else{
			echo('<p>No QR codes found. Please <a href="/account/profiles/">create</a> a profile first.</p>');
		}
	}
}

function createQR($user_name, $user_id){

	global $wpdb;
        $table_name= $wpdb->prefix . "qr_codes";

	$qr_file_name = $user_id . '-' . $user_name . '.png';

	$qr_link = get_site_url() . '/' . get_option('user_url_base') . '/' . $user_name . '?profiletab=' . get_option('video_tab_name');
	//echo('NO CODE FOUND: ');
	        	
	$dir_path =  wp_upload_dir()[basedir] . "/QR_codes/";
	if (!file_exists($dir_path)) { mkdir($dir_path, 0777, true); }
	
	$filePath = $dir_path . $qr_file_name;
	if (file_exists($filePath)) { unlink ($filePath); }
	
	require_once('phpqrcode/qrlib.php');
	
	QRcode::png($qr_link, $filePath, QR_ECLEVEL_L, 20);
		        
	$result = $wpdb->insert($table_name, array(
		        'user_id' => $user_id,
		        'qr_code' => $qr_file_name
		)
	);
	return $qr_file_name;
}
?>




















