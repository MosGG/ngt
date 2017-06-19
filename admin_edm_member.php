<?php
	echo content_converter($page['pageText']);

	$table['membership']['mmemberType']['display']		= 'Y';
	$table['membership']['mmemberType']['search']		= 'Y';
	$table['membership']['mmemberType']['searchHeading']	= 'Type';
	$table['membership']['mmemberTitle']['display']		= 'Y';

	$table['membership']['mmemberBusiness']['display']	= 'Y';
	$table['membership']['mmemberBusiness']['required']	= 'Y';
	$table['membership']['mmemberBusiness']['search']	= 'Y';
	$table['membership']['mmemberBusiness']['searchHeading']= 'Business';

	$table['membership']['mmemberABN']['display']		= 'Y';
	$table['membership']['mmemberABN']['search']		= 'Y';

	$table['membership']['mmemberNameF']['display']		= 'Y';
#	$table['membership']['mmemberNameF']['required']	= 'Y';
	$table['membership']['mmemberNameF']['search']		= 'Y';
	$table['membership']['mmemberNameF']['searchHeading']	= 'First Name';

	$table['membership']['mmemberNameS']['display']		= 'Y';
#	$table['membership']['mmemberNameS']['required']	= 'Y';
	$table['membership']['mmemberNameS']['search']		= 'Y';
	$table['membership']['mmemberNameS']['searchHeading']	= 'Surname';

	$table['membership']['mmemberEmail']['display']		= 'Y';
	$table['membership']['mmemberEmail']['search']		= 'Y';
	$table['membership']['mmemberPassword']['display']	= 'Y';
	$table['membership']['mmemberAddress']['display']	= 'Y';
	$table['membership']['mmemberSuburb']['display']	= 'Y';
	$table['membership']['mmemberState']['display']		= 'Y';
	$table['membership']['mmemberPostcode']['display']	= 'Y';
	$table['membership']['mmemberPhone']['display']		= 'Y';

	$table['membership']['mmemberMobilePhone']['display']   = 'Y';
	$table['membership']['mmemberMobilePhone']['search']	= 'Y';
	$table['membership']['mmemberMobilePhone']['searchHeading']	= 'Mobile';
	$table['membership']['mmemberSMSStatus']['display']	= 'Y';
	$table['membership']['mmemberSMSStatus']['insert']	= 'N';
	$table['membership']['mmemberSMSStatus']['search']	= 'Y';
	$table['membership']['mmemberSMSStatus']['searchHeading'] = 'SMS Status';
	$table['membership']['mmemberSMSList']['display']	= 'Y';
	$table['membership']['mmemberSMSList']['search']	= 'Y';
	$table['membership']['mmemberSMSList']['searchHeading']	= 'SMS List';

	$table['membership']['mmemberDiscount']['display']	= 'Y';
	$table['membership']['mmemberDateCreate']['display']	= 'Y';
	$table['membership']['mmemberDateCreate']['insert']	= 'NOW';
	$table['membership']['mmemberDateCreate']['update']	= 'NULL';
	$table['membership']['mmemberDateUpdate']['display']	= 'Y';
	$table['membership']['mmemberDateUpdate']['insert']	= 'NOW';
	$table['membership']['mmemberDateUpdate']['update']	= 'NOW';
	$table['membership']['mmemberUserUpdate']['display']	= 'N';
	$table['membership']['mmemberUserUpdate']['insert']	= $_SESSION['member']['memberId'];
	$table['membership']['mmemberUserUpdate']['update']	= $_SESSION['member']['memberId'];
	$table['membership']['mmemberStatus']['display']	= 'Y';
	$table['membership']['mmemberStatus']['insert']		= 'A';
	$table['membership']['mmemberStatus']['search']		= 'Y';
	$table['membership']['mmemberStatus']['searchHeading']	= 'EDM Status';
	$table['membership']['buttonInsert']['display']		= 'Y';
	$table['membership']['buttonUpdate']['display']		= 'Y';
	$table['membership']['buttonSearch']['display']		= 'Y';
	$table['membership']['buttonCancel']['display']		= 'Y';

	$layout  = $table['membership'];
	$tableId = $site['database']['membership'];

	$message['insert'] = "Successfully added new member.";

	echo "<div id='adminpage'>";
	include '/home/hosting/template-v0d/admin_table.php';
	echo "</div>";

	## Send a confirmation email to a customer who has been 'activated'.

	if ($_POST['submit']['mmemberStatus']=='A' && $_POST['submit']['button']=='Update' && !$error) {
	
		## Check that the customer hasn't been validated previously.

		$checkIfActiveSql 	= "SELECT `mmemberValidated` FROM ".$site['database']['membership']." WHERE `mmemberId`='".$_SESSION['edit']."' ;";
		$checkIfActiveResult 	= sql_exec($checkIfActiveSql);
		$checkIfActiveValue	= $checkIfActiveResult->fetch_assoc();
		
		## If the customer hasn't been validated, set the validate flag and send them an email containing their username and password.
		if ($checkIfActiveValue['mmemberValidated']=='') {
			$validateSql 	= "UPDATE ".$site['database']['membership']." SET `mmemberValidated` = 'A' WHERE `mmemberId`='".$_SESSION['edit']."' ; ";
			$validateResult = sql_exec($validateSql);
			
			$mailRSubject = $site['company']['name']." - Membership";
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
			$mailRBody   .= "Your username is: ".$_POST['submit']['mmemberEmail']."\n\n<br /><br />";
			$mailRBody   .= "Your password is: ".$_POST['submit']['mmemberPassword']."\n\n<br /><br />";
			$mailRBody   .= ".\n\n<br /><br />";
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

		}
	}

?>