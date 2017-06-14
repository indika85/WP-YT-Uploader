<?php
function process_video() {
	check_ajax_referer( 'rmm_pass_code', 'pass_key' );
	
	if ( isset($_POST['action_type']) ){
		$action_type = $_POST['action_type'];
		$user_id = $_POST['user_id'];
		$video_type = $_POST['video_type'];
		$video_title = stripslashes(strip_tags($_POST['video_name']));
		$video_desc = stripslashes(strip_tags($_POST['video_desc']));
		
		$video_status = 1;
		$ready_to_add = 0;
		
		global $wpdb;
	        $table_name= $wpdb->prefix . "yt_video_uploads";
	        
		if($action_type == 'add_video'){
			
			if($video_type == 'youtube'){
				$fileName = $_FILES["file1"]["name"]; // The file name
				$fileTmpLoc = $_FILES["file1"]["tmp_name"]; // File in the PHP tmp folder
				$fileType = $_FILES["file1"]["type"]; // The type of file it is
				$fileSize = $_FILES["file1"]["size"]; // File size in bytes
				$fileErrorMsg = $_FILES["file1"]["error"]; // 0 for false... and 1 for true
				
				if (!$fileTmpLoc) { // if file not chosen
				    echo "ERROR: Please browse for a file before clicking the upload button.";
				    exit();
				}
				$allowed =  array('mp4','mov', 'avi', 'wmv', 'flv', '3gp', 'mpeg');
				$ext = pathinfo($fileName, PATHINFO_EXTENSION);
				if(!in_array($ext, $allowed) ) {
				    echo 'ERROR: Invalid file type';
				    exit();
				}
				if($video_title =='' || $video_desc ==''){
					 echo 'ERROR: Video title and description cannot be blank';
				    	 exit();
				}
				
				$dir_name = get_template_directory() . "/temp_uploads/";
				if (!file_exists($dir_name)) { mkdir($dir_name, 0777, true); }
				
				$filePath = $dir_name . $fileName;
				if(move_uploaded_file($fileTmpLoc, $filePath)){
					
					//$filePath = dirname(__FILE__). '/test.mp4';
					
					//echo('Adding VIDEOxx');
					//die();
					//echo($video_title);
					require_once('yt-video-upload-function.php');
			
					$videoId= upload_video($filePath, $video_title, $video_desc);
					
					if($videoId !=''){
						unlink($filePath);
		        			$ready_to_add = 1;
		        			$video_status = 0;
					}
					else{
						echo('Error uploading file to YT');
						die();
					}

					//echo $videoId;
				} else {
					echo "move_uploaded_file function failed";
					die();
				}
			}
			else if($video_type == 'user_youtube' || $video_type == 'vimeo'){
				//echo(' ...Inside if YT... ');
				$videoId = trim($_POST['video_link_id']);
				$ready_to_add = 1;
			}

			//echo('Video ID: ' . $videoId . ' Video Type: ' . $video_type);
			//$wpdb->show_errors();
			
			//---------------------
			
			if($ready_to_add == 1){
				$res = $wpdb->query( $wpdb->prepare( 
					"INSERT INTO {$table_name}
					( user_id, video_type, video_title, video_desc, video_id, video_status)
					VALUES ( %d, %s, %s, %s, %s, %s )", 
				        $user_id, 
					$video_type, 
					$video_title,
					$video_desc,
					$videoId,
					$video_status
				 
				));
				echo('Video added: ' . $res);
			}
			else {
				echo('NO video added');
			}
			//echo $wpdb->print_error();
			die();
		}
		else if($action_type == 'delete_video'){
			//echo('Deleting video of: ' . $user_id);
			
			$result = $wpdb->delete($table_name, array(
					'user_id' => $user_id
					)
				);
				
			echo($result);
			die();
		}
		
		else if($action_type == 'update_video'){
			
			//echo('Updating video of: ' . $user_id);
			$result = $wpdb->query( $wpdb->prepare( 
					"UPDATE {$table_name}
					SET video_title= %s, video_desc=%s 
					WHERE user_id=%d", 
					$video_title,
					$video_desc,
					$user_id
				 
				));
				
			echo($result);
			die();
		}
	}
	else{
		echo "Required parameters not present";
		die();
	}
}
function get_status(){
	check_ajax_referer( 'rmm_check_code', 'pass_key' );
	if ( isset($_POST['video_id']) ){
	
		$video_id = $_POST['video_id'];
		require_once('yt-video-upload-function.php');
	
		$res = get_processing_status($video_id);
		//echo($res['items'][0]["processingDetails"]["processingStatus"]);
		
		$response = array(
			'processingStatus' => $res['items'][0]["processingDetails"]["processingStatus"],
			'partsTotal' => $res['items'][0]["processingDetails"]["processingProgress"]["partsTotal"],
			'partsProcessed' => $res['items'][0]["processingDetails"]["processingProgress"]["partsProcessed"],
			'timeLeftMs' => $res['items'][0]["processingDetails"]["processingProgress"]["timeLeftMs"],
			'processingFailureReason' => $res['items'][0]["processingDetails"]["processingFailureReason"]
		);
		
		//$response = array(
		//	'processingStatus' => 'processing',
		//	'partsTotal' => 1000,
		//	'partsProcessed' => 620,
		//	'timeLeftMs' => 266500,
		//	'processingFailureReason' => 'Something'
		//);
		
		//echo($video_id);
		echo json_encode($response);
		die();
	}
	else{
		echo "Video id not present";
		die();
	}
}

