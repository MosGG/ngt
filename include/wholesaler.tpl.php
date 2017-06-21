<?php
	if ($_SESSION['membership']) {
		echo content_converter($page['pageText']);
	}

	$admin['user']     = strtolower($_POST['login']);
	$admin['password'] = strtolower($_POST['password']);

	if ($_POST['logout'] == "Logout") {
		$_SESSION['cartMode'] = "";
		$_SESSION['cart'] = "";
		$_SESSION['access'] = 20;
		unset($_SESSION['member']);
		unset($_SESSION['membership']);
		unset($_SESSION['cache']);
		echo "<meta http-equiv='refresh' content='0;url=".$_SERVER['REQUEST_URI']."'>";
	}

	if ($static['admin']['p']) {
		foreach ($static['admin']['p'] as $adminKey => $adminPassword) {
			if ($admin['user'] == 'ubc' && $adminPassword == $admin['password'] && $adminKey > '32500') {
				$_SESSION['access'] = '99';
				$_SESSION['membership']['mmemberId']       = $adminKey;
				$_SESSION['membership']['mmemberSiteId']   = $site['id'];
				$_SESSION['membership']['mmemberUser']     = $admin['user'];
				$_SESSION['membership']['mmemberPassword'] = '(secret)';
				$_SESSION['membership']['mmemberName']     = $static['admin']['n'][$adminKey];
				$_SESSION['membership']['mmemberAccess']   = '99';
				$_SESSION['member']['memberId'] = $_SESSION['membership']['mmemberId'];
				echo "<meta http-equiv='refresh' content='1;url=".$_SERVER['REQUEST_URI']."'>";
			}
			if ($admin['user'] == 'admin' && $adminPassword == $admin['password'] && $adminKey >= '32450' && $adminKey < '32500' && $site['admin']['user'][$adminKey]) {
				$_SESSION['access'] = $site['admin']['user'][$adminKey];
				$_SESSION['membership']['mmemberId']       = $adminKey;
				$_SESSION['membership']['mmemberSiteId']   = $site['id'];
				$_SESSION['membership']['mmemberUser']     = $admin['user'];
				$_SESSION['membership']['mmemberPassword'] = '(secret)';
				$_SESSION['membership']['mmemberName']     = $static['admin']['n'][$adminKey];
				$_SESSION['membership']['mmemberAccess']   = $site['admin']['user'][$adminKey];
				$_SESSION['member']['memberId'] = $_SESSION['membership']['mmemberId'];
				echo "<meta http-equiv='refresh' content='1;url=".$_SERVER['REQUEST_URI']."'>";
			}
		}
	}

	if ($_POST['login']) {
		$sql  = "SELECT * FROM ".$site['database']['membership']." WHERE `mmemberEmail` = '".$admin['user']."' AND `mmemberPassword` = '".$admin['password']."' AND `mmemberType` <> '%' AND `mmemberStatus` = 'A' ";
		$result = sql_exec($sql);
		if ($row = $result->fetch_assoc()) {
			$_SESSION['membership'] = $row;

			if ($_SESSION['membership']['mmemberAccess'] >= '60') {
				$_SESSION['access'] = $_SESSION['membership']['mmemberAccess'];
			} else {
				$_SESSION['access'] = '30';
			}
			$sql  = "SELECT id FROM ".$site['database']['shopping_cart']."WHERE `mmemberEmail` = '".$admin['user']."'";
			$resultid = sql_exec($sql);
			$rid = $resultid->fetch_assoc();
			if($rid != NULL){
				$sql  = "SELECT cart FROM ".$site['database']['shopping_cart']."WHERE `mmemberEmail` = '".$admin['user']."' ";
				$resultcart = sql_exec($sql);
				$rcart = $resultcart->fetch_assoc();
				$previoussession = json_decode($rcart['cart']);
				if($previoussession != NULL){
					foreach($previoussession as $id => $qty){
						$_SESSION['cart'][$id] = $qty;
					}
				}
			}
			echo "<meta http-equiv='refresh' content='1;url=".$_SERVER['REQUEST_URI']."'>";

		} else {
			$sql  = "SELECT * FROM ".$site['database']['member']." WHERE memberUser = '".$admin['user']."' AND memberPassword = '".$admin['password']."'";
			$sql .= " AND memberSiteId = '".$site['id']."';";
			$result = sql_exec($sql);
			if ($row = $result->fetch_assoc()) {
				foreach ($row as $key=>$data) {
					$_SESSION['membership']['m'.$key] = $data;
				}
				$_SESSION['access'] = $row['memberAccess'];
				$_SESSION['member']['memberId'] = $_SESSION['membership']['mmemberId'];

				if ($_SESSION['membership']['mmemberAccess'] >= '60') {
					$_SESSION['access'] = $_SESSION['membership']['mmemberAccess'];
				} else {
					$_SESSION['access'] = '30';
				}

				echo "<meta http-equiv='refresh' content='1;url=".$_SERVER['REQUEST_URI']."'>";
			}
		}
	}

	if ($_SESSION['membership']['mmemberId']) {
		$sql  = "UPDATE ".$site['database']['logSession'];
		$sql .= " SET";
		$sql .= " logSMember  = '".$_SESSION['membership']['mmemberId']."',";
		$sql .= " logSStatus  = 'L'";
		$sql .= " WHERE logSId = '".$_SESSION['log']."' AND logSSiteId = '".$site['id']."';";
		$result = sql_exec($sql);
	}

	if (!$_SESSION['membership']) {
		echo "<div id='wholesaler'>";
			echo "<form action='".$_SERVER['REQUEST_URI']."' method='post'>\n";
			echo "<table cellspacing='5' summary=''>\n";
			echo "\t<tr>\n";
			echo "\t\t<th>Email Address:</th>\n";
			echo "\t</tr>\n";
			echo "\t<tr>\n";
			echo "\t\t<td><input type='text' name='login' size='20' maxlength='50' /></td>\n";
			echo "\t</tr>\n";
			echo "\t<tr>\n";
			echo "\t\t<th>Password:</th>\n";
			echo "\t</tr>\n";
			echo "\t<tr>\n";
			echo "\t\t<td><input type='password' name='password' size='20' maxlength='50' /></td>\n";
			echo "\t</tr>\n";
			echo "\t<tr>\n";
			echo "\t\t<td colspan='2' align='center'><input type='submit' name='button-login' value='Login' /></td>\n";
			echo "\t</tr>\n";
			echo "</table>\n";
			echo "</form>\n";
		echo "</div>\n";
		echo "<div id='wholesalernote'>".
			"<p>Don't have a login? <a href='".$site['url']['full']."become-a-member'>Become a member</a>.</p>".
			"<p>Forget password? <a href='".$site['url']['full']."forget-password'>Click here</a>.</p>".
			"</div>";
	} else {
		echo "<p>Welcome ".$_SESSION['membership']['mmemberNameF'].$_SESSION['membership']['mmemberName'].",<br />Feel free to browse through our <a href='".$site['url']['full']."products'>product range</a>.</p>";
		echo "<form action='".$site['url']['actual']."' method='post'>";
		echo "<input class='logout' name='logout' type='submit' value='Logout' />\n";
		echo "</form>";
	}
?>
