<?php
 
	//require_once 'src/Google/Client.php';
	//require_once 'src/Google/Service/YouTube.php';

	//session_start();
	 
	$OAUTH2_CLIENT_ID = get_option('client_ID');
	$OAUTH2_CLIENT_SECRET = get_option('client_secret');
	$REDIRECT = get_option('redirect_URI');
	$APPNAME = get_option('app_name');
	 
	 
	$client = new Google_Client();
	$client->setClientId($OAUTH2_CLIENT_ID);
	$client->setClientSecret($OAUTH2_CLIENT_SECRET);
	$client->setScopes('https://www.googleapis.com/auth/youtube');
	$client->setRedirectUri($REDIRECT);
	$client->setApplicationName($APPNAME);
	$client->setAccessType('offline');
	 
	 
	// Define an object that will be used to make all API requests.
	$youtube = new Google_Service_YouTube($client);
	 
	if (isset($_GET['code'])) {
	    if (strval($_SESSION['state']) !== strval($_GET['state'])) {
	        die('The session state did not match.');
	    }
	 
	    $client->authenticate($_GET['code']);
	    $_SESSION['token'] = $client->getAccessToken();
	 
	}
	 
	if (isset($_SESSION['token'])) {
	    $client->setAccessToken($_SESSION['token']);
	    echo '<code>' . $_SESSION['token'] . '</code>';
	    update_option( 'refresh_token', $_SESSION['token']);
	}
	 
	// Check to ensure that the access token was successfully acquired.
	if ($client->getAccessToken()) {
	    //Do nothing
	} else {
	    $state = mt_rand();
	    $client->setState($state);
	    $_SESSION['state'] = $state;
	 
	    $authUrl = $client->createAuthUrl();
	    $htmlBody = "You need to <a href=" . $authUrl. ">authorise access</a> before proceeding.<p>";

	}
?>

<?php echo $htmlBody; ?>
