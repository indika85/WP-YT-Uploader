<div class="wrap">
		
		<h2 style="color:red">DO NOT CHANGE THESE SETTINGS</h2>
		
		<form method="post" action="options.php">
			<?php settings_fields( 'wp-yt-plugin-settings-group' ); ?>
    			<?php do_settings_sections( 'wp-yt-plugin-settings-group' ); ?>
		    <table class="form-table">
		    	
		    	<tr valign="top">
		    	<td><h3 style="color:red">YouTube API Settings</h3></td>
		    	</tr>
		    	
		        <tr valign="top">
		        <th scope="row">Client ID</th>
		        <td><input style="width:50%" type="text" name="client_ID" value="<?php echo esc_attr( get_option('client_ID') ); ?>" /></td>
		        </tr>
		         
		        <tr valign="top">
		        <th scope="row">Client secret</th>
		        <td><input style="width:50%" type="text" name="client_secret" value="<?php echo esc_attr( get_option('client_secret') ); ?>" /></td>
		        </tr>
		        
		        <tr valign="top">
		        <th scope="row">App Name</th>
		        <td><input style="width:50%" type="text" name="app_name" value="<?php echo esc_attr( get_option('app_name') ); ?>" /></td>
		        </tr>
		        
		        <tr valign="top">
		        <th scope="row">Authorized redirect URI</th>
		        <td><input style="width:50%" type="text" name="redirect_URI" value="<?php echo esc_attr( get_option('redirect_URI') ); ?>" /></td>
		        </tr>
		        
		        <tr valign="top">
		        <th scope="row">API Key</th>
		        <td><input style="width:50%" type="text" name="API_key" value="<?php echo esc_attr( get_option('API_key') ); ?>" /></td>
		        </tr>
		        
		        <tr valign="top">
		        <th scope="row">Refresh Token</th>
		        <td><input style="width:50%" type="text" readonly name="refresh_token" value="<?php echo esc_attr( get_option('refresh_token') ); ?>" /></td>
		        </tr>
		        
		        <tr valign="top">
		        <td><h3>Image Gallery Settings</h3></td>
		        </tr>
		        <tr valign="top">
		        <th scope="row">Maximum number of images allowed: (leave blank for no limit) </th>
		        <td><input style="width:50%" type="text" name="max_number_of_images" value="<?php echo esc_attr( get_option('max_number_of_images') ); ?>" /></td>
		        </tr>
		        
		        <tr valign="top">
		        <td><h3 style="color:red">QR Code Settings</h3></td>
		        </tr>
		        <tr valign="top">
		        <th scope="row">Member URL base name. (i.e. In rememberme.memorial/xmemberx/indika, the base name is xmemberx). Make sure you change the page permalink as well</th>
		        <td><input style="width:50%" type="text" name="user_url_base" value="<?php echo esc_attr( get_option('user_url_base') ); ?>" /></td>
		        </tr>
		        
		        <tr valign="top">
		        <th scope="row">Video tab name </th>
		        <td><input style="width:50%" type="text" name="video_tab_name" value="<?php echo esc_attr( get_option('video_tab_name') ); ?>" /></td>
		        </tr>
		        
		        <tr valign="top">
		        <th scope="row">Random pass: </th>
		        <td><input style="width:50%" type="text" name="rand_pass" value="<?php echo esc_attr( get_option('rand_pass') ); ?>" /></td>
		        </tr>
		    </table>
		    
		    <?php submit_button(); ?>
		
		</form>
		
		<?php
			$refresh_token = get_option('refresh_token');
			
			if($refresh_token ==''){
				
				echo("No refrresh token received");
				include_once 'get-token.php';
			}
			else{
				//echo ("Refresh Token:" . $refresh_token);
			}
		?>
	</div>