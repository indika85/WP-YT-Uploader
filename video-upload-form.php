<!-- video upload form -->
<div id="upload-selector-section">
	<h4>Link or upload a video to your profile.</h4>
	<div id="link-youtube" class="upload-selector-option">Link a YouTube video</div>
	<div id="link-vimeo" class="upload-selector-option">Link a Vimeo video</div>
	<div id="upload-youtube" class="upload-selector-option">Upload video file to Remember Me</div>
</div>

<div id="yt-video-upload-sction" style="display:none" title="Upload video to Remember Me">
	<div id="yt-upload-form">
		<form id="upload_form" enctype="multipart/form-data" method="post"">
			<input type="hidden" name="action" value="process_video"/>
			<input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id ?>"/>
			
			<p>Video Title: </p>
			<input type="text" name="video_name" id="video_name"><br>
			
			<p>Description:</p>
			<textarea name="video_desc" id="video_desc" rows="4" cols="50"> </textarea><br>
		  	
		  	<div id="file-select-section" style="display:none" class="videos-link-section">
			  	<p>File to upload: <i><small>(Accepted file formats: MOV, MP4, AVI, WMV, FLV, 3GP, MPEG)</i></small><p> 
			  	<input type="file" name="file1" id="file1" accept=".mp4,.mov, .avi, .flv, .mpeg"> <br>
		  	</div>
		  	<div id="youtub-select-section" style="display:none" class="videos-link-section">
		  		<p>YouTube video ID: </p>
				<input type="text" name="youtube_video_id" id="youtube_video_id"><br>
				<img src="http://www.test.rememberme.memorial/wp-content/uploads/2015/12/youtube-link.jpg">
		  	</div>
		  	<div id="vimeo-select-section" style="display:none" class="videos-link-section">
		  		<p>Vimeo video ID: </p>
				<input type="text" name="vimeo_video_id" id="vimeo_video_id"><br>
				<img src="http://www.test.rememberme.memorial/wp-content/uploads/2015/12/vimeo-link.jpg">
		  	</div>
		  	<input type="button" id="btn" value="Upload File">
		</form>
	</div>
	<div id="message-section">
		<h4 id="message">Ready to upload</h4>
	</div>
	
	<div id="progress-section" style="display:none">
		<div id="myProgress" >
		  <div id="myBar">
		  	<div id="label">%</div>
		  </div>
		</div>
		<div id="cancel-upload">Cancel Upload</div>
	</div>
	
	<div id="processing-section" style="display:none">
		<div class="dummy"></div>
	
	  	<div class="img-container">
			<img src="<?php echo plugin_dir_url( __FILE__ ) . 'img/processing.gif'; ?>">
		</div>
	</div>
	
	<div id="dialog-confirm" title="Cancel Upload ?" style="display:none">
	  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Are you sure you want to cancel the upload?</p>
	</div>
</div>



