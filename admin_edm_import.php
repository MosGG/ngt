<?php

	function valid( $type, $input ) {

	       ## Function to validate various strings on forms etc.

	       # $type	 = the type of string you are checking for. (phone,mobile,email)
	       # $input  = the string you wish to check

		switch( $type ) {

			## Validate a phone number
			case 'phone':
				$pattern = "/^([1]-)?[0-9]{3}-[0-9]{3}-[0-9]{4}$/i";
			break;

			## Validate an Australian phone/mobile number
			case 'ozphone':
				$pattern = "/^\(?(?:\+?61|0)(?:(?:2\)?[ -]?(?:3[ -]?[38]|[46-9][ -]?[0-9]|5[ -]?[0-35-9])|3\)?(?:4[ -]?[0-57-9]|[57-9][ -]?[0-9]|6[ -]?[1-67])|7\)?[ -]?(?:[2-4][ -]?[0-9]|5[ -]?[2-7]|7[ -]?6)|8\)?[ -]?(?:5[ -]?[1-4]|6[ -]?[0-8]|[7-9][ -]?[0-9]))(?:[ -]?[0-9]){6}|4\)?[ -]?(?:(?:[01][ -]?[0-9]|2[ -]?[0-57-9]|3[ -]?[1-9]|4[ -]?[7-9]|5[ -]?[018])[ -]?[0-9]|3[ -]?0[ -]?[0-5])(?:[ -]?[0-9]){5})$/";
			break;

			## validate email address
			case 'email':
				$pattern = "/^[_a-z0-9-]+(.[_a-z0-9-]+)*[@]+([a-z0-9-])+(.[a-z0-9-]+)*(.[a-z]{2,3})$/i";
			break;
			
			case 'emailUser':
				$pattern = "/^[_a-z0-9-]+(.[_a-z0-9-]+)*@$/i";
			break;
			
			case 'emailDomain':
				$pattern = "/^[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/i";
			break;

			default:
				return false;
			break;

		}

		return preg_match($pattern, $input) ? true : false;
	}

	$field_title[] = 'A/C No.';
	$field_title[] = 'Code';
	$field_title[] = 'Name';
	$field_title[] = 'Address1';
	$field_title[] = 'Address2';
	$field_title[] = 'Address3';
	$field_title[] = 'Address4';
	$field_title[] = 'Phone';
	$field_title[] = 'Contact';
	$field_title[] = 'Primary Group';
	$field_title[] = ' Balance';
	$field_title[] = 'Email';
	$field_title[] = 'Price Level';
	
	$tableId = $site['database']['membership'];

	if (($_POST['button'] == 'Import' && $_FILES['file']['error'] == '0')) {
		
		## Backup the database before it's manipulated.
		
		#$backupfile = "edm-table-".time().".sql";
		#$fx = $site['url']['path'].'content/edm/backup/'.$backupfile.''; 
		#$bsql = "SELECT * INTO OUTFILE '".$fx."' FROM ".$tableId."";
		#$result = sql_exec($bsql);

		## End Backup
		
		$file = $site['url']['path'].'content/export/edm_data_import.csv';
		file_upload($_FILES['file'], $file);
		$fp = fopen($file, 'r') or die("Couldn't open $file");
		$lineCount = '1';
######		#$fields = fgetcsv($fp, 4096, "\t");
		$fields = fgetcsv($fp, 4096);

		$error = array();
		foreach ($fields as $key=>$data) {
			$fieldName[$data] = $key;
			$error[$data] = "Field doesn't Exist";
		} # ($fields as $key=>$data)

		foreach ($field_title as $field) {
			if (!isset($fieldName[$field])) {
				$error[$field] = "Field is Missing";
			} else {
				unset($error[$field]);
			}
		} # ($field_title as $field)
		
	} # ($_POST['button'] == 'Import' && $_FILES['file']['error'] == '0')

	if ($_POST['button'] == 'Import' && $_FILES['file']['error'] != '0') {
		echo "<div id='error'>\n";
		echo "<b>ERROR:</b><br>";
		echo "Invalid file<br>";
		echo "</div>";
		echo "<b>Upload aborted.</b>";
	} # ($_POST['button'] == 'Import' && $_FILES['file']['error'] != '0')

	if ($_POST['button'] == 'Import' && $error) {
		echo "<div id='error'>\n";
		echo "<b>ERROR:</b><br>";
		echo "<pre>";
		print_r($error);
		echo "</pre>";
		echo "</div>";
		echo "<b>Upload aborted.</b>";
	} # ($_POST['button'] == 'Import' && $_FILES['file']['error'] != '0')

	if ($_POST['button'] != 'Import') {
		echo "<div id='form'>";
		echo "<table class = 'table'>";
		echo "<tr><td><a href='".$site['url']['full'].$site['path']['pdf']."ImportingEDM.pdf' target='1'>Click here</a> to view the Import guide.</td></tr>";
		echo "</table>";

		echo "<form enctype='multipart/form-data' action='".$site['url']['actual']."' method='post'>";
		echo '<input type="hidden" name="MAX_FILE_SIZE" value="8096000">';
		echo "<table id='form' class='table'>\n";
		echo "<tr>\n";
		echo "<th align='left'>Product File:</th>\n";
		echo "<td><input type='file' name='file' size='60'></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<th colspan='2'><input type='submit' name='button' value='Import'></th>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</form>\n";
		echo "<p>Ensure it is a csv file that you are uploading</p>\n";
		echo "</div>";
	
	} # ($_POST['button'] != 'Import')

	if (($_POST['button'] == 'Import' && $_FILES['file']['error'] == '0') && !$error) {
		$updateCount=0;
		$insertCount=0;
		$dodgeyCount=0;
		$existingCount=0;
		$missingCount=0;
		$goodMail = 0;
		
		#$lineCount = '1';

		while (!feof($fp)) {
			$lineCount ++;
			#$line = fgetcsv($fp, 4096,"\t");
			$line = fgetcsv($fp, 4096);
			
			unset($errorMail);
			unset($existline);			
			unset($errorMulti);

			if ($line) {

				# Fix Quotes and Double Quotes #

				foreach ($line as $k=>$d) {
					$line[$k] = str_replace('"', '&quot;', $d);
					$line[$k] = str_replace("'", '`', $line[$k]);
				}
				## Clean up existing and 'new' email addresses in the import file.
				## ( This will attempt to correct both new and existing addresses.)
					
				## Pull carriage and line breaks from the email address.

				$line[$fieldName['Email']] =  str_replace("\x0a",'',$line[$fieldName['Email']]);
				$line[$fieldName['Email']] =  str_replace("\x0d",'',$line[$fieldName['Email']]);
				
				## Pull &nbsp; from the email address.
				
				$line[$fieldName['Email']] =  str_replace("\xa0",'',$line[$fieldName['Email']]);
				
				## 'Trim' the email address from the import file.
					
				$line[$fieldName['Email']] = trim($line[$fieldName['Email']]);
			
				## Check that the email address from this line of the import file is valid.			

				
				if( !valid( 'email',$line[$fieldName['Email']] ) ) {

					## If the email address fails the validity test, break it in 'half' and check it again as 'name@' and 'domain' to ensure that
					## it is in fact invalid. This will negate an issue with PHP and regex errors with longer than 22 character domain names in an email string.
		        	
					if($mailDivisor = strpos($line[$fieldName['Email']],'@')) {
						$mailLength  = strlen($line[$fieldName['Email']]);

						$mailPartA = substr($line[$fieldName['Email']],0,$mailDivisor+1);
						$mailPartB = substr($line[$fieldName['Email']],($mailDivisor-$mailLength+1),($mailLength-$mailDivsior+1));			
						
						if( !valid( 'emailUser',$mailPartA ) ) {
							#echo "Username Invalid<br/>";
							$errorUser='T';
						}
					
						if( !valid( 'emailDomain',$mailPartB ) ) {
							#echo "Domain Invalid<br/>";
								$errorDomain='T';
							}
						}

						## If the two halves of the email address are valid then the email address is valid.

						if ($mailDivisor = strpos($line[$fieldName['Email']],'@') && !$errorUser && !$errorDomain) {
							$itsAllGood='T';
						} else {
							if ($line[$fieldName['Email']] !='') {
								echo "<b>Invalid email address for : </b>".$line[$fieldName['Name']]." ".$line[$fieldName['NameS']]." <br><b>Email : </b>".$line[$fieldName['Email']]."<br />";
								echo "<b>Member not inserted/updated.</b><br /><br />";
								$dodgeyCount++;
							} else {
								$missingCount++;
								}
							
							$errorMail='T';
							
    						 }
				
						unset($errorUser);
						unset($errorDomain);
					}

				
				if (!$errorMail) { 

					unset($fullAddress);
					unset($mobilePhone);
					unset($landline);
					unset($firstName);
					unset($lastName);
					unset($discountLevel);
					unset($password);					
					unset($typeByState);

					$goodMail++;

					## Convert the four part address into a single address.					

					$fullAddress = 	$line[$fieldName['Address1']]." ".$line[$fieldName['Address2']]." ".$line[$fieldName['Address3']]." ".$line[$fieldName['Address4']];
					
					## Convert the supplied phone number to a mobile or a landline.

					$strippedPhone = preg_replace("/[^\x30-\x39]*/","", $line[$fieldName['Phone']]);
					
					if (substr($strippedPhone,0,1)=='4' || substr($strippedPhone,0,2)=='04' ) {
						$mobilePhone = $strippedPhone;
						$landline = '';
					} else {
						$mobilePhone = '';
						$landline = $strippedPhone;
					  }
									
					## Explode the name field on space to firstname lastname (Everything after the first space goes to the last name field, delimited with a preceeding space.)
					
					$twoPartName = explode(" ",$line[$fieldName['Contact']]);
					$nameArrayLength = count($twoPartName);
					#echo "<h1>".$nameArrayLength."</h1><br />";
					$firstName = $twoPartName[0];
					if ($nameArrayLength > 1) {
						$arrayCounter = 1;
						while ($arrayCounter < $nameArrayLength ) {
							$lastName .= $twoPartName[$arrayCounter];
							$arrayCounter++;
							if ($arrayCounter < $nameArrayLength) {
								$lastName .= " ";
							}
						}
					} else {
						$lastName = '';
					  }
					
					## Set the discount level.
					
					if ($line[$fieldName['Price Level']]=='2') {
							$discountLevel = 20;
						} elseif ($line[$fieldName['Price Level']]=='3') {
							$discountLevel = 25;
						  } else {
							   $discountLevel = 0;
							 }

					## Create the password.

					$emailBits = explode("@",$line[$fieldName['Email']]);
					$password = $emailBits[0];
					
					## Create a member type based on state.

					switch($line[$fieldName['Primary Group']])
						{
   							case 'VIC':
   								$typeByState = 'V';
							break;
							
							case 'NSW':
   								$typeByState = 'N';
							break;
							
							case 'TAS':
								$typeByState = 'A';
							break;
							
							case 'WA':
								$typeByState = 'M';
							break;
							
							case 'QLD':
								$typeByState = 'Q';
							break;
							
							case 'SA':
								$typeByState = 'S';
							break;
							
							case 'ACT':
								$typeByState = 'Z';
							break;
							
							case 'NT':
								$typeByState = 'D';
							break;
							
							case 'OVERSEAS':
								$typeByState = 'O';
							break;
							
							default:
        							$typeByState = '';
    							break;
					}
					
					## If the Email address is valid check to see if the member already exists in the member table.

					$csql = "SELECT count(*) FROM ".$tableId." WHERE `mmemberEmail` = '".$line[$fieldName['Email']]."';";
					$cresult = sql_exec($csql);
					$tline = $cresult->fetch_assoc();
					$memberCount = $tline['count(*)'];
					
					## If a member doesn't exist, insert a new member

					if ($memberCount==0) {

						$sql  = "INSERT INTO ".$tableId." (";
						$sql .= "`mmemberBusiness`, `mmemberNameF`, `mmemberNameS`, `mmemberAddress`, `mmemberPhone`, `mmemberMobilePhone`,";	
						$sql .= "`mmemberDiscount`, `mmemberStatus`, `mmemberType`,`mmemberEmail`,`mmemberPassword`,`mmemberState`, ";
						
						$sql .= "`mmemberUserUpdate`, `mmemberDateCreate`, `mmemberDateUpdate`, `mmemberSource`) VALUES (";

						$sql .= "'".$line[$fieldName['Name']]."','".$firstName."', '".$lastName."', '".$fullAddress."', '".$landline."', '".$mobilePhone."',";
						$sql .= "'".$discountLevel."', 'A','".$typeByState."','".$line[$fieldName['Email']]."', '".$password."','".$line[$fieldName['Primary Group']]."',";
						$sql .= "'".$_SESSION['member']['memberId']."', now(), now(), 'B');";
					
						#echo $sql."<br />";
					
						$result = sql_exec($sql);
						$insertCount++;


					} else {
						## If a member exists, only update the discount code,password and state if no value exists.
						
						#$sql = "SELECT * FROM ".$tableId." WHERE `mmemberEmail` = '".$line[$fieldName['Email']]."';";
						
						$sql = "SELECT * FROM ".$tableId." WHERE `mmemberEmail` = '".$line[$fieldName['Email']]."' AND `mmemberBusiness` = '".$line[$fieldName['Name']]."';";
						$result = sql_exec($sql);
						
						while($currentRecord = $result->fetch_assoc()) {
							unset($performUpdate);
							if ($discountLevel != $currentRecord['mmemberDiscount']) {
								
								$isql  = "UPDATE ".$tableId." SET ";
								$isql .= "`mmemberDiscount` = '".$discountLevel."' ";
							
								if (!$currentRecord['mmemberPassword']) {
									$isql .= ",`mmemberPassword` = '".$password."'";
								}
								
								if (!$currentRecord['mmemberState']) {
									$isql .= ",`mmemberState` = '".$line[$fieldName['Primary Group']]."'";
								}
									
								$isql .= ",`mmemberUserUpdate` = '".$_SESSION['member']['memberId']."'";
								$isql .= ",`mmemberDateUpdate` = now()";
								$isql .= " WHERE `mmemberId` = '".$currentRecord['mmemberId']."' ; ";
								$performUpdate='T';

							} elseif (!$currentRecord['mmemberPassword']) {
									$isql  = "UPDATE ".$tableId." SET ";
									$isql .= "`mmemberPassword` = '".$password."'";
									
									if (!$currentRecord['mmemberState']) {
										$isql .= ",`mmemberState` = '".$line[$fieldName['Primary Group']]."'";
									}
									
									$isql .= ",`mmemberUserUpdate` = '".$_SESSION['member']['memberId']."'";
									$isql .= ",`mmemberDateUpdate` = now()";
									$isql .= " WHERE `mmemberId` = '".$currentRecord['mmemberId']."' ; ";
									$performUpdate='T';
															
							   } elseif (!$currentRecord['mmemberState']) {
										$isql  = "UPDATE ".$tableId." SET ";
										$isql .= "`mmemberState` = '".$line[$fieldName['Primary Group']]."'";
										$isql .= ",`mmemberUserUpdate` = '".$_SESSION['member']['memberId']."'";
										$isql .= ",`mmemberDateUpdate` = now()";
										$isql .= " WHERE `mmemberId` = '".$currentRecord['mmemberId']."' ; ";
										$performUpdate='T';
									
							     }
							

							if ($performUpdate=='T') {
								
								$uresult = sql_exec($isql);
								$updateCount++;
								
							}
						}
					  }
				}

	

			}
		}
		
		fclose($fp);

		## Check for replicated businesses in the import file with different discount levels. 
		$variedDiscounts = array();
		$activeDuplicates = array();
		$duplcateCount=0;
		$lineCount = '1';
		$fp = fopen($file, 'r') or die("Couldn't open $file");

		while (!feof($fp)) {
			$lineCount ++;
			#$line = fgetcsv($fp, 4096, "\t");
			$line = fgetcsv($fp, 4096);
			if ($line) {
				## Add every line of the input file to an array as Price Level / Business Name.
				
				array_push($activeDuplicates,$line[$fieldName['Price Level']]."|".$line[$fieldName['Name']]);
			}
			
		}
		
		fclose($fp);
		
		foreach ($activeDuplicates as $key=>$data) {
			$namePart = explode("|",$data);
			$instanceCount=0;
			$levelCount = 1;

			while ($levelCount<3) {
					$test = array_keys($activeDuplicates,$levelCount."|".$namePart[1]);
					if ($test) { $instanceCount++;}
					$levelCount++;
			}
			if ($instanceCount > 1) { 
						array_push($variedDiscounts, $namePart[1]);
			}
		}

	
		if(is_array($variedDiscounts)) {
			$uniqueVariedDiscounts = array_unique($variedDiscounts);
		}
			

		## End replicated business check.


		## Display summary of import.		

		echo "<br />";
		echo "<table>";
		echo "<tr><td colspan='2'><b>Import Summary:</b></td></tr>";
		echo "<tr><td>Members Updated:</td><td>".$updateCount."</td></tr>";
		echo "<tr><td>Members Imported:</td><td>".$insertCount."</td></tr>";
		echo "<tr><td colspan='2'><hr></td></tr>";
		echo "<tr><td colspan='2'><b>Import File Summary:</b></td></tr>";
		echo "<tr><td>Valid Email Addresses in import file :</td><td>".$goodMail."</td></tr>";		
		echo "<tr><td>Invalid Email Addresses in import file :</td><td>".$dodgeyCount."</td></tr>";
		echo "<tr><td>Missing Email Addresses in import file :</td><td>".$missingCount."</td></tr>";
		if ($uniqueVariedDiscounts) {
			echo "<tr><td colspan='2'>Businesses with multiple price levels:</td></tr>";
			foreach ($uniqueVariedDiscounts as $ukey=>$udata) {
				echo "<tr><td colspan='2'>".$udata."</td></tr>";
			}
		}	
		echo "<tr><td colspan='2'><hr></td></tr>";
		echo "<tr><td colspan='2'><b>EDM Database Summary:</b></td></tr>";
			
		$xxsql = "SELECT count(*) FROM ".$tableId." WHERE `mmemberEmail` != '' AND `mmemberType` = '' AND `mmemberStatus` = 'A';";
		$xxresult = sql_exec($xxsql);
		$xxline = $xxresult->fetch_assoc();
		$xxmemberCount = $xxline['count(*)'];

		$zzsql = "SELECT count(*) FROM ".$tableId." WHERE `mmemberEmail` = '' AND `mmemberStatus` = 'A' ;";
		$zzresult = sql_exec($zzsql);
		$zzline = $zzresult->fetch_assoc();
		$zzmemberCount = $zzline['count(*)'];

		$rrsql = "SELECT * FROM ".$tableId." WHERE `mmemberEmail` != '' AND `mmemberType` = '' AND `mmemberStatus` = 'A';";
		$rrresult = sql_exec($rrsql);
		$rrline = $rrresult->fetch_assoc();
			
		if ($zzmemberCount > 0) {
			echo "<tr><td>Active EDM Members with no email address :</td><td>".$zzmemberCount."</td></tr>"; 
		}
		if ($xxmemberCount > 0) {
			echo "<tr><td>Active EDM Members with an email address not in a message list :</td><td>".$xxmemberCount."</td></tr>"; 
			echo "<tr><td><b>Member:</b></td><td><b>Email:</b></td></tr>";
			while($rrline = $rrresult->fetch_assoc()) {
				echo "<tr><td>".$rrline['mmemberNameF']." ".$rrline['mmemberNameS']."</td><td>".$rrline['mmemberEmail']." </td></tr>";
			}	
		}


		echo "<tr><td>Duplicate Email Addresses in EDM Database:</td><td> </td></tr>";
		echo "<tr><td><b>Address:</b></td><td><b>Instances:</b></td></tr>";
		## Display a summary of duplicate email addresses in the EDM table.
			
		$dsql = "SELECT `mmemberEmail`, COUNT(*) AS COUNT FROM `newg_edm_member` GROUP BY `mmemberEmail` HAVING COUNT > 1 ;";
		$dresult = sql_exec($dsql);
		while ($dvalues = $dresult->fetch_assoc()) {
			if ($dvalues['mmemberEmail'] != '') { 
				echo "<tr><td>".$dvalues['mmemberEmail']."</td><td>".$dvalues['COUNT']." </td></tr>";
			}
		}




		echo "</table>";
	
	}

?>