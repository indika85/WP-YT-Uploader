<div id="video-player-section" class="video-player" style="display:none">
		<iframe id="yt-player" width="100%" height="400px" src="<?php echo($video_url) ?>" frameborder="0" allowfullscreen style="border: solid 3px #73afe7"></iframe>
		<div class="player-controls">
			<div class="player-controls-row">
				<div id="play"><i id="play-button" class="fa fa-play" aria-hidden="true"></i></div>
				<div id="current-time">2:26</div>
				<div id="duration">5:34</div>
				<div id="volume-section">
					<i id="vol-icon" class="fa fa-volume-up" aria-hidden="true"></i> 
					<input type="range" id="volume" name="volume" min="0" max="100">
				</div>
			</div>
			<div class="progress-slider-row">
				<input type="range" id="progress-slider" name="progress-slider" min="0" max="100">
			</div>
		</div>
</div>

<div id="yt-video-upload-sction">

	<div id="message-section">
		<h4 id="message">We are still processing your video. Processing time depends on the video lenght.</h4>
		<h5 style="display:none" id="time-remaining">xxx</h5>
	</div>
	
	
	<div id="processing-section" >
		<div class="dummy"></div>
	
	  	<div class="img-container">
			<img src="<?php echo plugin_dir_url( __FILE__ ) . 'img/processing.gif'; ?>">
		</div>
	</div>
	<div id="progress-section" >
		<div id="myProgress" >
		  <div id="myBar">
		  	<div id="label">%</div>
		  </div>
		</div>
	</div>
</div>


<script>
      var tag = document.createElement('script');

	  tag.id = 'iframe-demo';
	  tag.src = 'https://www.youtube.com/iframe_api';
	  var firstScriptTag = document.getElementsByTagName('script')[0];
	  firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

      var player;
      function onYouTubeIframeAPIReady() {
      	console.log('Player controls are ready');
        player = new YT.Player('yt-player', {
          events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
          }
        });
      }


      function onPlayerReady(event) {
      	//console.log('Event is ready');
      	document.getElementById('yt-player').style.borderColor = '#FF6D00';
      	
      	// Update the controls on load
	    updateTimerDisplay();
	    updateProgressBar();
	
	    // Clear any old interval.
	    //clearInterval(time_update_interval);
	
	    // Start interval to update elapsed time display and
	    // the elapsed part of the progress bar every second.
	    var time_update_interval = setInterval(function () {
	        updateTimerDisplay();
	        updateProgressBar();
	    }, 1000);
      	//document.getElementById('yt-player').contentWindow.document.getElementsByClassName('ytp-watermark')[0].style.display = 'none';

      }
	
	function updateTimerDisplay(){
	    // Update current time text display.
	    jQuery('#current-time').text(formatTime( player.getCurrentTime() ));
	    jQuery('#duration').text(' / ' + formatTime( player.getDuration() ));
	}
	
	function formatTime(time){
	    time = Math.round(time);
	
	    var minutes = Math.floor(time / 60),
	    seconds = time - minutes * 60;
	
	    seconds = seconds < 10 ? '0' + seconds : seconds;
	
	    return minutes + ":" + seconds;
	}
	function updateProgressBar(){
	    // Update the value of our progress bar accordingly.
	    jQuery('#progress-slider').val((player.getCurrentTime() / player.getDuration()) * 100);
	}

	jQuery('#progress-slider').on('mouseup touchend', function (e) {

	    // Calculate the new time for the video.
	    // new time in seconds = total duration in seconds * ( value of range input / 100 )
	    var newTime = player.getDuration() * (e.target.value / 100);
	
	    // Skip video to new time.
	    player.seekTo(newTime);
	
	});

      var done = false;
      function onPlayerStateChange(event) {
        //console.log(event.data);
        if(event.data == 1){
	    	jQuery('#play-button').removeClass('fa-play');
	    	jQuery('#play-button').addClass('fa-pause');      
        }
        else if (event.data == 2 || event.data == 0){
 		jQuery('#play-button').removeClass('fa-pause');
	    	jQuery('#play-button').addClass('fa-play');       
        }
      }
      
      jQuery('#volume').on('change', function () {
	    player.setVolume(jQuery(this).val());
	});
	
      function stopVideo() {
        player.stopVideo();
      }
      
      jQuery('#vol-icon').on('click', function() {
      	if(jQuery(this).hasClass('fa-volume-up')){
      		jQuery(this).removeClass('fa-volume-up');
      		jQuery(this).addClass('fa-volume-off');
      		player.mute();
      	}
      	else if(jQuery(this).hasClass('fa-volume-off')){
      		jQuery(this).removeClass('fa-volume-off');
      		jQuery(this).addClass('fa-volume-up');
      		player.unMute();
      	}
      });

      jQuery('#play').on('click', function () {
      	//console.log('clicked');
	    if(player)
	    {
	    	if(jQuery('#play-button').hasClass('fa-play')){
	    		var fn = function(){ player.playVideo(); }
	        	setTimeout(fn, 200);
	    	}
	    	else if(jQuery('#play-button').hasClass('fa-pause')){
	    		
	    		player.pauseVideo();
	    	}
	        
	    }
	});
</script>