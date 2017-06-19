<?php
	echo content_converter($page['pageText'], "converter_function.inc.php");
	if ($_SESSION['membership'] || $_SESSION['member']) {
		include 'product.php';
	} else {
		echo "<p>Please <a href='".$site['url']['full'].$_SESSION['cache']['layout']['287']['pageUrl']."'>Login</a> to view our product range.</p>";
		echo "<p>Don't have a login? <a href='".$site['url']['full']."become-a-member'>Become a member</a>.</p>";
	}
	$_SESSION['pageurl'] = $site['url']['actual'];

	if ($site['url']['decode']['edmid']) {
		$sql  = "SELECT * FROM ".$site['database']['edm-sent']." WHERE `mailSEmail` = '".$site['url']['decode']['email']."' ";
		$sql .= "AND `mailSId` = '".$site['url']['decode']['edmid']."'";
		$result = sql_exec($sql);
		$line = $result->fetch_assoc();
		if ($line) {
			$sql = "SELECT * FROM ".$site['database']['membership']." WHERE `mmemberId` = '".$line['mailSMember']."'";
			$result = sql_exec($sql);
			$member = $result->fetch_assoc();

			$count = $member['mmemberEdmVisitCount']+1;
			$sql = "UPDATE ".$site['database']['membership']." SET `mmemberEdmVisitDate` = now(), `mmemberEdmVisitCount` = '".$count."' WHERE `mmemberId` = '".$member['mmemberId']."'";
			$result = sql_exec($sql);
		}
	}
?>