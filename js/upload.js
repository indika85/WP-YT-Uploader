jQuery(document).ready( function($) {
	console.log('Upload functions ready5');
	var xhr = new window.XMLHttpRequest();
	var video_type='';
	var wait_amount=1000;
	var uploading = false;
	//xxx();
	
	$(window).bind('beforeunload', function(){
		if(uploading){
  			return 'If you leave this page your upload will be canceled.';
  		}
	});
	
	$('#upload-youtube').on('click', function(){
		//console.log('Clicked');
		$('#file-select-section').show();
		video_type = 'youtube';
		$('#message').html('Ready to upload your video');
		$('#btn').val('Upload video file');
		$( "#yt-video-upload-sction" ).dialog({
		      resizable: false,
		      height: "auto",
		      width: 600,
		      modal: true,
		      beforeClose: function( event, ui ) { $('#file-select-section').hide(); }
		});
	}),
	$('#link-youtube').on('click', function(){
		//console.log('YouTube section Clicked');
		
		$('#youtub-select-section').show();
		video_type = 'user_youtube';
		$('#message').html('Ready to link your YouTube video');
		$('#btn').val('Link YouTube video');
		$( "#yt-video-upload-sction" ).dialog({
		      resizable: false,
		      height: "auto",
		      width: 600,
		      modal: true,
		      beforeClose: function( event, ui ) { $('#youtub-select-section').hide(); }
		});
	}),
	$('#link-vimeo').on('click', function(){
		//console.log('Vimeo section Clicked11');
		$('#vimeo-select-section').show();
		video_type = 'vimeo';
		$('#message').html('Ready to link your Vimeo video');
		$('#btn').val('Link Vimeo video');
		$( "#yt-video-upload-sction" ).dialog({
		      resizable: false,
		      height: "auto",
		      width: 600,
		      modal: true,
		      beforeClose: function( event, ui ) { $('#vimeo-select-section').hide(); }
		});
	}),
	$('#btn').on('click', function(e) {
            	var youtube_video_id = $.trim($('#youtube_video_id').val());
            	var vimeo_video_id = $.trim($('#vimeo_video_id').val());
            	
            	if(video_type == 'youtube'){
	            	if( !$('#file1').val()){
	            		$('#message').html('Please select a file to upload');
	            		return;
	            	}
            	}
            	else if (video_type == 'user_youtube'){
            		if( !youtube_video_id){
            			//console.log('Val');
	            		$('#message').html('Please enter the YouTube video ID');
	            		$('#youtube_video_id').focus();
	            		return;
	            	}
	            	//console.log('THIS: ' + validate_youtube_ID($('#youtube_video_id').val()));
	            	if(validate_youtube_ID(youtube_video_id) != 1){
	            		$('#message').html('Video ID you entered is not a valid YouTube video ID');
	            		$('#youtube_video_id').focus();
	            		return;
	            	}
            	}
            	else if (video_type == 'vimeo'){
            		if( !vimeo_video_id){
	            		$('#message').html('Please enter the Vimeo video ID');
	            		$('#vimeo_video_id').focus();
	            		return;
	            	}
	            	//console.log('THIS: ' + validate_vimeo_ID(vimeo_video_id));
	            	if(validate_vimeo_ID(vimeo_video_id) != vimeo_video_id){
	            		$('#message').html('Video ID you entered is not a valid Vimeo video ID');
	            		$('#vimeo_video_id').focus();
	            		return;
	            	}
            	}
            	if( !$('#video_name').val().trim()){
            		$('#message').html('Please enter a video title');
            		$('#video_name').focus();
            		return;
            	}
            	if( !$('#video_desc').val().trim()){
            		$('#message').html('Please enter a video description');
            		$('#video_desc').focus();
            		return;
            	}
            	
		
		var fd = new FormData();
		if(video_type == 'youtube'){
			var file = $('#file1')[0].files[0];
			//alert(file.name+" | "+file.size+" | "+file.type);    
			fd.append( 'file1', file );
			wait_amount = 10000;
			$('#message').html('Uploading your video.. Do not refresh or navigate away from the page');
		}
		else if(video_type == 'user_youtube'){
			fd.append( 'video_link_id', youtube_video_id );
			$('#message').html('Linking your YouTube video...');
		}
		else if(video_type == 'vimeo'){
			fd.append( 'video_link_id', vimeo_video_id );
			$('#message').html('Linking your Vimeo video...');
		}
		fd.append( 'video_name', $('#video_name').val() );
		fd.append( 'video_desc', $('#video_desc').val() );
		fd.append( 'video_type', video_type );
		fd.append( 'user_id', $('#user_id').val() );
		fd.append( 'action_type', 'add_video' );
		
		fd.append('action', 'process_video');
		fd.append('pass_key', the_ajax_script.pass_key);
		
            	$('#yt-upload-form').hide('slow', function(){
            		if(video_type == 'youtube'){
            			$('#progress-section').show('slow');
            		}
            	});
		uploading  = true;
		$('.ui-dialog-titlebar-close').hide();
		// the_ajax_script.ajaxurl is a variable that will contain the url to the ajax processing file
	 	jQuery.ajax({
	 	  xhr: function() {
		        //var xhr = new window.XMLHttpRequest();
		        xhr.upload.addEventListener("progress", function(evt) {
		            if (evt.lengthComputable) {
		                var percentComplete = Math.round((evt.loaded / evt.total)*100);
		                //console.log(percentComplete);
		                var elem = document.getElementById("myBar");
		                elem.style.width = percentComplete + '%';
		                document.getElementById("label").innerHTML = percentComplete * 1 + '%';
		                
		                if(percentComplete >= 100){
		                	$('#message').html('Please wait while we process your video...');
		                	if(video_type == 'youtube'){
			                	$('#progress-section').hide('slow', function(){
			                		$('#processing-section').show('slow');
			                	});
		                	}
		                }
		            }
		       }, false);
		       return xhr;
		  },
		  url: the_ajax_script.ajaxurl,
		  data: fd,
		  type:'POST',
		  success: function(data){
		    uploading = false;
		    console.log(data);
		    $('#message').html('Finalizing and updating you profile...');
		    setTimeout ( function(){
		    	$('#message').html('We will continue processing your video in the background. Your page will reload shortly.');
		    	setTimeout (function(){
		    		$('#processing-section').hide('slow', function(){
		    		$('#message').hide('slow');
		    		uploading = false;
		    		$('.ui-dialog-titlebar-close').show();
		    		location.reload();
		    	});
		    	},2000);
		    	//console.log('Complete');
		    }, wait_amount );
		    
		  },
		  error: function(jqXHR, textStatus, errorThrown) {
		  	uploading = false;
		  	$('.ui-dialog-titlebar-close').show();
		  	$('#message').html('There was an error uploading your data. Please try again.');
		  	console.log('Error status: ' + textStatus);
		  	console.log('Error thrown: ' + errorThrown);
		  },
		  cache: false,
		  contentType: false,
		  processData: false
		});
	
	}),
	$("#file1").on('change', function(){
		var ext = $(this).val().split('.').pop().toLowerCase();
		if($.inArray(ext, ['mp4','mov','flv','mpeg', 'avi']) == -1) {
		    //alert('invalid extension!');
		    $(this).val('');
		    $('#message').html('Please select a valid video file to upload');
		}
		//alert(ext);
	}),
	$('#cancel-upload').on('click', function(){
		//alert('Clicked');
		$( "#dialog-confirm" ).dialog({
		      resizable: false,
		      height: "auto",
		      width: 400,
		      modal: true,
		      buttons: {
		        "Yes": function() {
		        	xhr.abort();
				$('#message').html('Please wait while we remove temporary files on the server.');
				$("myBar").width='10%';
				$('#lable').html('%');
				$('#progress-section').hide('slow', function(){
					$('#yt-upload-form').show('slow');
					location.reload();
					
				});
		          	$( this ).dialog( "close" );
		        },
		        no: function() {
		          $( this ).dialog( "close" );
		        }
		      }
		});
	}),
	
	$('#delete-btn').on('click', function(){
		$( "#dialog-confirm" ).dialog({
		      resizable: false,
		      height: "auto",
		      width: 400,
		      modal: true,
		      buttons: {
		        "DELETE": function() {
				//console.log('Deleting video');
				var user_id = $('#user_id').val();
				
				$(this).val('Deleting...');
            			$(this).prop("disabled",true);
            	
				jQuery.ajax({
			 	  url: the_ajax_script.ajaxurl,
				  data: {
					action : 'process_video',
					user_id : user_id,
					action_type: 'delete_video',
					pass_key: the_ajax_script.pass_key
				},
				  type:'POST',
				  success: function(data){
				 	if(data == 1){
				 		location.reload();
				 		$(this).val('Deleting...');
            					
				   	}
					else{
					 	alert('There was an error deleting the video. Please try again later ');
					 	$(this).val('Delete Video');
					 	$(this).prop("disabled",false);
					}
				    
				  }
				});
				$( this ).dialog( "close" );
		        },
		        "Cancel": function() {
		          $( this ).dialog( "close" );
		        }
		      }
		});
	}),
	$('#cancel-btn').on('click', function(){
		$('#video_name').hide(function(){$('#video_name_lable').fadeIn();});
		$('#video_desc').hide(function(){$('#video_desc_lable').fadeIn();});
			
		$('#update-btn').val('Edit Content');
		$(this).hide();
	}),
	$('#update-btn').on('click', function(){
		console.log('Update clicked');
		
		if($(this).val()=='Edit Content'){
			$('#video_name_lable').hide(function(){$('#video_name').fadeIn();});
			$('#video_desc_lable').hide(function(){$('#video_desc').fadeIn();});
			$('#cancel-btn').show();
			$(this).val('Update Content');
			
			return;
		}

		
		var video_name = $('#video_name').val().trim();
		var video_desc = $('#video_desc').val().trim();
		if( video_name == ''){
            		alert('Please enter a video title');
            		return;
            	}
            	if( video_desc==''){
            		alert('Please enter a video description');
            		return;
            	}
            	var user_id = $('#user_id').val();
            	
            	$(this).val('Updating...');
            	$(this).prop("disabled",true);
            	
		jQuery.ajax({
			url: the_ajax_script.ajaxurl,
			data: {
				action : 'process_video',
				user_id : user_id,
				video_name : video_name,
				video_desc : video_desc,
				action_type: 'update_video',
				pass_key: the_ajax_script.pass_key
			},
			type:'POST',
			success: function(data){
				 if(data == 1){
				 	$(this).val('Update Content');
				 	location.reload();
				 }
				 else{
				 	console.log(data);
				 	$('#update-btn').val('Update Content');
				 	$('#update-btn').prop("disabled",false);
				 	location.reload();
				 	//alert('There was an error updating the video. Please try again later ');
				 	
				 }
				    
			}
		});
	});
	function validate_youtube_ID(videoID){
		//console.log('Inside function: ' + videoID);

		var apiKey = the_ajax_script.yt_key;
		var results;
		$.get({
			url: 'https://www.googleapis.com/youtube/v3/videos?id=' + videoID + '&key=' + apiKey + '&part=id', 
			async: false,
			success: function(response) {
			    //console.log(response.pageInfo.totalResults);
			    results = response.pageInfo.totalResults;
			}
		});
		
		return results;
	}
	function validate_vimeo_ID(videoID){
		//console.log('Inside function: ' + videoID);

		var results;
		$.get({
			url: 'https://vimeo.com/api/oembed.json?url=https://vimeo.com/' + videoID, 
			async: false,
			success: function(response) {
			    //console.log(response.pageInfo.totalResults);
			    results = response.video_id;
			}
		});
		
		return results;
	}	
})