function update_status(){
	check_ajax_referer( 'rmm_check_code', 'pass_key' );
	if ( isset($_POST['user_id']) ){
	
		$user_id = $_POST['user_id'];
		
		global $wpdb;
	        $table_name= $wpdb->prefix . "yt_video_uploads";
	        
	        $result = $wpdb->update($table_name, array(
				'video_status' => 1 
				), 
				array(
					'user_id' => $user_id
				)
			);
		
	        echo($result);
		die();
	}
	else{
		echo "Userid not present";
		die();
	}
}

function create_profile(){
	check_ajax_referer( 'rmm_add_profile', 'pass_key' );
	if ( isset($_POST['username']) && isset($_POST['password']) && isset($_POST['fname']) && isset($_POST['lname'])){
		$username = trim(filter_var($_POST['username'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH));
		
		//echo('Test');

		//require_once ( ABSPATH . 'wp-includes/class-phpass.php');
		//$wp_hasher = new PasswordHash( 8, TRUE );
		if($_POST['create_pass'] == 1){
			$password = $_POST['password'];
		}
		else{
			$password = get_option('rand_pass', 'h&jns58_@Nk8@#j!');
		}
		
		//echo($_POST['create_pass'] . " - " . $password); 
		//die();
		//$hashed_password = $wp_hasher->HashPassword( $password );

		//echo $hashed_password;
		//die();

		$fname= trim(filter_var($_POST['fname'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH));
		$lname= trim(filter_var($_POST['lname'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH));
		
		$userdata = array(
		    'user_login'  =>  $username,
		    'user_pass'   =>  $password,  // When creating an user, `user_pass` is expected.
		    'first_name' => $fname,
		    'last_name' => $lname
		);
		
		$child_id = wp_insert_user( $userdata ) ;
		global $ultimatemember;
		//On success
		if ( ! is_wp_error( $child_id) ) {
			
			um_fetch_user( $child_id );
			// Change user role
			$ultimatemember->user->set_role('private-child');
			
			um_fetch_user( $child_id );
			//Approve user
 			$ultimatemember->user->approve();
			//echo "User created : ". $child_id;
			
			global $wpdb;
	        	$table_name= $wpdb->prefix . "profile_purchases";
	        	
	        	$res = $wpdb->insert($table_name, array(
					'member_id' => get_current_user_id(),
					'child_id' => $child_id
				));
			if($res >0 ){
				echo "1";
			}
			else{
				echo "0";
			}
			um_reset_user();
		}
		else{
			echo "0";
		    	//echo "Error creating profile: " . $user_id;
		}

		//echo('Hello');
		die();
	}
	else{
		echo "Required parameters not present";
		die();
	}
}

function change_profile_status(){
	check_ajax_referer( 'rmm_add_profile', 'pass_key' );
	if ( isset($_POST['childid']) && isset($_POST['role'])){
		$child_id = $_POST['childid'];
		$role = $_POST['role'];
		
		//echo('Test');
		global $ultimatemember;
		um_fetch_user($child_id);
		
		if ($role=='child') {
			$ultimatemember->user->set_role('private-child');	
		}
		else if ($role=='private-child') {
			$ultimatemember->user->set_role('child');
		}
		
		um_reset_user();
		echo('changed- '.$child_id. ' to ' . $role);
		die();
	}
	else{
		echo "Required parameters not present";
		die();
	}
}
function check_username(){
	check_ajax_referer( 'rmm_add_profile', 'pass_key' );
	if ( isset($_POST['username']) ){
		$username = trim(filter_var($_POST['username'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH));
		
		if ( username_exists( $username ) )
	           echo "0"; //"Username In Use";
	        else
	           echo "1";//"Username Not In Use";
		
		die();
	}
	else{
		echo "Username not present";
		die();
	}
}

add_action('wp_ajax_process_video', 'process_video');
add_action('wp_ajax_nopriv_process_video', 'process_video');

add_action('wp_ajax_get_status', 'get_status');
add_action('wp_ajax_nopriv_get_status', 'get_status');

add_action('wp_ajax_update_status', 'update_status');
add_action('wp_ajax_nopriv_update_status', 'update_status');

add_action('wp_ajax_create_profile', 'create_profile');
add_action('wp_ajax_nopriv_create_profile', 'create_profile');

add_action('wp_ajax_change_profile_status', 'change_profile_status');
add_action('wp_ajax_nopriv_change_profile_status', 'change_profile_status');

add_action('wp_ajax_check_username', 'check_username');
add_action('wp_ajax_nopriv_check_username', 'check_username');
?>
