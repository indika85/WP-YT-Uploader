<?php 
//upload-page.php

global $ultimatemembergallery;

//$profile_id = um_profile_id();
//$user_id = um_user('ID'); 

$user_id = um_get_requested_user();
$link_name = um_user('first_name');
$WP_user_id = get_current_user_id();

//echo ('Profile ID:' .  $profile_id);
//echo ('</br>User ID:' .  $user_id);
//echo ('</br>Display name:' . $link_name);
//echo ('</br>WP ser ID:' . $WP_user_id);
//um_fetch_user( get_current_user_id() );
//echo um_user('cover_photo', 1000);
//echo ('User Role: ' . um_user('role'));
//$id = get_query_var( 'profiletab', 1 );
//echo('Page ID: ' . $id);

global $wpdb;
$table_name= $wpdb->prefix . "yt_video_uploads";
$video_count = $wpdb->get_var( "SELECT COUNT(*) FROM ". $table_name . " WHERE user_id= " . $user_id);
?>

<input type="hidden" name="video_count" id="video_count" value="<?php echo($video_count) ?>"/>

<?php
if($video_count >=1 ){
	
	$video_url="";
        $user_status='logged_out';
        
        $video_record = $wpdb->get_row( "SELECT * FROM ". $table_name . " WHERE user_id= " . $user_id);
        
        $video_title= $video_record->video_title;
        $video_description= $video_record->video_desc;
        $video_link= $video_record->video_id;
        $video_type= $video_record->video_type;
	$video_status = $video_record->video_status;
	
	if($ultimatemembergallery->can_user_be_edited()){
		$user_status='logged_in';
	}
?>	
	<input type="hidden" name="user_id" id="user_id" value="<?php echo($user_id) ?>"/>
	<input type='hidden' name='user_status' id='user_status' value='<?php echo($user_status) ?>'/>
	<input type='hidden' name='video_status' id='video_status' value='<?php echo($video_status) ?>'/>
	<input type='hidden' name='video_id' id='video_id' value='<?php echo($video_link) ?>'/>
<?php
}


if($ultimatemembergallery->can_user_be_edited()){
	//User is logged in and viewing his own profile
	
        //echo('Video count:' . $video_count);
        if($video_count == 0){
		include_once('video-upload-form.php');
	}
	else{
		include_once('video-edit-form.php');
	}
}
else{
	
	//echo("Yo are not logged in!");
	if($video_count == 0){
		echo('<h5>No video found</h5>');
	}
	else{
		include_once('video-show-form.php');
	}
}		
			
//require_once('yt-video-upload-function.php');
//$path=dirname(__FILE__). '/test.mp4';
//$videoId= upload_video($path);

//echo ("Video ID: " . $videoId);
		
?>


