<?php
	// include '/home/hosting/template-v0d/log.inc.php';

	########################################
	#
	# 25/03/2013 - David	- Add invalid page detection and write to error_file.txt
	# 05/04/2013 - William	- Cleaning and formatting
	#						- Additional decoding of Robots including human readable text version of user agent string as used by template_SPS
	# 08/04/2013 - William	- Decoding of bots function moved to global as being in here cause error "Function already defined"
	# 23/04/2013 - David	- Hashed out Add invalid page detection and write to error_file.txt - stop writing log
	# 24/06/2013 - David	- Changed it to log everything
	# 12/08/2013 - David	- if exist $site['database']['logFile'] log file used
	# 28/01/2014 - William	- Removed code which prevented stat logging while a site if on pluto (required for testing recently viewed products list)
	# 28/02/2014 - William	- Increased limitation of data length insertion for Browser/User Agent and Referrer Fields
	# 22/05/2014 - William	- Updated all mysql functions to mysqli
	# 07/10/2014 - William	- Removed commented code for error_file.txt
	#						- Removed commented code for logFile database table (table will be deleted in site upgrades)
	#						- Increased allowable characters to be logged for the IP address to 45, not 20
	#						- Referrer is truncated completely IF the URL is less than 5 characters long xx.yy (am.us) is the minimum
	# 18/02/2015 - David	- Fixed issue with not loging due to $_SESSION['log'] = "on" when not off instead of being empty
	# 14/05/2015 - William	- Added code to GOTO the end of the file (skip logging) to prevent it from being called accidentally twice on any given page. This would be better replaced with a include_once on our sites but that is not possible at this time.
	# 02/06/2016 - Ashley	- Fixed up some undefined indexes
	# 20/12/2016 - Ashley	- Fixed up an integer/char mismatch issue prevalent in newer versions of database management systems
	#
	########################################

	// Patch to reduce doubling of the logs where the log script is excessively included
	if (isset($double_logging)) {
		goto skipLogging;
	}

	// The below lines turn off site stat logging whilst on pluto
#	if ($static['server']['name'] == "pluto.ubcserver.com") {
#		$_SESSION['log'] = "off";
#	}

	if (isset($_GET['log']) && $_GET['log'] == "off") {
		$_SESSION['log'] = "off";
	}

	$_SESSION['log'] = isset($_SESSION['log']) ? $_SESSION['log'] : "on";
	$_SESSION['log'] = (isset($_SESSION['member']['memberAccess']) && $_SESSION['member']['memberAccess'] > 55) ? "off" : $_SESSION['log'];

	if ($_SESSION['log'] != "off") {
		# Record Session Details
		if ($_SESSION['log'] == "on") {
			$match = find_ua_match($bot, $_SERVER["HTTP_USER_AGENT"]);

			$log['logIp']		= $_SERVER["REMOTE_ADDR"];
			$log['logReferer']	= !isset($_SERVER["HTTP_REFERER"]) || strlen($_SERVER["HTTP_REFERER"]) < 5 ? "" : $_SERVER["HTTP_REFERER"];
			$log['logBrowser']	= $match['decoded'];
			$log['logMember']	= isset($_SESSION['member']['memberId']) ? $_SESSION['member']['memberId'] : 0;
			$log['logStatus']	= "B";

			if ($match['status'] === true) {
				$log['logStatus']  = "R";
			}

			$log['logUrl']		= $_SERVER["HTTP_HOST"];
			$log['logFile']		= $_SERVER["REQUEST_URI"];

			$log['logPage']		= $page['pageId'];

			$write['logBrowser']	= log_data($site['database']['logBrowser'], 'logBrowserId', 'logBrowserName', $log['logBrowser'], 900);
			$write['logIp']		= log_data($site['database']['logIp'], 'logIpId', 'logIpName', $log['logIp'], 45);
			$write['logReferer']	= log_data($site['database']['logReferer'], 'logRefererId', 'logRefererName', $log['logReferer'], 900);
			$write['logUrl']	= log_data($site['database']['logUrl'], 'logUrlId', 'logUrlName', $log['logUrl'], 60);

			$sql  = "SELECT * FROM ".$site['database']['logSession']." WHERE `logSDate` = '".date("Y-m-d",time())."' AND `logSIp` = '".$write['logIp']."' AND `logSBrowser` = '".$write['logBrowser']."'";
			$result = sql_exec($sql);
			$line = $result->fetch_assoc();

			if ($line['logSId']) {
				$_SESSION['log'] = $line['logSId'];
			} else {
				$sql  = "INSERT INTO ".$site['database']['logSession']." (`logSSiteId`, `logSDate`, `logSIp`, `logSReferer`, `logSBrowser`, `logSMember`, `logSUrl`, `logSStatus`) ";
				$sql .= "VALUES (";
				$sql .= "'".(isset($site['id']) ? $site['id'] : 0)."', ";
				$sql .= "now() , ";
				$sql .= "'".$write['logIp']."', ";
				$sql .= "'".$write['logReferer']."', ";
				$sql .= "'".$write['logBrowser']."', ";
				$sql .= "'".$log['logMember']."', ";
				$sql .= "'".$write['logUrl']."', ";
				$sql .= "'".$log['logStatus']."'";
				$sql .= ");";
				$result = sql_exec($sql);
				$_SESSION['log'] = $db->insert_id;
			}
		}

		$log['logPage']	= $page['pageId'];

		if (isset($_SESSION['logSummary']['logPage'][$log['logPage']])) {
			$log['logSummaryPage'] = "";
		} else {
			$_SESSION['logSummary']['logPage'][$log['logPage']] = $log['logPage'];
			$log['logSummaryPage'] = $log['logPage'];
		}

		$log['logItem']	= (isset($page['productId'])) ? $page['productId'] : "";

		if (isset($_SESSION['logSummary']['logItem'][$log['logItem']])) {
			$log['logSummaryItem'] = "";
		} else {
			$_SESSION['logSummary']['logItem'][$log['logItem']] = $log['logItem'];
			$log['logSummaryItem'] = $log['logItem'];
		}
		$log["logPage"]			= (int)$log["logPage"];
		$log["logItem"]			= (int)$log["logItem"];
		$log["logSummaryPage"]	= (int)$log["logSummaryPage"];
		$log["logSummaryItem"]	= (int)$log["logSummaryItem"];
		$sql  = "INSERT INTO ".$site['database']['log']." (`logSession`, `logDateTime`, `logPage`, `logItem`, `logSummaryPage`, `logSummaryItem`) ";
		$sql .= "VALUES (";
		$sql .= "".$_SESSION["log"].", ";
		$sql .= "now(), ";
		$sql .= "".$log["logPage"].", ";
		$sql .= "".$log["logItem"].", ";
		$sql .= "".$log["logSummaryPage"].", ";
		$sql .= "".$log["logSummaryItem"]."";
		$sql .= ");";
		$result = sql_exec($sql);

		if (isset($_SESSION['debug']['log']) && $_SESSION['debug']['log'] == "log") {
			$_SESSION['logDebug'][][$log['logPage']] = $_SERVER['REQUEST_URI'];
		}
	}

	unset($static['admin']['p']);

	// GOTO here if logging should not occur
	skipLogging:

	// Set this variable to prevent all future executions of this script... making it effectively only EVER run once for a page.
	$double_logging = true;
?>