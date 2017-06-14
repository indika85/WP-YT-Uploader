<!-- Video edit/ delete form -->
<?php
        //Variable from uload-page.php
        if($video_type=='youtube'){
        	$video_url = "//www.youtube.com/embed/" . $video_link . "?rel=0&amp;controls=0&amp;enablejsapi=1;showinfo=0";
        }
        else if($video_type=='user_youtube'){
        	$video_url ="//www.youtube.com/embed/" . $video_link ;
        }
        else if($video_type=='vimeo'){
        	$video_url ="//player.vimeo.com/video/" . $video_link ;
        }
        //echo('Title is: ' . $video_description);
?>

<div id="video-content-section">
	<div class="video-title">
		<input type="text" class="clean-content" id="video_name" name="video_name" value="<?php echo($video_title) ?>" style="display:none" />
		<h5 id="video_name_lable"> <?php echo($video_title) ?> </h5>
	</div>
	
	<!-- video player goes here -->
	<?php 
		if ($video_type=='youtube') {
			require_once('yt-player.php'); 
		}
		else if ($video_type=='user_youtube' || $video_type=='vimeo'){
			echo('<iframe width="100%" height="400px" src=" ' . $video_url . '" frameborder="0" allowfullscreen style="border: solid 3px #73afe7"></iframe>');
		}
	?>
	
	<div class="video-description">
		<textarea name="video_desc" id="video_desc" rows="4" cols="50" style="display:none"> <?php echo($video_description) ?> </textarea><br>
		<p id="video_desc_lable"> <?php echo($video_description) ?> </p>
	</div>
	<div class="button-section">
		<div class="button-ele"><input type="button" id="delete-btn" value="Delete Video"></div>
		<div class="button-ele"><input type="button" id="update-btn" value="Edit Content"></div>
		<div class="button-ele"><input type="button" id="cancel-btn" value="Cancel" style="display:none"></div>
	<div>
</div>

<div id="dialog-confirm" title="DELETE VIDEO ?" style="display:none">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Are you sure you want to DELETE the video?</p>
  <p>This will remove the video from our server. You will be able to upload a new video once you delete. </p>
</div>