<?php
/* add profile purchases tab */

add_filter('um_account_page_default_tabs_hook', 'profiles_tab_in_um', 50 );
function profiles_tab_in_um( $tabs ) {
	if(um_user('role') == 'member'){
		$tabs[800]['profiles']['icon'] = 'um-faicon-users';//
		$tabs[800]['profiles']['title'] = 'Memorial Profiles';
		$tabs[800]['profiles']['custom'] = true;
	}
	return $tabs;
}
	
/* make new tab hookable */

add_action('um_account_tab__profiles', 'um_account_tab__profiles');
function um_account_tab__profiles( $info ) {
	global $ultimatemember;
	extract( $info );

	$output = $ultimatemember->account->get_tab_output('profiles');
	if ( $output ) { echo $output; }
}

/* Add content in the tab */

add_filter('um_account_content_hook_profiles', 'um_account_content_hook_profiles');
function um_account_content_hook_profiles( $output ){
	ob_start();
	
	if ( is_ultimatemember() ) {
		if(um_user('role') == 'child'){
			$shop_url = get_site_url() . '/add-profiles';
			echo ('<h5>You do not have access to this area.</h5>');
			echo ('<p>Please login to your main account to access this section.</p>');
			return;
		}
		else if(um_user('role') == 'pending-member'){
			$shop_url = get_site_url() . '/add-profiles';
			echo ('<h5>Please complete your membership registration to access this area.</h5>');
			echo ('<p>You can purchase memorial profiles <a href="' . $shop_url . '"> here </a></p>');
			return;
		}
		else if(um_user('role') == 'member'){
			require_once('purchased-profiles.php');
		}
	}
	//require_once('qr-code-processor.php');
		
	$output .= ob_get_contents();
	ob_end_clean();
	return $output;
}
?>