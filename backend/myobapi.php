<?php 
include 'database_config.php';
// getStockImage("IP9685");


// getstockItem();
test();

function test(){
	// global $conn;
	$path = '/stocksecondarygroup';
	$res = myobPost($path);
	$array = json_decode($res, true);
	var_dump($array);
	exit();
}

function addSecondaryGroup(){
	global $conn;
	$path = '/stocksecondarygroup?pagesize=10&page=1';
	$res = myobPost($path);
	$array = json_decode($res, true);
	var_dump($array);
	exit();
	// foreach($array as $category){
	// 	$sql = "INSERT INTO `myob_category_list` (`id`, `grouplevel`, `groupid`, `grouptitle`, `pageid`, `pagetitle`) VALUES (NULL, '2', '".$category['id']."', '".$category['name']."', '', '')";
	// 	$result = $conn->query($sql);
	// 	if ($result) {
	// 		echo "Success add primary group id:".$category['id']." name:".$category['name'];
	// 	} else {
	// 		echo "Error when adding id:".$category['id']." name:".$category['name'];
	// 	}
	
	// }
}

function addPrimaryGroup(){
	global $conn;
	$path = '/stockprimarygroup?pagesize=100';
	$res = myobPost($path);
	$array = json_decode($res, true);
	foreach($array as $category){
		if ($category['active']){
			$sql = "INSERT INTO `myob_category_list` (`id`, `grouplevel`, `groupid`, `grouptitle`, `pageid`, `pagetitle`) VALUES (NULL, '1', '".$category['id']."', '".$category['name']."', '', '')";
			$result = $conn->query($sql);
			if ($result) {
				echo "Success add primary group id:".$category['id']." name:".$category['name'];
			} else {
				echo "Error when adding id:".$category['id']." name:".$category['name'];
			}
		}
	}
}

function getstockItem(){
	$path = '/stockitem?$filter=Active+eq+true&$orderby=Stockcode+asc&pagesize=100';
	$res = myobPost($path);
	echo $res;
}

function getStockImage($stockId){
	$path = "/stockitem/".$stockId."/image?width=400";
	$res = myobPost($path);
	saveImg($stockId.".jpg", $res);
}

function saveImg($filename, $sourcecode){
	$savefile = fopen("../content/product/full/".$filename, 'w');
    fwrite($savefile, $sourcecode);
    fclose($savefile);
    return true;
}

function myobPost($path, $postArray=NULL, $method="GET"){
	$dev_key = "c2satxwfwh5qm843dtyr5bwx";
	$api_secret = "amAM4fyAP7u7NJPFUkh7RdZT";
	$redirect_url = "http://www.newglobalmel.com.au/myobapi";
	$uri = "https://exo.api.myob.com";
	$url = $uri . $path;
	$username = "newglobaltrading";
	$password = "NewGlobal5555";
	$exotoken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE1MjUwMTA0MDAsImlzcyI6IlMtMS01LTIxLTI1MjMyNDA5MjUtMzk1NTc3NjkzMy0xOTUyOTY0Njk1OmVJUElaSklBcFRZdUFOaUZYa3dva3c9PSIsImF1ZCI6Imh0dHBzOi8vZXhvLmFwaS5teW9iLmNvbS8iLCJuYW1lIjoiTkVXR0xPQkFMVFJBRElORyIsInN0YWZmbm8iOiI0ODMwIiwiYXBwaWQiOiI0NDAwIn0.oeHq2BgoFeEbgWEHVyWX4CSdk14xnKICvTnkWPvv3j0";
	$headers = array(
		"Authorization: Basic " . base64_encode($username . ":" . $password),
		"x-myobapi-key: " . $dev_key,
		"x-myobapi-exotoken: " . $exotoken,
		"accept: application/json",
	);

	try {
		// Start curl
		$ch = curl_init();
		if (FALSE === $ch) throw new Exception('failed to initialize');
		
		// Headers
		// curl_setopt($ch, CURLOPT_HEADER, true); 
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		if ($method == "POST") {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postArray));
		}

		// curl_setopt($ch, CURLOPT_PUT, true);
		// setup the authentication
		curl_setopt($ch, CURLOPT_USERPWD,  $username .":". $password );
		// curl_setopt($ch, CURLOPT_INFILE, $putData);
		// curl_setopt($ch, CURLOPT_INFILESIZE, strlen($putString));

		$output = curl_exec($ch);
		if (FALSE === $output) throw new Exception(curl_error($ch), curl_errno($ch));
		// Close the file
		// fclose($putData);
		} catch(Exception $e) {

	    trigger_error(sprintf(
	        'Curl failed with error #%d: %s',
	        $e->getCode(), $e->getMessage()),
	        E_USER_ERROR);
	}
	curl_close($ch);
	return $output;
}