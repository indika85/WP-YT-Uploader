<!-- Video upload from -->
<div id="yt-upload-form">
	<form id="upload_form" enctype="multipart/form-data" method="post"">
		<input type="hidden" name="action" value="process_video"/>
		<input type="hidden" name="video_type" id="video_type" value="youtube"/>
		<input type="hidden" name="user_id" id="user_id" value="<?php echo get_current_user_id() ?>"/>
		
		<p>Video Title: </p>
		<input type="text" name="video_name" id="video_name"><br>
		
		<p>Description:</p>
		<textarea name="video_desc" id="video_desc" rows="4" cols="50"> </textarea><br>
	  	
	  	<p>File to upload: (Accepted file formats: MOV, MP4, AVI, WMV, FLV, 3GP, MPEGPS)<p> 
	  	<input type="file" name="file1" id="file1" accept=".mp4,.mov, .avi, .flv, .mpeg"> <br>
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


