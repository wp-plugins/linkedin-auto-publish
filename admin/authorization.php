<?php
$app_id = get_option('xyz_lnap_application_id');
$app_secret = get_option('xyz_lnap_application_secret');
$lnredirecturl=admin_url('admin.php?page=linkedin-auto-publish-settings&auth=3');

session_start();
$code="";
if(isset($_REQUEST['code']))
$code = $_REQUEST["code"];

if(isset($_POST['lnauth']))
{
	
	$redirecturl=admin_url('admin.php?page=linkedin-auto-publish-settings&auth=3');
	
	$lnappikey=get_option('xyz_lnap_lnapikey');
	$lnapisecret=get_option('xyz_lnap_lnapisecret');
	
	# First step is to initialize with your consumer key and secret. We'll use an out-of-band oauth_callback
	$API_CONFIG = array(
	'appKey'       => $lnappikey,
	'appSecret'    => $lnapisecret,
	'callbackUrl'  => $redirecturl
	);

	$OBJ_linkedin = new LNAPLinkedIn($API_CONFIG);
	$response = $OBJ_linkedin->retrieveTokenRequest();
	
	if(isset($response['error']))
	{
			
		header("Location:".admin_url('admin.php?page=linkedin-auto-publish-settings&msg=1'));
		exit();
	}
	
	$lnoathtoken=$response['linkedin']['oauth_token'];
	$lnoathseret=$response['linkedin']['oauth_token_secret'];

	# Now we retrieve a request token. It will be set as $linkedin->request_token

	update_option('xyz_lnap_lnoauth_token', $lnoathtoken);
	update_option('xyz_lnap_lnoauth_secret',$lnoathseret);
	
	wp_redirect( LNAPLinkedIn::_URL_AUTH . $response['linkedin']['oauth_token']);
	echo "<script>document.location.href='".LNAPLinkedIn::_URL_AUTH . $response['linkedin']['oauth_token']."'</script>";
	die;
	
}

if(isset($_GET['auth']) && $_GET['auth']==3)
{
	if(isset($_GET['auth_problem']))
		break;
	$lnoathtoken=get_option('xyz_lnap_lnoauth_token');
	$lnoathseret=get_option('xyz_lnap_lnoauth_secret');

	 
	$lnappikey=get_option('xyz_lnap_lnapikey');
	$lnapisecret=get_option('xyz_lnap_lnapisecret');

	$lnoauth_verifier=$_GET['oauth_verifier'];


	update_option('xyz_lnap_lnoauth_verifier',$lnoauth_verifier);

	$API_CONFIG = array(
			'appKey'       => $lnappikey,
			'appSecret'    => $lnapisecret,
			'callbackUrl'  => $lnredirecturl
	);

	$OBJ_linkedin = new LNAPLinkedIn($API_CONFIG);
	$response = $OBJ_linkedin->retrieveTokenAccess($lnoathtoken, $lnoathseret, $lnoauth_verifier);

	# Now we retrieve a request token. It will be set as $linkedin->request_token
	update_option('xyz_lnap_application_lnarray', $response['linkedin']);	
	update_option('xyz_lnap_lnaf',0);

}
?>