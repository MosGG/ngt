<?php

	## Set session variables 'On Change'..

	if ($_POST['submit']) {
		$_SESSION['SelectedSMSList'] = $_POST['submit']['smsList'];
		
		## Remove HTML slashes.
		$_SESSION['NewOptOutMessage'] = stripslashes($_POST['submit']['optOut']);
		## Replace 's to provide text input FIELD friendly values. 
		$_SESSION['NewOptOutMessage'] = str_replace("'",'`',$_SESSION['NewOptOutMessage']);


		## Remove HTML slashes.
		$_SESSION['NewSMSMessage'] =  stripslashes($_POST['submit']['Message']);
		## Remove carriage returns and line breaks.
		$_SESSION['NewSMSMessage'] =  str_replace("\x0a",'',$_SESSION['NewSMSMessage']);
		$_SESSION['NewSMSMessage'] =  str_replace("\x0d",'',$_SESSION['NewSMSMessage']);
	}

	## Perform error checks and assign the appropriate error value to $errorDetected.	

	if ($_POST['sendSMS']) {
		
		## Check to ensure there is some text in the opt out message field (And that the 'text' is not just spaces)		
		
		if (!$_POST['submit']['optOut'] || (strlen(trim($_POST['submit']['optOut']," "))==0)) {
			$errorDetected = "Check Opt Out Message.";
		}

		## Check to ensure there is some text in the SMS message field	(And that the 'text' is not just spaces or carriage returns.)	
		
		if (!$_POST['submit']['Message'] || (strlen(trim($_POST['submit']['Message'],"\x20\x0a\x0d"))==0)) {
			$errorDetected = "Check SMS message text.";
		}
		
		## Check for a combined message of 160 characters or less.		

		if ((strlen($_SESSION['NewOptOutMessage'])+strlen($_SESSION['NewSMSMessage']))>159) {
			$errorDetected = "".(strlen($_SESSION['NewOptOutMessage'])+strlen($_SESSION['NewSMSMessage'])+ 1)." characters. ( Maximum is 160. )";
		}
			
		## Check to ensure a list has been selected, has members and sufficient credit is available.
		
		if (!$_SESSION['SelectedSMSList'] || $_SESSION['SelectedSMSList'] == 'NotSelected') {
			$errorDetected = "No SMS List selected.";
		
		} else {

			## Check List length against available SMS credit
				
			$sql = "SELECT COUNT(*) FROM ".$site['database']['membership']." WHERE `mmemberSMSList` = '".$_SESSION['SelectedSMSList']."' AND `mmemberSMSStatus` = 'A' ;";

			$countResult = sql_exec($sql);	
			$memberCount = mysql_result($countResult, 0);
		
			if ($memberCount > $_SESSION['balanceData']['smsBalance']) {
				$errorDetected = "Insufficient credit! ".$memberCount." members in this list.<br />";
			}
			if ($memberCount < 1) {
				$errorDetected = "There are no active members in this list.<br />";
			}
		 }
	}

	## Add credits to the sms table

	if ($_POST['submit']['addCredits']) {
		
		## Check that the data entered is valid.
		
		if (is_numeric(trim($_POST['submit']['addCredits']))) {
			
			##Add record to the sms table.
			$updatedCreditBalance = $_POST['submit']['addCredits'] + $_SESSION['balanceData']['smsBalance'];			

			$sql  = "INSERT INTO ".$site['database']['sms']." ";
			$sql .= " (`smsDate`, `smsCredit`, `smsBalance`, `smsUserId`, `smsOptOut`, `smsMessage`, `smsList` ) VALUES ";
			$sql .= " (now(),'".$_POST['submit']['addCredits']."','".$updatedCreditBalance."','".$_SESSION['member']['memberId']."','".$_SESSION['balanceData']['smsOptOut']."','".$_SESSION['balanceData']['smsMessage']."','".$_SESSION['balanceData']['smsList']."');";
			
			$addCreditResult = sql_exec($sql);
			unset($_POST['submit']);
			} else {
				echo "<h2>ERROR - Please enter a whole number. (No credits added.)</h2>";
		  }
	}

	## Get the available SMS balance from the sms table. ( Populate $_SESSION['balanceData'] with the last record in the SMS table.)
	
	$sql="SELECT * FROM ".$site['database']['sms']." ORDER BY `smsId` DESC LIMIT 1 ;";
	$balanceResult = sql_exec($sql);	
	$_SESSION['balanceData'] = $balanceResult->fetch_assoc();

	## Display the available SMS balance.
	
	echo "<table>";
	
	echo "<tr><td><b>Available SMS Credit: <b></td><td><b>".$_SESSION['balanceData']['smsBalance']."</b></td></tr>";

	## Display the add credit input field if the user has sufficient access.	

	if ($_SESSION['access']>60) {

		echo "<form action='".$site['url']['actual']."' method='post'";	
		echo "<tr><td><b>Amount of Credits to add:</b></td><td><input type='text' name='submit[addCredits]'>";
		echo " <input type='submit' name='addCredits' value='Add Credits' /</td></tr>";
		echo "</form>";
		echo "<tr><td>&nbsp;</td></tr>";
		echo "<tr><td>&nbsp;</td></tr>";	

	}

	## Create the SMS 'list' dropdown.
	
	if ($_GET['oldList']) {
		$_SESSION['SelectedSMSList']=$_GET['oldList'];
        } else {
		#if( $_SESSION['SelectedSMSList'] || ($_SESSION['SelectedSMSList'] && $_SESSION['SelectedSMSList']=='')) {
		if( $_POST['submit']) {
			## All is well with the world.
		} else {
			$_SESSION['SelectedSMSList'] = $_SESSION['balanceData']['smsList'];
	  	  }
	  }	

	echo "<form action='".$site['url']['actual']."' method='post'>";
	echo "<tr>";
	echo "<td><b>Select SMS List: </b></td><td><select name='submit[smsList]' onChange='form.submit()'>";
	echo "<option value='NotSelected' selected = 'selected'></option>"; 
		
	foreach ($table['membership']['mmemberSMSList']['item'] as $lkey=>$ldata) {

		if ($_SESSION['SelectedSMSList']==$lkey) { 
			$selected='selected';
	
		} else {
			$selected='';
		  }
	
		echo "<option value='".$lkey."' ".$selected." >$ldata</option>"; 
 		       
	}

	echo "</td>";
	echo "</select>";
	echo "</tr>";
		
	## Display the 'Opt Out' input field. 

	if ($_GET['oldOpt']) {
		$optOutMessage = stripslashes($_GET['oldOpt']);
	} else {

		#if ($_SESSION['NewOptOutMessage'] || ($_SESSION['NewOptOutMessage'] && $_SESSION['NewOptOutMessage']=='')) {
		if ($_POST['submit']) {
			## Display the updated message.
			$optOutMessage = $_SESSION['NewOptOutMessage'];
        	} else {
			## Display the message from the last SMS sent.
			$optOutMessage = $_SESSION['balanceData']['smsOptOut'];	
	  	}
	}
	
	echo "<tr><td><b>Opt Out Message:</b></td><td><input type='text' name='submit[optOut]' value = '".$optOutMessage."' size='35' onChange='form.submit()'></td>";

	## Display the 'SMS Message' input Text Area. 
	
	if ($_GET['useOldMessage']) {
		$SMSMessage = stripslashes($_GET['useOldMessage']);
	} else {
		#if ($_SESSION['NewSMSMessage'] || ($_SESSION['NewSMSMessage'] && $_SESSION['NewSMSMessage']=='')) {
		if ($_POST['submit']) {
			## Display the updated message.
			$SMSMessage = $_SESSION['NewSMSMessage'];
        	} else {
			## Display the message from the last SMS sent.
			$SMSMessage = $_SESSION['balanceData']['smsMessage'];	
	  	  }
	 }

	$initialCharactersRemaining = 159 - (strlen($optOutMessage)+strlen($SMSMessage)); 	
	$includeOptOutMaxLength = 159 - strlen($optOutMessage);

	echo "<tr><td><b>SMS Message:</b></td><td><textarea rows='4' cols='35' class='inputfield' name='submit[Message]' maxlength='$includeOptOutMaxLength' onChange='form.submit()'>$SMSMessage</textarea></td><td><b class='remain'>".$initialCharactersRemaining." /160 characters remain</b></td></tr>";
 	echo "<tr><td></td><td><input type='submit' name='sendSMS' value='Send' /></td></tr>";
	
	## Display errors if they exist
	
	if ($errorDetected) {
		echo "<tr><td></td><td><h5><b>".$errorDetected." SMS not sent!</b></h5></td></tr>";
	}

	echo "</form>";
	

	## Display the Send Report.

	if ($_SESSION['SendReport']) {
		echo "<tr><td></td><td><h1><< SMS SENT >></h1></td></tr>";
		echo "<tr><td></td><td colspan='2'>".$_SESSION['SendReport']."</td></tr>";
		unset($_SESSION['SendReport']);
	}

	## Send the SMS emails if no errors have been detected.		

	if ($_POST['sendSMS'] && !$errorDetected) {
		
		echo "<tr><td></td><td><h1><< SENDING SMS >></h1></td></tr>";
		
		## Retrieve the appropriate records from the edm member table and send the SMS details to SMS GLobal.		

		$smsBody = $SMSMessage." ".$optOutMessage;
		
		## Replace ` with ' in email to prevent Unicode conversion of message by SMS Global.
		
		$smsBody = str_replace('`',"'",$smsBody);		

		$site['sms']['email'] = "From: ".$site['company']['name']."<".$static['sms']['email'].">";
		
		$sql = "SELECT * FROM ".$site['database']['membership']." WHERE `mmemberMobilePhone` <> '' AND `mmemberSMSList` = '".$_SESSION['SelectedSMSList']."' AND `mmemberSMSStatus` = 'A' ;";
		$result = sql_exec($sql);
		$count = 0;
		
		while ($data = $result->fetch_assoc()) {
			
			## Check that the mobile number is a 10 digit integer.
			
			if ((strlen(preg_replace('/\D/', '', $data['mmemberMobilePhone']))==10)) {		
				
				$smsMobile = preg_replace('/\D/', '', $data['mmemberMobilePhone'])."@sms.smsglobal.com.au";
				mail($smsMobile, $smsSubject, $smsBody, $site['sms']['email']."\r\nMIME-Version:1.0 \r\nContent-type:text/html; charset=iso-8859-1\r\n" ) or print $errorMSG;
				$count ++;
				
			} else {
				## Add members with invalid mobile numbers to a variable for display when 'send' is complete.

				$invalidMobileNumbers .= $data['mmemberNameF']." ".$data['mmemberNameS']." - <b>".$data['mmemberMobilePhone']."</b><br />";

			 }		

		}

		if ($count > 0) {
			#$notifyMobile	= "0408568871@sms.smsglobal.com.au";
			$notifyMobile	= "0419582942@sms.smsglobal.com.au";
			$notifySubject	= "SMS Notification";
			$notifyBody	= "A Bulk sms has been sent for ".$site['company']['name']." : ".$count." txts sent";
			mail($notifyMobile, $notifySubject, $notifyBody, $site['sms']['email']."\r\nMIME-Version:1.0 \r\nContent-type:text/html; charset=iso-8859-1\r\n" ) or print $errorMSG;
		}
	
		## Add the SMS to the SMS database table ,deducting the appropriate amount of credits.
	
		$reducedCreditBalance = $_SESSION['balanceData']['smsBalance'] - $count;			
	
		## Make SMS message SQL friendly.		

		$sqlFriendlyMessage = str_replace("'",'`',$SMSMessage);
		
		$sql  = "INSERT INTO ".$site['database']['sms']." ";
		$sql .= " (`smsDate`, `smsBalance`, `smsDebit`, `smsUserId`, `smsOptOut`, `smsMessage`, `smsList` ) VALUES ";
		$sql .= " (now(),'".$reducedCreditBalance."','".$memberCount."','".$_SESSION['member']['memberId']."','".$optOutMessage."','".$sqlFriendlyMessage."','".$_SESSION['SelectedSMSList']."');";
			
		$insertNewSMS = sql_exec($sql);

		## Generate the "Send" report.
		
		$_SESSION['SendReport']  = "<b>".$count."</b> message(s) sent.<br />";

		if ($invalidMobileNumbers) {
			$_SESSION['SendReport'] .= "<br />The following members have invalid mobile numbers : <br/>".$invalidMobileNumbers;
		}
		
		## Refresh the page after send.
		
		echo "<meta http-equiv='refresh' content='0;url=".$site['url']['actual']."'>";
	}	

	echo "</table>";
	echo "<br />";
	echo "<hr>";
	
	## Display retrievable Historical SMS data.
	
	if (!$_SESSION['SendReport']) {
		echo "<table>";
		echo "<tr><th><b>SMS History:</b></th></tr>";
		echo "<tr><th><b>Date:</b></th><th><b>SMS List:</b></th><th><b>Message:</b></th></tr>";
	
		$hsql = "SELECT * FROM 	".$site['database']['sms']." WHERE `smsDebit` > 0 ORDER BY `smsDate` DESC;";
		$hresult = sql_exec($hsql);
	
		while ($hdata = $hresult->fetch_assoc()) {
		
			## Create the hyperlink to reset the SMS Message.

			echo "<tr><td>".date("d-m-Y",strtotime($hdata['smsDate']))."</td><td>".$table['membership']['mmemberSMSList']['item'][$hdata['smsList']]."</td><td><a name='arp' href='".$site['url']['actual']."?useOldMessage=".$hdata['smsMessage']."&oldList=".$hdata['smsList']."&oldOpt=".$hdata['smsOptOut']."'>".substr($hdata['smsMessage'],0,100)."...</a></td></tr>";
		}
	
		echo "</table>";
	}
?>
