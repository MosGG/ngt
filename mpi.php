<?php
	$db_user     = 'root';//'newg_ubc';	//database user
	$db_password = '';//'eureka2009';		//database password
	$db_database = 'newg_hosting';	//database definition file
	$db_server   = 'localhost';	//database server -- usually localhost, but one never knows


	// MySQLi Connection
	include "template-v0d/global-sqli.php";

	function sql_exec($sql) {
		$result = "";
		$result = mysql_query($sql) or die("Database Error:".mysql_errno()." in <B>".$_SERVER['PHP_SELF']."</B>:<BR>".$sql.":<BR><I>".mysql_error());
	return($result);
	}


	$dir = "content/product/thumb/";
	if ($handle = opendir($config['image']['path'].$dir)) {
		while (false != ($filestore = readdir($handle))) {
			if (preg_match("/.jpg/", $filestore, $resultmatch)) {
				$directory[] = $filestore;
			}
		}
		sort($directory);
	}
	closedir($handle);

	foreach ($directory as $file) {
		$expanded = explode("_",$file);
		$product = $expanded['0'];
#		echo $file.":".$product."<br>";
		$sql = "SELECT * FROM `newg_product` WHERE `productId` = '".$product."'";
		$result = sql_exec($sql);
		$record = $result->fetch_assoc();
		$description = str_replace("'", "", $record['productTitle']);
		$title = str_replace("'", "", $record['productTitle']);

		$sql = "INSERT INTO `newg_product_image` (
			`productImageId` ,
			`productImageProduct` ,
			`productImageOrder` ,
			`productImageTitle` ,
			`productImageDescription` ,
			`productImageFile` 
			)
			VALUES (
			NULL , '".$product."', '', '".$title."', '".$description."', '".$file."'
			);";
		$result = sql_exec($sql);
		echo "<font size=-2>".$sql."</font><br><br>";
	}
?>