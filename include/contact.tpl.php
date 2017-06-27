<style>
/* contact */
#contactpage>form{
	width:592px;
	margin:0 auto;
}
#contactinfo-table{
	margin:0 auto;
	padding: 50px 0 30px 0;
}
#contactinfo-table .contactheading{
	text-align: center;
}
#contactinfo-table td{
	font-size: 14px;
	padding: 5px 5px;
}
#contactinfo-table a{
	color: #4A4A4A;
	text-decoration: none;
}
#contactinfo-table a:hover{
	color: #4ABDAC;
}
#enquiry-table{
	width:592px;
	padding:15px;
	border: 1px solid #4A4A4A;
	margin: 0 auto 60px auto;
}
#enquiry-table td{
	padding:10px 15px;
	font-size: 16px;
	position: relative;

}
#contact-submit{
	width:114px;
	height:40px;
	color:#4ABDAC;
	border:2px solid #4ABDAC;
	font-family: Montserrat-Bold;
	font-size: 16px;
	line-height: 34px;
	transition: all .3s linear;
	background-color: #EFEFEF;
}
#contact-submit:hover{
	color:#FFF;
	background-color: #4ABDAC;
}
#enquiry-table textarea,
#enquiry-table select,
#enquiry-table input{
	width:calc(100% - 20px);
	font-size: 16px;
	border:1px solid #4A4A4A;
	background-color: #EFEFEF;
	font-family: Montserrat;
	color:#4A4A4A;
}
#enquiry-table select,
#enquiry-table input{	
	height:40px;
	padding:0 10px;
}
#enquiry-table textarea{
	height: 214px;
	padding:10px 10px;
}
#enquiry-title{
	font-weight: bold;
	font-size: 18px;
}
#enquiry-required{
	font-family: Montserrat-Light;
	font-size: 12px;
	/*padding: 20px 0;*/
}
.red-star{
	color:#FC4A1A;
	padding-right: 3px;
}
.contact-sub-des{
	position: relative;
	font-family: Montserrat-Ultralight;
	/*top:50px;*/
	z-index:10;
	font-size: 14px;
}
#contact-confirm td{
	padding: 10px;
}
#contact-confirm input[type='submit']{
    display: inline-block;
    width: 114px;
    height: 44px;
    background-color: #EFEFEF;
    border: 2px solid #4ABDAC;
    /*line-height: 27px;*/
    color: #4ABDAC;
    font-weight: bold;
    font-size: 16px;
    text-align: center;
    text-decoration: none;
    transition: all .2s linear;
}
#contact-confirm input[type='submit']:hover{
	color:#fff;
	background-color: #4ABDAC;
}
</style>
<?php
	##############################################################################################################
	# UPDATES
	#
	# 14/11/2010 - David	- Added Mandatory fields to contact form
	# 30/06/2011 - Jamie	- Added HTML5 Code to Input Fields for Supported Browsers (Doesn't affect older Browsers)
	# 07/07/2011 - Janelle	- Changed separate tables into one table so that everything lines up
	# 24/11/2011 - Janelle	- Cleaned up formatting, changed entire file to php and added type='text' to input fields
	# 09/12/2011 - Jamie	- Added stripslashes($mailBody) to mail cmd to remove / slashes from mailbody
	# 19/12/2011 - William	- Changed thankyou message to be wrapped by div with class contactThankyou instead of blockquote
	# 19/01/2012 - Jamie 	- Altered line in String that sends email to business as it wasn't correct and being overiden further down.
	# 17/02/2012 - William	- If there is only one enquiry type in common inc, it will use a hidden field and not display a dropdown with only 1 option
	# 23/02/2012 - Janelle	- Added Business Hours to contact details
	# 08/03/2012 - William	- Removed HTML5
	#
	##############################################################################################################

	$_SESSION['contactMode'] = "Details";
	
	unset($error_field);

	## Mandatory Fields ##
	if ($_POST['enquiry'] && strlen($_POST['enquiry']['Name']) < 3)  { $error_field['Name']  = 'Y'; }
	if ($_POST['enquiry'] && strlen($_POST['enquiry']['Email']) < 7) { $error_field['Email'] = 'Y'; }
	if ($_POST['enquiry'] && strlen($_POST['enquiry']['Phone']) < 8) { $error_field['Phone'] = 'Y'; }
	if ($_POST['enquiry'] && strlen($_POST['enquiry']['ABN']) < 5)   { $error_field['ABN'] = 'Y'; }
	if ($_POST['enquiry'] && !$_POST['enquiry']['Description'])      { $error_field['Description'] = 'Y'; }

	## Buttons ##
	if (($_POST['Button'] == "Continue" || $_POST['button-continue_x']) && !$error_field) {
		$_SESSION['contactMode'] = "Confirm";
	}
	if ($_POST['Back'] == "Edit Details" || $_POST['button-edit_x']) {
		$_SESSION['contactMode'] = "Details";
	}
	if ($_POST['Button'] == "Submit" || $_POST['button-submit_x']) {
		$_SESSION['contactMode'] = "Process";
	}

	if ($_POST[enquiry]) {
		$_SESSION['enquiry'] = $_POST[enquiry];
	}

