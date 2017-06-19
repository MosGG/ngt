<?php
	include 'include/common.inc.php';
	
	## Build an array of the required image fields.

	$imageFields  = "`imageId` int(11) NOT NULL AUTO_INCREMENT,`imageCategory` varchar(50) NOT NULL,`imageTitle` varchar(50) NOT NULL,";
	$imageFields .= "`imageDescription` varchar(255) NOT NULL,`imageSizeX` smallint(6) NOT NULL,`imageSizeY` smallint(6) NOT NULL,";
	$imageFields .= "`imageBorder` varchar(10) NOT NULL,`imageAlignment` varchar(10) NOT NULL,`imageLink` text NOT NULL,";
	$imageFields .= "`imageLinkTrack` char(1) NOT NULL,`imageFile` varchar(120) NOT NULL";

	$requiredImageFields = explode(",",$imageFields);

	## Build an array of the existing image fields.

	$sqlCheck = "SHOW COLUMNS FROM ".$site['database']['edm-image']." ;";
	$checkResult = sql_exec($sqlCheck);
	$counter = 0;
	while ($currentField = $checkResult->fetch_assoc()) {
		$existingImageFields[$counter]= $currentField['Field'];
		$counter++;
	}

	## Build an array of the required pdf fields.

	$pdfFields  = "`pdfId` int(11) NOT NULL AUTO_INCREMENT,`pdfCategory` varchar(50) NOT NULL,`pdfTitle` varchar(50) NOT NULL,";
	$pdfFields .= "`pdfHyperlink` varchar(255) NOT NULL,`pdfFile` varchar(120) NOT NULL";

	$requiredPdfFields =  explode(",",$pdfFields);

	
	## Build an array of the existing pdf fields.

	$sqlCheck = "SHOW COLUMNS FROM ".$site['database']['edm-pdf']." ;";
	$checkResult = sql_exec($sqlCheck);
	$counter = 0;
	while ($currentField = $checkResult->fetch_assoc()) {
		$existingPdfFields[$counter]= $currentField['Field'];
		$counter++;
	}

	## Build an array of the required sent fields.

	$sentFields  = "`mailSId` int(11) NOT NULL AUTO_INCREMENT,`mailSDate` datetime NOT NULL,`mailSMember` smallint(11) NOT NULL,";
	$sentFields .= "`mailSType` char(1) NOT NULL,`mailSEmail` varchar(100) NOT NULL,`mailSMessage` smallint(6) NOT NULL,";
	$sentFields .= "`mailSStatus` char(1) NOT NULL,`mailSVisitDate` datetime NOT NULL,`mailSVisitCount` smallint(6) NOT NULL";

	$requiredSentFields = explode(",",$sentFields);

	
	## Build an array of the existing sent fields.

	$sqlCheck = "SHOW COLUMNS FROM ".$site['database']['edm-sent']." ;";
	$checkResult = sql_exec($sqlCheck);
	$counter = 0;
	while ($currentField = $checkResult->fetch_assoc()) {
		$existingSentFields[$counter]= $currentField['Field'];
		$counter++;
	}
	
	## Build an array of the required member fields.

	$memberFields  = "`mmemberId` int(11) NOT NULL AUTO_INCREMENT,`mmemberType` char(1) NOT NULL,`mmemberTitle` varchar(10) NOT NULL,";
	$memberFields .= "`mmemberNameF` varchar(60) NOT NULL,`mmemberNameS` varchar(60) NOT NULL,`mmemberEmail` varchar(80) NOT NULL,";
	$memberFields .= "`mmemberPassword` varchar(150) NOT NULL,`mmemberDiscount` int(11) NOT NULL,`mmemberAddress` varchar(100) NOT NULL,";
	$memberFields .= "`mmemberSuburb` varchar(40) NOT NULL,`mmemberState` varchar(10) NOT NULL,`mmemberPostcode` varchar(10) NOT NULL,";
	$memberFields .= "`mmemberPhone` varchar(60) NOT NULL,`mmemberDOB` varchar(60) NOT NULL,`mmemberNewsletter` varchar(10) NOT NULL,";
	$memberFields .= "`mmemberSurvey` char(1) NOT NULL,`mmemberSource` char(1) NOT NULL,`mmemberDateCreate` date NOT NULL,`mmemberDateUpdate` date NOT NULL,";
	$memberFields .= "`mmemberUserUpdate` smallint(6) NOT NULL,`mmemberFormat` char(1) NOT NULL,`mmemberStatus` char(1) NOT NULL,";
	$memberFields .= "`mmemberEdmVisitDate` datetime NOT NULL,`mmemberEdmVisitCount` smallint(6) NOT NULL,";
	$memberFields .= "`mmemberMonth` varchar(2) NOT NULL,`mmemberCategories` varchar(60) NOT NULL";
	
	$requiredMemberFields = explode(",",$memberFields);
	
	## Build an array of the existing member fields.

	$sqlCheck = "SHOW COLUMNS FROM ".$site['database']['membership']." ;";
	$checkResult = sql_exec($sqlCheck);
	$counter = 0;
	while ($currentField = $checkResult->fetch_assoc()) {
		$existingMemberFields[$counter]= $currentField['Field'];
		$counter++;
	}
	
	$controller = array('edm-image'=>'Image','edm-pdf'=>'Pdf','edm-sent'=>'Sent','membership'=>'Member');

	#var_dump($controller);

	## Add the required fields if they don't exist in the existing table.

	foreach ($controller as $ckey => $cdata) {	
		
		switch( $ckey ) {
			
			case 'edm-image':
				$requiredFields = $requiredImageFields;
				$existingFields = $existingImageFields;
			break;

			case 'edm-pdf':
				$requiredFields = $requiredPdfFields;
				$existingFields = $existingPdfFields;
			break;
			
			case 'edm-sent':
				$requiredFields = $requiredSentFields;
				$existingFields = $existingSentFields;
			break;

			case 'membership':
				$requiredFields = $requiredMemberFields;
				$existingFields = $existingMemberFields;
			break;

		}

		foreach ($requiredFields as $key=>$data) {
			$fieldArray=explode(" ",$data);
			## If the field doesn't exist 
			if (!in_array(str_replace("`","",$fieldArray[0]),$existingFields)) {
			
				## Add the column
		
				$sqlAlter = "ALTER TABLE ".$site['database'][$ckey]." ADD ".$data." ;";
		
				#echo $sqlAlter."<br />";
		
				$alterResult = sql_exec($sqlAlter);
			}
		}
	}

	## Add the edm-history table to the database.

	$historysql  = "CREATE TABLE IF NOT EXISTS ".$dbPrefix."edm_history` (";
  	$historysql .= "`historyId` int(11) NOT NULL AUTO_INCREMENT,`historyDateTime` datetime NOT NULL,";
  	$historysql .= "`historyUser` smallint(6) NOT NULL,`historyType` char(1) NOT NULL,";
  	$historysql .= "`historyTemplate` varchar(20) NOT NULL,`historyList` char(1) NOT NULL,`historyOptions` varchar(150) NOT NULL,";
  	$historysql .= "`historySubject` char(255) NOT NULL,`historyMessage` text NOT NULL,";
	$historysql .= "`historyCount` smallint(6) NOT NULL,`historyDelete` char(1) NOT NULL,";
	$historysql .= "  PRIMARY KEY (`historyId`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='History of EDMs Sent' AUTO_INCREMENT=3 ;";

	#echo "<br /><br />".$historysql;

	$historyResult = sql_exec($historysql);	

	## Modify the existing edm-sent table

	$sentModsql = "ALTER TABLE ".$site['database']['edm-sent']." CHANGE `mailSMember` `mailSMember` INT( 11 ) NOT NULL; ";
	$sentModResult = sql_exec($sentModsql);
			

	$sentModIIsql = "ALTER TABLE ".$site['database']['edm-sent']." CHANGE `mailSMessage` `mailSMessage` INT( 11 ) NOT NULL; ";
	$sentModResult = sql_exec($sentModIIsql);


	
?>