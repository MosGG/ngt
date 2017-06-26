<?php
	echo content_converter($page['pageText']);

	unset($table['membership']['mmemberType']['item']['%']);

	$table['membership']['mmemberType']['display']       = 'N';
	$table['membership']['mmemberType']['insert']        = '';

	$table['membership']['mmemberBusiness']['display']   = 'Y';
	$table['membership']['mmemberBusiness']['required']  = 'Y';

	$table['membership']['mmemberNameF']['Title']        = 'Y';
	$table['membership']['mmemberNameF']['display']      = 'Y';
	$table['membership']['mmemberNameF']['required']     = 'Y';

	$table['membership']['mmemberNameS']['Title']        = 'Y';
	$table['membership']['mmemberNameS']['display']      = 'Y';
	$table['membership']['mmemberNameS']['required']     = 'Y';

	$table['membership']['mmemberABN']['display']      = 'Y';
	$table['membership']['mmemberABN']['required']     = 'Y';

	$table['membership']['mmemberEmail']['display']      = 'Y';
	$table['membership']['mmemberEmail']['required']     = 'Y';

	if ($_POST['submit']['mmemberEmail'] <> '') {
			
		$emailBits = explode("@",$_POST['submit']['mmemberEmail']);
		$password = $emailBits[0];
		$table['membership']['mmemberPassword']['insert']  = $password;
	}

	$table['membership']['mmemberAddress']['display']    = 'Y';
	$table['membership']['mmemberSuburb']['display']     = 'Y';
	$table['membership']['mmemberState']['display']      = 'Y';
	$table['membership']['mmemberPostcode']['display']   = 'Y';
	$table['membership']['mmemberPhone']['display']      = 'Y';
	$table['membership']['mmemberMobilePhone']['display']= 'Y';
	$table['membership']['mmemberSMSStatus']['display']  = 'N';
	$table['membership']['mmemberSMSStatus']['insert']   = 'N';

	$table['membership']['mmemberCaptcha']['display']    = 'Y';
	$table['membership']['mmemberCaptcha']['required']   = 'Y';
	$table['membership']['mmemberSource']['insert']      = 'W';
	$table['membership']['mmemberDateCreate']['display'] = 'N';
	$table['membership']['mmemberDateCreate']['insert']  = 'NOW';
	$table['membership']['mmemberDateUpdate']['display'] = 'N';
	$table['membership']['mmemberDateUpdate']['insert']  = 'NOW';
	$table['membership']['mmemberUserUpdate']['display'] = 'N';
	$table['membership']['mmemberUserUpdate']['insert']  = $_SESSION['member']['memberId'];
	$table['membership']['mmemberStatus']['display']     = 'N';
	$table['membership']['mmemberStatus']['insert']      = 'N';

	$table['membership']['buttonInsert']['heading']      = 'Submit';
	$table['membership']['buttonInsert']['display']      = 'Y';

	$layout  = $table['membership'];
	$tableId = $site['database']['membership'];

	$message['info']   = "To become a member and join our mailing list, please enter your details below.<br />You will receive future sales announcements and special event notices.";
	$message['insert'] = "Thank you for applying for membership and joining our mailing list.<br />You will be contacted with login details when your membership application has been processed.";

		echo "<div id='register'>";
			echo "<div class='login-tab login-tab-active'>Login</div>";
			echo "<a href='/become-a-member'><div class='login-tab login-tab-inactive'>Register</div></a>";
			echo "<div id='form'>";
			include '/template-v0d/admin_table.php';
			echo "</div>";
		echo "</div>\n";


	

	if ($_POST && !$error && $_SESSION['emailexists'] != 'y') {

		#####################
		# EMAIL TO BUSINESS #
		#####################

		if ($site['company']['email']['enabled'] == "y") {

			$mailSubject = "[".$_SESSION['log']."] ".$site['company']['name']." - Mailing List/Membership Application.";
			$mailBody    = "<!doctype html public '-//w3c//dtd html 4.0 transitional//en'>\n";
			$mailBody   .= "<html>\n";
			$mailBody   .= "<head>\n";
			$mailBody   .= "<meta http-equiv=content-type content='text/html; charset=windows-1252'>\n";
			$mailBody   .= "<link rel='stylesheet' href='".$site['url']['full']."include/recommend.css'>\n";
			$mailBody   .= "</head>\n";
			$mailBody   .= "<table width='650'>";
			$mailBody   .= "<tr>";
			$mailBody   .= "<td colspan='2' id='heading'><img src='".$site['url']['full']."images/".$site['template']['recommend']['header']."' alt='".$site['company']['name']."' /></td>";
			$mailBody   .= "</tr>";
			$mailBody   .= "<tr>";
			$mailBody   .= "<td colspan='2'><br />".$_POST['submit']['mmemberNameF']." ".$_POST['submit']['mmemberNameS']." has applied for membership and joined the mailing list.<br /><br /></td>";
			$mailBody   .= "</tr>";

			foreach ($layout as $fieldKey=>$fieldData) {
				if ($fieldData['type'] != "Index" && $fieldData['type'] != "B" && $fieldKey != "mmemberDateCreate" && $fieldKey != "mmemberDateUpdate" && $fieldKey != "mmemberUserUpdate" && $fieldKey != "mmemberStatus" && $fieldKey != "mmemberSource" && $fieldKey != "mmemberSiteId" && $fieldKey != "mmemberAccess" && $fieldKey != "mmemberType" && $fieldKey != "mmemberCaptcha"&& $fieldKey != "security") {
					if ($fieldData['type'] == "H") {
						$mailBody .= "<tr>";
						$mailBody .= "<th colspan='2' align='left'><br /><u>".$fieldData['heading']."</u></th>";
						$mailBody .= "</tr>";
					} else if ($fieldData['type'] == "D") {
						$mailBody .= "<tr>";
						$mailBody .= "<th align='left' valign='top'>".$fieldData['heading'].":</th>";
						$mailBody .= "<td>".$fieldData['item'][$_POST['submit'][$fieldKey]]."</td>";
						$mailBody .= "</tr>";
					} else if ($fieldData['type'] == "M") {
						$mailBody .= "<tr>";
						$mailBody .= "<th align='left' valign='top'>".$fieldData['heading'].":</th>";
						$mailBody .= "<td>$".number_format($_POST['submit'][$fieldKey], $site['template']['price']['decimal'])."</td>";
						$mailBody .= "</tr>";
					} else {
						$mailBody .= "<tr>";
						$mailBody .= "<th align='left' valign='top'>".$fieldData['heading'].":</th>";
						$mailBody .= "<td>".$_POST['submit'][$fieldKey]."</td>";
						$mailBody .= "</tr>";
					}
				}
			} # END foreach

			$mailBody   .= "</table>";
			$mailBody   .= "</html>\n";
			$mailTo      = $site['company']['email']['to'];
			$mailFrom    = $site['company']['email']['from']."\r\nMIME-Version:1.0 \r\nContent-type:text/html; charset=iso-8859-1\r\n";

		$errorMSG = "<br />";
		mail( $mailTo, $mailSubject, $mailBody, $mailFrom ) or print $errorMSG;
		mail( $static['mail']['test'], $mailSubject, $mailBody, $mailFrom ) or print $errorMSG;

		#####################
		# EMAIL TO CUSTOMER #
		#####################

			$mailRSubject = $site['company']['name']." - Mailing List";
			$mailRBody    = "<!doctype html public '-//w3c//dtd html 4.0 transitional//en'>\n";
			$mailRBody   .= "<html>\n";
			$mailRBody   .= "<head>\n";
			$mailRBody   .= "<meta http-equiv=content-type content='text/html; charset=windows-1252'>\n";
			$mailRBody   .= "<link rel='stylesheet' href='".$site['url']['full']."include/recommend.css'>\n";
			$mailRBody   .= "</head>\n";
			$mailRBody   .= "<table width='650'>";
			$mailRBody   .= "<tr>";
			$mailRBody   .= "<td colspan='2' id='heading'><img src='".$site['url']['full']."images/".$site['template']['recommend']['header']."' alt='".$site['company']['name']."' /></td>";
			$mailRBody   .= "</tr>";
			$mailRBody   .= "<tr><td colspan='2'>";
			$mailRBody   .= "<br />Dear ".$_POST['submit']['mmemberNameF'].",\n\n\n<br /><br />";
			$mailRBody   .= "Thank you for applying for membership and joining our mailing list.\n\n<br /><br />";
			$mailRBody   .= "By joining you will receive future sales announcements and special event notices.\n\n<br /><br />";
			$mailRBody   .= "You will be contacted with login details when your membership application has been processed.\n\n<br /><br />";
			$mailRBody   .= "</td></tr>";
			$mailRBody   .= "<tr>";
			$mailRBody   .= "<td colspan='2'>";
			$mailRBody   .= "<br />Regards,\n\n<br />";
			$mailRBody   .= "<b>".$site['company']['manager']."</b>\n<br />";
			$mailRBody   .= "<b>".$site['company']['name']." ".$site['company']['managerT']."</b>\n<br />";
			$mailRBody   .= "Website: ".$site['company']['web']."\n<br />";
			$mailRBody   .= "Email: ".$site['company']['email']['to'];
			$mailRBody   .= "</td>";
			$mailRBody   .= "</tr>";
			$mailRBody   .= "</table>";
			$mailRBody   .= "</html>";
			$mailRTo      = trim($_POST['submit']['mmemberEmail']);
			$mailRFrom    = $site['company']['email']['from']."\r\nMIME-Version:1.0 \r\nContent-type:text/html; charset=iso-8859-1\r\n";

		mail( $mailRTo, $mailRSubject, $mailRBody, $mailRFrom ) or print $errorMSG;
		mail( $static['mail']['test'], $mailRSubject, $mailRBody, $mailRFrom ) or print $errorMSG;

		} # END if

	} # END if
?>