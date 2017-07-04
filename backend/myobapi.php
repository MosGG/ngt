<?php 
// include_once('class.myob_oauth.php');

$api_key = "c2satxwfwh5qm843dtyr5bwx";
$api_secret = "amAM4fyAP7u7NJPFUkh7RdZT";
$$redirect_url = "http://www.newglobalmel.com.au/myobapi";
$username = "cheee";
$password = "saycheee";




function getToken(){
	// $oauth = new myob_api_oauth();
	// $oauth_tokens = $oauth->getAccessToken($api_key, $api_secret, $redirect_url, $api_access_code, $api_scope);
}

function myobPost($username, $password, $dev_key, $access_token, $jsonString, $url){
	$headers = array(
		"Authorization"=>"Basic " . base64_encode($username . ":" . $password),
		"x-myobapi-key"=>$dev_key,
		"x-myobapi-exotoken"=>$access_token,
		);

	// Start curl
	$ch = curl_init();
	$putString = $jsonString;
	// Put string into a temporary file
	$putData = tmpfile();
	fwrite($putData, $putString);
	fseek($putData, 0);

	// Headers
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	curl_setopt($ch, CURLOPT_HEADER, true); 
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_PUT, true);
	// setup the authentication
	curl_setopt($ch, CURLOPT_USERPWD,  $username":"$password );
	curl_setopt($ch, CURLOPT_INFILE, $putData);
	curl_setopt($ch, CURLOPT_INFILESIZE, strlen($putString));

	$output = curl_exec($ch);
	echo $output;

	// Close the file
	fclose($putData);
	curl_close($ch);
}

?>
