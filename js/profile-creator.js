jQuery(document).ready( function($) {
	console.log('Profile Creator Loaded!');
	//--------- Getting Started Section--------
	//alert(profile_creator.user_status);
	
	if(profile_creator.user_status=='new'){
		$('#getting-started-section').fadeIn('slow');
	}
	$('#geting-started-close').on('click', function(){
		$('#getting-started-section').fadeOut('slow');
	});
	//-----------------
	var create_pass = 0;
	
	$('#create_password').change(function() {
	   if($(this).is(":checked")) {
	      $('#password-input-section').show('slow');
	      create_pass = 1;
	   }
	   else{
	   	$('#password-input-section').hide('slow');
	   	create_pass = 0;
	   }
	});

	$('#show-create-profile').on('click', function(){
		$('.profile-button-section').hide('fast');
		$('#create-profile-section').show('slow');
	});
	
	$('#create').on('click', function(){
		
		//alert('Clicked');
		//Check username
		if(checkUsername() && checkPassword()){
			createUser();
		}
		else{
			$('#general-message').show();
			$('#general-message').text('Please fix the above errors before continuing');
		}
		
	});
	$('.take-a-tour').on('click', function(){
		inline_manual_player.activateTopic(20957);
	});
	$('#cancel').on('click', function(){
		$('#create-profile-section').hide('slow');
		$('.profile-button-section').show('fast');
	});
	$('#username').on('blur', function(){
		$('#username-message').hide();
		checkUsername();
	});
	$('#passwordre').on('blur', function(){
		$('#password-message').hide();
		checkPassword();
	});
	$('#banner-publish').on('click', function(){
		var role= 'private-child';
		var child_id=$(this).data('childid');
		
		$(this).css("pointer-events", "none");
		$(this).text('Please wait...');
		
		changeStatus(role, child_id);
		
	});
	$('.profile-status').on('click', function(){
		var role= $(this).data('status');
		var child_id=$(this).data('childid');
		$(this).css("pointer-events", "none");
		$(this).text('Please wait...');
		
		changeStatus(role, child_id);
		
	});
	function changeStatus(role, child_id){
		$.ajax({
			url: profile_creator.ajaxurl,
			type:'POST',
			data:{
				action:'change_profile_status',
				pass_key:profile_creator.pass_key,
				role:role,
				childid:child_id
			},
			success: function( data ) {
				console.log('Status changed: ' + data);
				location.reload();
			},
			error: function(errorThrown){
				alert(errorThrown);
			}
		});
	}
	
	function createUser(){
		//Create user
		var username = $.trim($('#username').val());
		var password = $.trim($('#password').val());
		var fname = $.trim($('#fname').val());
		var lname = $.trim($('#lname').val());
		
		if(fname == ""){
			$('#fname-message').text('First name cannot be blank.');
			$('#fname-message').show();
			$('#fname').focus();
			return;
		}
		if(lname == ""){
			$('#lname-message').text('Last name cannot be blank.');
			$('#lname-message').show();
			$('#lname').focus();
			return;
		}
		
		$("#create").css("pointer-events", "none");
		$('#loading-spinner').show('fast');
		$('#create').text('Please wait...');
		
		$.ajax({
			url: profile_creator.ajaxurl,
			type:'POST',
			data:{
				action:'create_profile',
				pass_key:profile_creator.pass_key,
				username:username,
				password:password,
				fname:fname,
				lname:lname,
				create_pass:create_pass
			},
			success: function( data ) {
				console.log('Create Profile: ' + data);
				window.location = window.location.href.split("?")[0];
			},
			error: function(errorThrown){
				alert(errorThrown);
				$('#loading-spinner').hide('fast');
				$('#create').text('Create');
				$("#create").css("pointer-events", "auto");
			}
		});
	}
	
	function checkUsername(){
		var username = $.trim($('#username').val());
		
		if( username == ""){
			$('#username-message').text('Username cannot be blank.');
			$('#username-message').show();
			return false;		
		}
		if(/^[a-zA-Z0-9_]*$/.test(username) == false) {
			$('#username-message').text('This username is invalid. Please use only lettern and numbers. No spaces');
			$('#username-message').show();
			$('#username').focus();
			return false;
		}
		$.ajax({
			url: profile_creator.ajaxurl,
			type:'POST',
			data:{
				action:'check_username',
				pass_key:profile_creator.pass_key,
				username:username
			},
			success: function( data ) {
				console.log('Checking username: ' + data);
				if(data == "1"){
					console.log('Username doesnt exsist');
					return true;
					
				}
				else if(data == "0"){
					console.log('Username exsist');
					$('#username').focus();
					$('#username-message').text('This username is already taken. Please select a different one and try again.');
					$('#username-message').show();
					return false;
					
				}
			},
			error: function(errorThrown){
				alert(errorThrown);
				return false;
			}
		});
		
		return true;
	}
	
	function checkPassword(){
		if(create_pass == 0) return true;
		
		var password = $.trim($('#password').val());
		var passwordre = $.trim($('#passwordre').val());
		if(password.length < 8){
			$('#password-message').text('Passwords should be at least 8 characters long');
			$('#password-message').show();
			return false;
		}
		if(password =="" || passwordre == ""){
			$('#password-message').text('Passwords cannot be blank');
			$('#password-message').show();
			return false;
		}
		if(password != passwordre){
			$('#password-message').text('The two passwords do not match');
			$('#password-message').show();
			return false;
		}
		return true;
	}
})
