<?php  $status = get_query_var( 'status', 1 );  
	if($status=='paid'){
		echo('<div id="create-profile-section">');
	}
	else{
		echo('<div id="create-profile-section" style="display:none">');
	}
?>

	<div class="ps-row">
		<h4>Create a profile for your loved one</h4>
	</div>
	<div class="ps-row">
		<h5>Account information of the new profile</h5>
	</div>
	<div class="ps-row">
		<div class="ps-col1">
			<p>Username for the new profile:<span class="small-text">(Should be only characters. No spaces)</span></p>
			<span id="username-message" style="color:red; font-size:13px; display:none"></span>
		</div>
		<div class="ps-col2">
			<input type="text" required name="username" id="username" />
		</div>
	</div>
	<div class="ps-row">
		<input type="checkbox" name="create_password" id="create_password" value="createPass" tooltip="If you create a password for this profile you can login to this particular profile using the username of this profile and this password. Adding a separate password will be useful if you want someone else to add/edit content to this profile."> Create a password for this profile </input>
		</br><span class="small-text">By creating a password you can manage this profile without logging into your account.</span>
	</div>
	<div id="password-input-section" style="display:none">
		<div class="ps-row">
			<div class="ps-col1">
				<p>Password:<span class="small-text">(Should be at least 8 characters long)</span></p>
			</div>
			<div class="ps-col2">
				<input type="password" required name="password" id="password" />
			</div>
		</div>
		<div class="ps-row">
			<div class="ps-col1">
				<p>Retype Password:</p>
				<span id="password-message" style="color:red; font-size:13px; display:none"></span>
			</div>
			<div class="ps-col2">
				<input type="password" required name="passwordre" id="passwordre" />
			</div>
		</div>
	</div>
	
	<div class="ps-row">
		<h5>Profile information of your loved one</h5>
	</div>
	<div class="ps-row">
		<div class="ps-col1">
			<p>Loved one's first name:</p>
			<span id="fname-message" style="color:red; font-size:13px; display:none"></span>
		</div>
		<div class="ps-col2">
			<input type="text" required name="fname" id="fname" />
		</div>
	</div>
	<div class="ps-row">
		<div class="ps-col1">
			<p>Loved one's last name:</p>
			<span id="lname-message" style="color:red; font-size:13px; display:none"></span>
		</div>
		<div class="ps-col2">
			<input type="text" required name="lname" id="lname" />
		</div>
	</div>
	<div class="ps-row">
		<div class="ps-col1">
			<div style="display:none" id="loading-spinner" class="spinner spinner-4"></div>
			<span id="create">Create</span> 
			<span id="cancel">Cancel</span>
			
		</div>
		<span id="general-message" style="color:red; font-size:13px; display:none"></span>
	</div>
</div>