<?php
/* add new tab called "mytab" */

add_filter('um_account_page_default_tabs_hook', 'qr_tab_in_um', 100 );
function qr_tab_in_um( $tabs ) {
	$tabs[800]['qr']['icon'] = 'um-faicon-qrcode';//fa-qrcode
	$tabs[800]['qr']['title'] = 'QR Code';
	$tabs[800]['qr']['custom'] = true;
	return $tabs;
}
	
/* make new tab hookable */

add_action('um_account_tab__qr', 'um_account_tab__qr');
function um_account_tab__qr( $info ) {
	global $ultimatemember;
	extract( $info );

	$output = $ultimatemember->account->get_tab_output('qr');
	if ( $output ) { echo $output; }
}

/* Add content in the tab */

add_filter('um_account_content_hook_qr', 'um_account_content_hook_qr');
function um_account_content_hook_qr( $output ){
	ob_start();
	
	if ( is_ultimatemember() ) {
		if(um_user('role') == 'pending-member'){
			$shop_url = get_site_url() . '/shop';
			echo ('<h5>Please complete your membership registration to access this area.</h5>');
			echo ('<p>You can purchase our membership <a href="' . $shop_url . '"> here </a></p>');
			return;
		}
	}
	require_once('qr-code-processor.php');
		
	$output .= ob_get_contents();
	ob_end_clean();
	return $output;
}
?>