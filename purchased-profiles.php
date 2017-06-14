<?php

$member_id = um_user('ID');
//$user_name = wp_get_current_user()->user_login;

//echo('User ID: ' . $member_id);


$WP_user_id = get_current_user_id();

if($member_id == $WP_user_id){
	//echo('User Logged in.');
	//$paged = get_query_var( 'status', 1 );
	//echo('Hello: '.$paged);
	global $wpdb;
        $table_name= $wpdb->prefix . "profile_purchases";
        
        $profiles = $wpdb->get_results( "SELECT child_id FROM ". $table_name . " WHERE member_id= " . $member_id);
        //echo('QR: ' . $qr_code);
        
        $number_of_profiles_created = count($profiles);

	//gets the number of orders from WooCommerce
	$args = array(
		'customer' => $member_id,
		'status' => 'wc-completed'
	);
	
	$orders = wc_get_orders($args);
	
	global $woocommerce;
	$number_of_profiles_purchased = 0;
	
	//Get the number of items in each order
	foreach($orders as $tmp){
		$order = new WC_Order($tmp->id);
	
		$order_item = $order->get_items();
	
	        foreach( $order_item as $product ) {
	            	//$prodct_name[] = $product['name']; 
			$number_of_profiles_purchased += $product['qty'];
	        }
	}
	echo('<h4>Memorial Profiles</h4>');
	echo('<div class="ps-row">');
	echo('<table class="info-table"><tr>');
	echo('<td tooltip="Shows the total number of profiles purchased. You can add more profiles by clicking the Add Profiles button below."> Profiles purchased: ' . $number_of_profiles_purchased .'</td>');
	
	echo('<td tooltip="Shows the number of profiles created. If you have created any profiles they should appear in the below table."> Profiles created: ' . $number_of_profiles_created . '</dtd>');
	echo('</tr></table>');
	echo('</div>');
	//echo('COUNT: ' . count($orders->orders));
	
	if($number_of_profiles_created > 0 ){
		//Shsow list of profiles
		
		$site_url= get_site_url();
		//$site_url = "http://www.rememberme.memorial";
		
		$url_base = get_option('user_url_base');
		
		echo('<div class="ps-row">');
		echo('<table class="list-table">');
		echo('<tr>');
			echo('<th>Profile Name</th>');
			echo('<th>Action</th>');
			echo('<th>Profile Status</th>');
		echo('</tr>');
		foreach($profiles as $profile){
			$user_data = get_userdata($profile->child_id);
			$member_username = $user_data->user_login;
			$member_fname = $user_data->first_name;
			$member_lname = $user_data->last_name;
			$profile_url = $site_url . '/' . $url_base. '/' . $member_username . '/?profiletab=main&um_action=edit';
			
			//echo('<div class="ps-col1 profile-item"><a href="'. $profile_url .'" target="_blank" >'. $member_fname .' ' . $member_lname . '</a></div>');

			echo('<tr>');
				echo('<td>');
					echo($member_fname .' ' . $member_lname);
						
				echo('</td>');
				echo('<td>');
					echo('<a href="'. $profile_url .'" tooltip="You can add/ edit content on this memorial profile.">Edit/ Add content</a>');
				echo('</td>');
				echo('<td>');
				
					global $ultimatemember;
					um_fetch_user($profile->child_id);
					$profileStatus=$ultimatemember->user->get_role();
					um_reset_user();
					if($profileStatus == 'child'){
						echo('<span class="profile-status status-published" data-childid="'.$profile->child_id.'" data-status="'.$profileStatus.'" tooltip="You can make this profile private by Unpublishing it. Click on Published to toggle status.">Published</span>');
					}
					else if($profileStatus == 'private-child'){
						echo('<span class="profile-status status-unpublished" data-childid="'.$profile->child_id.'" data-status="'.$profileStatus.'" tooltip="You can make this profile public by Publishing it. Click on Unpublished to toggle status"> Unpublished </span>');
					}
				echo('</td>');
			echo('</tr>');
		}
		echo('</table>');
		echo('</div>');
	}
	if($number_of_profiles_purchased <=0 ){
		echo('<span class="get-started-no-profiles">Get Started by Adding a Profile </span>');
	}
	$status = get_query_var( 'status', 1 );  
	if($status=='paid'){
		echo('<div class="profile-button-section" style="display:none">');
	}
	else{
		echo('<div class="profile-button-section">');
	}
?>

		<div class="profile-button" onclick="window.location='/add-profiles';"> Add Profiles </div>
<?php
	if($number_of_profiles_purchased > $number_of_profiles_created){
		//You can create profile
		echo('<div id="show-create-profile" class="profile-button"> Create Profile </div>');
	}
?>
	</div>
<?php
	//if($number_of_profiles_purchased <=0 ){
		echo('<span class="take-a-tour">Take a Tour of Your Account </span>');
	//}
	if($number_of_profiles_purchased > $number_of_profiles_created){
		//You can create profile
		require_once('create-profile-form.php');
	}
}
?>

<div id="getting-started-section" class="getting-started-overlay" style="display:none">
	<div class="getting-started-content">
		<div id="geting-started-close">X</div>
		<div class="getting-started-header">
			<h2>Hello <?php echo(get_currentuserinfo()->user_firstname) ?> </h2>
			<p>Thank you for succusessfully activating your account.</p>
			<p>You have made the right decision to remember your loved ones forever.</p>
		</div>
		<div class="how-to-get-started">
			How to get started
		</div>
		<div class="getting-started-row">
			1. <a href="/add-profiles">Add</a> a memorial profile to your account.	
		</div>
		<div class="getting-started-row">
			2. Create the memorial profile.
		</div>
		<div class="getting-started-row">
			3. Add your loved one's details to the profile.
		</div>
		<div class="getting-started-row">
			4. Add images and link/upload a video.
		</div>
		<div class="getting-started-row">
			5. Publish your loved one's profile.
		</div>
		<div class="getting-started-row-1">
			<a href="/add-profiles"><span class="how-to-add-button">Get Started by Adding a Profile ></span></a>
		</div>
	</div>
</div>
<script>!function(){var e=document.createElement("script"),t=document.getElementsByTagName("script")[0];e.async=1,e.src="https://inlinemanual.com/embed/player.05e0ec2eb052be37092bdb1c86438753.js",e.charset="UTF-8",t.parentNode.insertBefore(e,t)}();</script>