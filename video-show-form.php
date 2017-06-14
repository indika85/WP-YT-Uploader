<!-- Show video form -->
<?php
     	//Variables from upload-page.php   
        if($video_type=='youtube'){
        	$video_url = "//www.youtube.com/embed/" . $video_link . "?rel=0&amp;controls=0&amp;enablejsapi=1;showinfo=0";
        }
        else if($video_type=='user_youtube'){
        	$video_url ="//www.youtube.com/embed/" . $video_link ;
        }
        else if($video_type=='vimeo'){
        	$video_url ="//player.vimeo.com/video/" . $video_link ;
        }
        //$video_url="https://player.vimeo.com/video/58385453";
        //echo('Title is: ' . $video_description);
        
        if($video_type==''){
        	$video_title ="No video found";
        }
?>
<div id="video-content-section">
	<div class="video-title">
		<h5> <?php echo($video_title) ?> </h5>
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
		<p> <?php echo($video_description) ?> </p>
	</div>
</div>