#	if ($_GET['description']) {
#		$_SESSION['enquiry']['Description'] = $_GET['description'];
#	}

	if ($static['server']['name'] == 'pluto.ubcserver.com') {
#		if ($site['email']['development']) {
			$site['company']['email']['to'] = $site['email']['development'];
#		} else {
#			$site['company']['email']['to'] = $static['mail']['dev'];
#		}
	}

	echo "<div id='contactpage'>";
	echo "<form action='".$_SERVER['REQUEST_URI']."' name='Confirm' method='post'>";

	################################
	# DETAILS MODE
	################################

	if ($_SESSION['contactMode'] == "Details") {
	
		if ($_SESSION['enquiry']['Type']) {
			$subject = $_SESSION['enquiry']['Type'];
		}
		$subject = $_GET["subject"];

		if ($_GET['description']) {
			$_SESSION['enquiry']['Description'] = 'Enquiry about '.$_GET['description'].':'.$_SESSION['enquiry']['Description'];
		}

		echo $ContactMessage;

		// $RecommendImage = "recommend.jpg";
		// if ($ContactRecommendImage) {
		// 	$RecommendImage  = $ContactRecommendImage;
		// }
		// if ($ContactRecommendImage != "") {
		// 	echo "<br /><a href='".$site['url']['full']."recommend'><img src='".$site['url']['full']."images/".$RecommendImage."' alt='Click here to recommend ".$site['company']['name']." to a friend' border='0' /></a>";
		// }

		echo "<table summary='' id='contactinfo-table'>"; 
			if ($contact['business']) {
				echo "<tr>";
					echo "<td colspan='2' class='contactheading'><img src='/images/new/footer-company.png'></td>";
					echo "<td><b>".$site['company']['name'];
					if ($contact['abn']) {
						echo " - ABN: ".$site['company']['abn'];
					} 
					if ($contact['acn']) {
						echo " - ACN: ".$site['company']['acn'];
					} 
					echo "</b></td>";
				echo "</tr>";
			}
			if ($contact['manager']) {
				echo "<tr>";
					echo "<td colspan='2' class='contactheading'>".$site['company']['managerT'].":</td>";
					echo "<td><b>".$site['company']['manager']."</b></td>";
				echo "</tr>";
			}
			if ($contact['hours']) {
				echo "<tr>";
					echo "<td colspan='2' class='contactheading'>Business Hours:</td>";
					echo "<td><b>".$site['company']['hours']."</b></td>";
				echo "</tr>";
			}
			if ($contact['mobile']) {
				echo "<tr>";
					echo "<td colspan='2' class='contactheading'>Mobile:</td>";
					echo "<td><b>".$site['company']['mobile']."</b></td>";
				echo "</tr>";
			}
			if ($contact['phone']) {
				echo "<tr>";
					echo "<td colspan='2' class='contactheading'><img src='/images/new/footer-tel.png'></td>";
					echo "<td><b>".$site['company']['phone']."</b></td>";
				echo "</tr>";
			}
			if ($contact['fax']) {
				echo "<tr>";
					echo "<td colspan='2' class='contactheading'><img src='/images/new/footer-fax.png'></td>";
					echo "<td><b>".$site['company']['fax']."</b></td>";
				echo "</tr>";
			}
			if ($contact['postal']) {
				echo "<tr>";
					echo "<td colspan='2' class='contactheading'>Mail:</td>";
					echo "<td><b>".$site['company']['postal']."</b></td>";
				echo "</tr>";
			}
			if ($contact['address']) {
				echo "<tr>";
					echo "<td colspan='2' class='contactheading'><img src='/images/new/footer-location.png'></td>";
					echo "<td><b>".$site['company']['address']."</b></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td colspan='2' class='contactheading'><img src='/images/new/footer-location.png'></td>";
					echo "<td><b>6/25-33 Alfred Rd, Chipping Norton, NSW 2170</b></td>";
				echo "</tr>";
			}
			if ($contact['mapref']) {
				echo "<tr>";
					echo "<td colspan='2' class='contactheading'>Map Reference:</td>";
					echo "<td><b>".$site['company']['mapref']."</b></td>";
				echo "</tr>";
			}
			if ($contact['email']) {
				echo "<tr>";
					echo "<td colspan='2' class='contactheading'><img src='/images/new/footer-email.png'></td>";
					echo "<td><b>".encrypt_email($site['company']['email']['to'], $site['company']['email']['to'], $site['company']['name']." Enquiry")."</b></td>";
				echo "</tr>";
			}
			echo "<tr>";
				echo "<td colspan='2' class='contactheading'><img src='/images/new/footer-website.png'></td>";
				echo "<td><b><a href='/'>www.newglobalmel.com.au</a></b></td>";
			echo "</tr>";
			if ($site['company']['email']['enabled'] != "y") {
				echo "<tr>";
					echo "<td colspan='2'></td>";
					echo "<td><b>DISABLED</b></td>";
				echo "</tr>";
			}
			echo "</table>";
			echo "<table id='enquiry-table'>";
			echo "<tr><td align='center' colspan='2' id='enquiry-title'>ENQUIRES</td></tr>";
			echo "<tr><td align='right' colspan='2' id='enquiry-required'><span class='red-star'>*</span>Required</td></tr>";
			// echo "<tr>";
			// 	echo "<td colspan='3'><br />or <b>Submit</b> your details below and we will contact you promptly.<br /><br /></td>";
			// echo "</tr>";
			## CHECK IF CONTACTTYPE HAS MORE THAN ONE OPTION BEFORE BOTHERING WITH A DROPDOWN/SELECT ##
			$bother = count($contactType);
			if ($bother > 1) {
				echo "<tr>";
					echo "<td><b>Enquiry Type:</b></td>";
					echo "<td></td>";
					echo "<td>";
						echo "<select name='enquiry[Type]'>";
							foreach($contactType as $key=>$value) {
								$Sel = "";
								if ($subject == $value) { $Sel = "selected='selected'"; };
								echo "<option $Sel>".$value."</option>";
							}
						echo "</select>";
					echo "</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td>&nbsp;</td>";
				echo "</tr>";
			} else {
				echo "<tr style='display:none'><td><input type='hidden' name='enquiry[Type]' value='".$contactType['0']."' /></td></tr>";
			}
			echo "<tr>";
				if($error_field['Name']) {
					$error_on  = "<span class='error'>";
					$error_off = "</span>";
				} else {
					$error_on  = "";
					$error_off = "";
				}
				echo "<td><span class='red-star'>*</span><b>".$error_on."Name:".$error_off."</b></td>";
				if($error_field['Phone']) {
					$error_on  = "<span class='error'>";
					$error_off = "</span>";
				} else {
					$error_on  = "";
					$error_off = "";
				}
				echo "<td><span class='red-star'>*</span><b>".$error_on."Contact Number:".$error_off."</b></td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td><input type='text' name='enquiry[Name]' value='".$_SESSION['enquiry']['Name']."' id='name' title='Enter Your Name' maxlength='100' /></td>";
				echo "<td><input type='text' name='enquiry[Phone]' title='Please enter 10 digit phone number' type='text' maxlength='20' value='".$_SESSION['enquiry']['Phone']."' /></td>";
			echo "</tr>";
			echo "<tr>";
				if($error_field['ABN']) {
					$error_on  = "<span class='error'>";
					$error_off = "</span>";
				} else {
					$error_on  = "";
					$error_off = "";
				}
				echo "<td colspan='2'><span class='red-star'>*</span><b>".$error_on."ABN:".$error_off."</b></td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td colspan='2'><input name='enquiry[ABN]' title='' type='text' maxlength='20' value='".$_SESSION['enquiry']['ABN']."' /></td>";
			echo "</tr>";
			echo "<tr>";
				if($error_field['Email']) {
					$error_on  = "<span class='error'>";
					$error_off = "</span>";
				} else {
					$error_on  = "";
					$error_off = "";
				}
				echo "<td colspan='2'><span class='red-star'>*</span><b>".$error_on."Email:".$error_off."</b></td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td colspan='2'><input name='enquiry[Email]' value='".$_SESSION['enquiry']['Email']."' type='text'/></td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td><b>Address:</b></td>";
			echo "</tr>";
			echo "<tr>";
				// echo "<td>&nbsp;</td>";
				echo "<td colspan='2'><input type='text' name='enquiry[Address]' value='".$_SESSION['enquiry']['Address']."' /><span class='contact-sub-des'>Street Address</span></td>";
			echo "</tr>";
			echo "<tr>";
				// echo "<td><b>Suburb:</b></td>";
				// echo "<td>&nbsp;</td>";
				echo "<td><input type='text' name='enquiry[Suburb]' value='".$_SESSION['enquiry']['Suburb']."' /><span class='contact-sub-des'>Suburb</span></td>";

				// echo "<td><b>Postcode:</b></td>";
				// echo "<td>&nbsp;</td>";
				echo "<td><input type='text' name='enquiry[Postcode]' value='".$_SESSION['enquiry']['Postcode']."' /><span class='contact-sub-des'>Postcode</span></td>";
			echo "</tr>";
			echo "<tr>";
				// echo "<td><b>State:</b><br /></td>";
				// echo "<td>&nbsp;</td>";
				echo "<td>";
					echo "<select name='enquiry[State]'>";
						echo "<option selected='selected'>Victoria</option>";
						echo "<option>New South Wales</option>";
						echo "<option>ACT</option>";
						echo "<option>Queensland</option>";
						echo "<option>Northern Territory</option>";
						echo "<option>Western Australia</option>";
						echo "<option>South Australia</option>";
						echo "<option>Tasmania</option>";
						echo "<option>International Enquiry</option>";
					echo "</select>";
				echo "<span class='contact-sub-des'>State</span></td>";
			echo "</tr>";
			// echo "<tr>";
			// 	echo "<td>&nbsp;</td>";
			// echo "</tr>";
			echo "<tr>";
				if($error_field['Description']) {
					$error_on  = "<span class='error'>";
					$error_off = "</span>";
				} else {
					$error_on  = "";
					$error_off = "";
				}
				echo "<td><span class='red-star'>*</span><b>".$error_on."Comments:".$error_off."</b></td>";
			echo "</tr><tr>";
				// echo "<td>".$error_on."*".$error_off."</td>";
				echo "<td colspan='2'><textarea cols='38' rows='5' name='enquiry[Description]'>".$_SESSION['enquiry']['Description']."</textarea></td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td colspan='1'>&nbsp;</td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td align='center' colspan='2'>";
				if ($button['contact']['continue']['image']) {
					echo "<input type='image' src='".$site['url']['full']."images/".$button['contact']['continue']['image']."' name='button-continue' width='".$button['contact']['continue']['width']."' height='".$button['contact']['continue']['height']."'/> ";
				} else {
					echo "<button id='contact-submit' type='submit' name='Button' value='Continue'>Submit</button>";
				}
				echo "</td>";
			echo "</tr>";
			// echo "<tr>";
			// 	if($error_field) {
			// 		$error_on  = "<strong><span class='error'>";
			// 		$error_off = "</span></strong>";
			// 	} else {
			// 		$error_on  = "";
			// 		$error_off = "";
			// 	}
			// 	echo "<td colspan='2'><br /><br />".$error_on."* Indicates mandatory fields".$error_off."</td>";
			// echo "</tr>";
		echo "</table>";

		$_SESSION['contactMode'] == "Confirm";

	}

	################################
	# DETAILS MODE END
	################################

	################################
	# CONFIRM MODE
	################################

	if ($_SESSION['contactMode'] == "Confirm") {

		echo "<table id='contact-confirm' border='0' cellpadding='5' cellspacing='0' align='center'>";
			echo "<tr>";
				echo "<td colspan='2' style='border-bottom:1px solid #979797;'>";
					echo "<h2>";
						echo "Are the details correct? &nbsp;&nbsp;&nbsp;";
						// if ($button['contact']['edit']['image']) {
						// 	echo "<input type='image' src='".$site['url']['full']."images/".$button['contact']['edit']['image']."' name='button-edit' width='".$button['contact']['edit']['width']."' height='".$button['contact']['edit']['height']."'/> ";
						// } else {
						// 	echo "<input type='submit' name='Back' value='Edit Details' size='10' />";
						// }
					echo "</h2>";
				echo "</td>";
			echo "</tr>";
			if ($_SESSION['enquiry']['Name']) {
				echo "<tr>";
					echo "<td width='90'><b>Name:</b></td>";
					echo "<td width='300'>".$_SESSION['enquiry']['Name']."</td>";
				echo "</tr>";
			}
			if ($_SESSION['enquiry']['ABN']) {
				echo "<tr>";
					echo "<td width='90'><b>ABN:</b></td>";
					echo "<td width='300'>".$_SESSION['enquiry']['ABN']."</td>";
				echo "</tr>";
			}
			if ($_SESSION['enquiry']['Email']) {
				echo "<tr>";
					echo "<td><b>Email:</b></td>";
					echo "<td>".$_SESSION['enquiry']['Email']."</td>";
				echo "</tr>";
			}
			if ($_SESSION['enquiry']['Phone']) {
				echo "<tr>";
					echo "<td><b>Phone:</b></td>";
					echo "<td>".$_SESSION['enquiry']['Phone']."</td>";
				echo "</tr>";
			}
			if ($_SESSION['enquiry']['Address']) {
				echo "<tr>";
					echo "<td><b>Address:</b></td>";
					echo "<td>".$_SESSION['enquiry']['Address']."</td>";
				echo "</tr>";
			}
			if ($_SESSION['enquiry']['Suburb']) {
				echo "<tr>";
					echo "<td><b>Suburb:</b></td>";
					echo "<td>".$_SESSION['enquiry']['Suburb']."</td>";
				echo "</tr>";
			}
			if ($_SESSION['enquiry']['Postcode']) {
				echo "<tr>";
					echo "<td><b>Postcode:</b></td>";
					echo "<td>".$_SESSION['enquiry']['Postcode']."</td>";
				echo "</tr>";
			}
			if ($_SESSION['enquiry']['State']) {
				echo "<tr>";
					echo "<td><b>State:</b></td>";
					echo "<td>".$_SESSION['enquiry']['State']."</td>";
				echo "</tr>";
			}
			if ($_SESSION['enquiry']['Description']) {
				echo "<tr>";
					echo "<td><b>Comments:</b></td>";
					// echo "<td>";
					// echo "<table width='300' cellpadding='0' cellspacing='0'>";
					// 	echo "<tr>";
							echo "<td>".$_SESSION['enquiry']['Description']."</td>";
					// 	echo "</tr>";
					// echo "</table>";
					// echo "</td>";
				echo "</tr>";
			}
			echo "<tr>";
				echo "<td align='center' colspan='2' style='border-top: 1px solid #979797;padding:20px;'><input type='submit' name='Back' value='Edit Details' size='10' />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='Button' value='Submit' /></td>";
			echo "</tr>";
		echo "</table>";


	}

	################################
	# CONFIRM MODE END
	################################

	################################
	# PROCESS MODE
	################################

	if ($_SESSION['contactMode'] == "Process") {

		$sql  = "INSERT INTO ".$site['database']['contact']." (";
		$sql .= "contactSiteId ,";
		$sql .= "contactSession ,";
		$sql .= "contactName ,";
		$sql .= "contactABN ,";
		$sql .= "contactAddress ,";
		$sql .= "contactSuburb ,";
		$sql .= "contactPostcode ,";
		$sql .= "contactState ,";
		$sql .= "contactPhone ,";
		$sql .= "contactEmail ,";
		$sql .= "contactTitle ,";
		$sql .= "contactDescription";
		$sql .= ") VALUES ( ";
		$sql .= "'".$site['id']."', "; 
		$sql .= "'".$_SESSION['log']."', "; 
		$sql .= "'".$_SESSION['enquiry']['Name']."', ";
		$sql .= "'".$_SESSION['enquiry']['ABN']."', ";
		$sql .= "'".$_SESSION['enquiry']['Address']."', ";
		$sql .= "'".$_SESSION['enquiry']['Suburb']."', "; 
		$sql .= "'".$_SESSION['enquiry']['Postcode']."', ";
		$sql .= "'".$_SESSION['enquiry']['State']."', ";
		$sql .= "'".$_SESSION['enquiry']['Phone']."', ";
		$sql .= "'".$_SESSION['enquiry']['Email']."', ";
		$sql .= "'".$_SESSION['enquiry']['Type']."', ";
		$sql .= "'".$_SESSION['enquiry']['Description']."'";
		$sql .= ");";
		$result = sql_exec($sql);

		## Change Email From ##
#		$site['mailFrom'] = $_SESSION['enquiry']['Email'];

		## Thank You Message ##
		echo "<div class='contactThankyou'>";
			echo "<p style='font-size:72px;color:#4ABDAC;font-weight:bold;'>THANK YOU!</p>";
			echo "<p>Your enquiry has been sent to ".$site['company']['name'].".</p>";
			echo "<p>You will be contacted shortly.</p>";
		echo "</div>";


		if ($site['company']['email']['enabled'] == "y") {

			#####################
			#  SMS To Business  #
			#####################

			if ($site['sms']['number'] && $static['sms']['enabled'] == 'y') {
				$mailSubject = "";
				$smsBody     = "<".$_SESSION['log']."> ";
				$smsBody    .= $_SESSION['enquiry']['Name'].",".$_SESSION['enquiry']['Type'].". ";
				$smsBody    .= "P:".$_SESSION['enquiry']['Phone']." E:".$_SESSION['enquiry']['Email']." ";
				$smsBody    .= $_SESSION['enquiry']['Description'];	
				mail($site['sms']['number'], $smsSubject, $smsBody, $site['sms']['email']."\r\nMIME-Version:1.0 \r\nContent-type:text/html; charset=iso-8859-1\r\n" ) or print $errorMSG;
				mail($static['sms']['email'], "SMS COPY", $site['company']['name'].$smsBody, $site['sms']['email']."\r\nMIME-Version:1.0 \r\nContent-type:text/html; charset=iso-8859-1\r\n" ) or print $errorMSG;
			}

			#######################
			#  Email To Business  #
			#######################

			$mailSubject = "[".$_SESSION['log']."] ".$site['company']['name']." - ".$_SESSION['enquiry']['Type'];
			$mailBody    = "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.0 Transitional//EN'>\n";
			$mailBody   .= "<html>\n";
			$mailBody   .= "<head>\n";
			$mailBody   .= "<meta http-equiv=Content-Type content='text/html; charset=windows-1252'>\n";
			$mailBody   .= "<link rel='stylesheet' href='".$site['url']['full']."include/recommend.css'>\n";
			$mailBody   .= "</head>\n";
			$mailBody   .= "<body>\n";
			$mailBody   .= "<table width='600' border='1' bordercolor='#000000' cellpadding='0' cellspacing='0'>\n";
			$mailBody   .= "<tr>\n";
			$mailBody   .= "<td><img src='".$site['url']['full']."images/email-header.jpg' alt='".$site['company']['name']." - ".$site['company']['web']."' border='0' /></td>\n";
			$mailBody   .= "</tr>\n";
			$mailBody   .= "<tr>\n";
			$mailBody   .= "<td><br />\n";
			$mailBody   .= $site['company']['name'].",<br /><br /><br />\n\n\n";
			$mailBody   .= $_SESSION['enquiry']['Name']." has submitted an enquiry - ".$_SESSION['enquiry']['Type'].".<br /><br />\n\n";
			$mailBody   .= "Can be contacted on ".$_SESSION['enquiry']['Phone']." or email <a href='mailto:".$_SESSION['enquiry']['Email']."'>".$_SESSION['enquiry']['Email']."</a><br />\n";
			$mailBody   .= "ABN: ".$_SESSION['enquiry']['ABN']."<br /><br />\n\n";
			$mailBody   .= "<u>Location details:</u><br />\n".$_SESSION['enquiry']['Address']." <br />\n".$_SESSION['enquiry']['Suburb']." ".$_SESSION['enquiry']['Postcode']." ".$_SESSION['enquiry']['State']."<br /><br />\n\n";
			$mailBody   .= "<hr /><br />\n";	
			$mailBody   .= $_SESSION['enquiry']['Description']."<br /><br />\n\n";	
			$mailBody   .= "<hr />\n";	
			$mailBody   .= "</td>";
			$mailBody   .= "</tr>";
			$mailBody   .= "</table>";
			$mailBody   .= "</body>\n";
			$mailBody   .= "</html>\n";
			$mailTo      = $site['company']['email']['to'];
#			$mailFrom    = $site['company']['email']['from'];
			$mailFrom    = $_SESSION['enquiry']['Email'];

#			$errorMSG = "<br />Could not send mail - Email address incorrect";
			$errorMSG = "<br />";

			mail( $mailTo, $mailSubject, stripslashes($mailBody), "From: ".$mailFrom."<".$mailFrom.">\r\nMIME-Version:1.0 \r\nContent-type:text/html; charset=iso-8859-1\r\n" ) or print $errorMSG;
			mail( $static['mail']['test'], $mailSubject, stripslashes($mailBody), "From: ".$_SESSION['enquiry']['Email']."<".$_SESSION['enquiry']['Email'].">\r\nMIME-Version:1.0 \r\nContent-type:text/html; charset=iso-8859-1\r\n" ) or print $errorMSG;
#			mail( "test@ubc.net.au", $mailSubject, $mailBody, $mailFrom ) or print $errorMSG;

			#######################
			#  Email To Customer  #
			#######################

			$mailRSubject = $site['company']['name']." - Enquiry";
			$mailRBody    = "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.0 Transitional//EN'>\n";
			$mailRBody   .= "<html>\n";
			$mailRBody   .= "<head>\n";
			$mailRBody   .= "<meta http-equiv=Content-Type content='text/html; charset=windows-1252'>\n";
			$mailRBody   .= "<link rel='stylesheet' href='".$site['url']['full']."include/recommend.css'>\n";
			$mailRBody   .= "</head>\n";
			$mailRBody   .= "<body>\n";
			$mailRBody   .= "<table width='600' border='1' bordercolor='#000000' cellpadding='0' cellspacing='0'>\n";
			$mailRBody   .= "<tr>\n";
			$mailRBody   .= "<td><img src='".$site['url']['full']."images/email-header.jpg' alt='".$site['company']['name']." - ".$site['company']['web']."' border='0' /></td>\n";
			$mailRBody   .= "</tr>\n";
			$mailRBody   .= "<tr>\n";
			$mailRBody   .= "<td><br />\n";
			$mailRBody   .= "Thank you ".$_SESSION['enquiry']['Name'].",<br /><br /><br />\n\n\n";
			$mailRBody   .= "Your enquiry has been sent to ".$site['company']['name'].".<br /><br /><br />\n\n\n";
			$mailRBody   .= "Regards,<br /><br />\n\n";
#			$mailRBody   .= "<img src='http://".$site['company_web']."/images/#' /><br />\n";
			$mailRBody   .= $site['company']['manager']."<br />\n";
			$mailRBody   .= $site['company']['name']." ".$site['company']['managerT']."<br />\n";
			$mailRBody   .= $site['company']['email']['to']."\n";
			$mailRBody   .= "</td>\n";
			$mailRBody   .= "</tr>\n";
			$mailRBody   .= "</table>\n";
			$mailRBody   .= "</body>\n";
			$mailRBody   .= "</html>\n";
			$mailRTo      = trim($_SESSION['enquiry']['Email']);
			$mailRFrom    = $site['company']['email']['from']."\r\nMIME-Version:1.0 \r\nContent-type:text/html; charset=iso-8859-1\r\n";	

			mail( $mailRTo, $mailRSubject, stripslashes($mailRBody), $mailRFrom ) or print $errorMSG;
			mail( $static['mail']['test'], $mailRSubject, stripslashes($mailRBody), $mailRFrom ) or print $errorMSG;
		}

		$sql  = "UPDATE ".$site['database']['logSession'];
		$sql .= " SET";
		$sql .= " logSStatus  = 'S'";
		$sql .= " WHERE logSId = '".$_SESSION['log']."' AND logSSiteId = '".$site['id']."';";
		$result = sql_exec($sql);

		$_SESSION['enquiry'] = "";

	}

	################################
	# PROCESS MODE
	################################

	echo "</form>";
	echo "</div>"
?>