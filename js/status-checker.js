jQuery(document).ready( function($) {
	var video_count = $('#video_count').val();
	console.log('Video count: ' + video_count);
	if(video_count == 0){
		return;
	}
	
	var status_check_interval=null;
	
	var user_status = $('#user_status').val();
	var video_status = $('#video_status').val();
	//var video_status = 0;
	var video_id = $('#video_id').val();
	var user_id = $('#user_id').val()
	
	//console.log('User status1: ' + user_status);
	//console.log('Video ID1: ' + video_id);
	

	if(video_status == 1){
		$('#yt-video-upload-sction').hide();
		$('#video-player-section').show();
		return;
	}
	else if(video_status == 0){
		//Video is still processing
		console.log('Video still processing');
		
		if(user_status == 'logged_out'){
			$('#processing-section').hide();
			$('#progress-section').hide();
			$('#time-remaining').hide();
			$('#message').text('We are still processing the video. If you are the account owner, please log-in to see the progress');
		}
		else if(user_status == 'logged_in'){
			$('#time-remaining').show();
			//Call check_status for the first time then it waill be called every 5 seconds
			check_status();
			status_check_interval = setInterval(function () {
			        check_status();
			}, 10000);
		}
	}
	function check_status(){
		console.log('Checking status');
		$.ajax({
				url: status_checker.ajaxurl,
				type:'POST',
				dataType : 'json',
				data:{
					action:'get_status',
					pass_key:status_checker.pass_key,
					video_id:video_id
				},
				success: function( data ) {
					console.log('Call returned: ' + data.partsTotal);
					if(data.processingStatus == 'succeeded'){
						update_status();
					}
					else if(data.processingStatus == 'processing'){
						var partsTotal = data.partsTotal;
						var partsProcessed = data.partsProcessed;
						var timeLeftMs = data.timeLeftMs;
						//console.log('partsTotal: '+ partsTotal);
						//console.log('partsProcessed: '+ partsProcessed);
						//console.log('timeLeftMs: '+ timeLeftMs);
						update_progress(partsTotal, partsProcessed, timeLeftMs);
					}
					else if(data.processingStatus == 'failed'){
						var processingFailureReason = data.processingFailureReason;
						clearInterval(status_check_interval);
						$('#progress-section').fadeOut('slow');
						$('#time-remaining').hide();
						$('#message').text('Error: ' + processingFailureReason);
					}
					else{
						clearInterval(status_check_interval);
						$('#message').text('Unknown status: ' + data.processingStatus);
					}
				}
		});
	}
	function update_progress(partsTotal, partsProcessed, timeLeftMs){
		if(partsTotal == 0 || partsTotal==''){
			partsTotal =100;
		}
		if(partsProcessed == 0 || partsProcessed ==''){
			partsProcessed =10;
		}
		if(timeLeftMs ==''){
			timeLeftMs = 0;
		}
		
		var percentComplete = Math.round(( parseInt(partsProcessed) /  parseInt(partsTotal))*100);
		//console.log(percentComplete);
		var elem = document.getElementById("myBar");
		elem.style.width = percentComplete + '%';
		document.getElementById("label").innerHTML = percentComplete * 1 + '%';
		
		var timeRemaining = msToTime(timeLeftMs);
		$('#time-remaining').text('Estimated time remaining: ' + timeRemaining);
	}
	
	function msToTime(duration) {
	    var milliseconds = parseInt((duration%1000)/100)
	        , seconds = parseInt((duration/1000)%60)
	        , minutes = parseInt((duration/(1000*60))%60)
	        , hours = parseInt((duration/(1000*60*60))%24);
	
	    hours = (hours < 10) ? "0" + hours : hours;
	    minutes = (minutes < 10) ? "0" + minutes : minutes;
	    seconds = (seconds < 10) ? "0" + seconds : seconds;
	
	    return hours + " hours " + minutes + " minutes " + seconds + " seconds";// + milliseconds;
	}

	function update_status(){
		console.log('Updating status');
		$.ajax({
				url: status_checker.ajaxurl,
				type:'POST',
				data:{
					action:'update_status',
					pass_key:status_checker.pass_key,
					user_id:user_id
				},
				success: function( response ) {
					console.log('Call returned1: ' + response);
					clearInterval(status_check_interval);
					//location.reload();
					$('#progress-section').fadeOut('slow');
					$('#time-remaining').hide();
					$('#processing-section').hide('slow', function(){
						$('#message').text('Almost done!');
						setTimeout(function(){ 
							$('#message').text('Processing complete. Please refresh the page to see the video');
						}, 5000);
						
					});
					
				}
			});
	}
})