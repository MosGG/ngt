<?php
	##################################
	## Setup whats to import/export ##

	$table['product']['productTitle']['csv']	= 'Description';
	$table['product']['productPart']['csv']		= 'Stock Code';
	$table['product']['productStock']['csv']	= 'Total Stock';
	$table['product']['productInner']['csv']	= 'Unit / Pack';
	$table['product']['productCarton']['csv']	= 'Carton';
	$table['product']['productPrice1']['csv']	= 'Sell Price';

	$layout  = $table['product'];
	$tableId = $site['database']['product'];


	## Create Field to read from spreadsheet

	foreach ($layout as $key=>$data) {
		if ($data['csv']) {
			$field[$data['csv']]['field'] = $key;
		} 
	}

	############
	## IMPORT ##

	if ($_POST['button'] == 'Import') {
		$lineCount = '1';

		## IF FILE UPLOADS WITHOUT ERROR ##

		if ($_FILES['file']['error'] == '0') {
			## UPLOAD FILE TO SITE ##
			$file	= $site['url']['path'].$site['path']['product']['csv']."product_data_import.csv";
			file_upload($_FILES['file'], $file);
			$fp	= fopen($file, 'r') or die("Couldn't open $file");
			$fields	= fgetcsv($fp, 4096, "\t");

			foreach ($fields as $key=>$data) {
				$field[$data]['position'] = $key;

			}

			## ERROR CHECK - CHECK IF FIELDS IN CSV MATCH FIELDS FOR IMPORT AND ADJUST ERROR MSG TO SUIT ##
			$error = array();

			foreach($layout as $key=>$data) {
				if ($data['csv']) {
					if (!isset($field[$data['csv']]['position'])) {
						$error[] = $data['csv']." field missing!";
					}
				}
			}

			$myob	= fgetcsv($fp, 4096, "\t");

			if ($myob[0] != '@MYOB' || $myob[1] != 'MYOB IMPORT CODE') {
				$error[] = "MYOB export file is corrupted";
			}
		}

			
		## IF FILE IS INVALID FILE ##

		if ($_FILES['file']['error'] != '0') {
			echo "<div id='error'>\n";
			echo "<b>ERROR:</b><br>";
			echo "Invalid file<br>";
			echo "</div>";
			echo "<b>Upload aborted.</b>";
		}

		## IF ANY ERRORS WITH FILE ARE FOUND - DISPLAY ERRORS ##

		if ($error) {
			echo "<div id='error'>\n";
			echo "<b>ERROR:</b><br>";
			echo "<pre>";
			print_r($error);
			echo "</pre>";
			echo "</div>";
			echo "<b>Upload aborted.</b>";
		}

#print_array($fields);
#print_array($field);

		## IF NO ERRORS ARE FOUND - GO FOR UPDATE ##
		$count_insert = 0;
		$count_update = 0;
		if (!$error && $_FILES['file']['error'] == '0') {
			while (!feof($fp)) {
				$lineCount ++;
				$l = fgetcsv($fp, 4096, "\t");
				if ($l && !preg_match("/^ZZ/", $line[0])) {

					$line = array();
					foreach ($l as $k=>$d) {
						$d = str_replace('"', '', $d);
						$line[$k] = $d;
					}

					$sql = 'SELECT * FROM '.$tableId.' WHERE `productPart` = "'.$line[$field['Stock Code']['position']].'"';
					$result	= sql_exec($sql);
					$exist	= $result->fetch_assoc();

#					## FIX QUOTES AND DOUBLE QUOTES ##
#					foreach ($line as $k => $d) {
#						if (is_numeric($line[$k])) {
#							$line[$k] = htmlentities($data, ENT_QUOTES | ENT_HTML401, "UTF-8");
#						}
#					}

					## MAKE SURE productCategory IN CSV IS VALID CATEGORY/STATUS IN WEBSITE ##
					$category = "";
					foreach ($layout['productCategory']['item'] as $c) {
						if ($line[$fieldName['productCategory']] == $c) { $category = "Found"; }
					}
					## REPLACE WITH FIRST CATEGORY IN COMMON INC IF CATEGORY ISNT VALID ##
					if (!$category) {
						$line[$fieldName['productCategory']] = $layout['productCategory']['item']['0'];
					}

					if ($exist) {
						$count_update++;
						$sql  = "UPDATE ".$tableId." SET ";
						$sql .= "`productDateUpdate` = now(), ";
						$sql .= "`productAdmin` = '".$_SESSION['member']['memberId']."'";
						foreach ($field as $key=>$data) {
							if ($data['field']) {
								$sql .= ', `'.$data['field'].'` = "'.$line[$data['position']].'"';
							}
						}
						$sql .= " WHERE `productId` = '".$exist['productId']."'";
						$result	= sql_exec($sql);
					#	echo "Line $lineCount: Updated ".$line[0]."<br />";

#echo "<br>$sql<br>";
					}


					if (!$exist) {
						$count_insert++;
						$sql  = "INSERT INTO ".$tableId." (";
						$sql .= "`productCategory`, ";
						$sql .= "`productFlag`, ";
						$sql .= "`productAdmin`, ";
						$sql .= "`productDateCreate`, ";
						$sql .= "`productDateUpdate`";
						foreach ($field as $key=>$data) {
							if ($data['field']) {
								$sql .= ", `".$data['field']."`";
							}
						}
						$sql .= ") VALUES (";
						$sql .= "'In Stock', ";
						$sql .= "'X', ";
						$sql .= "'".$_SESSION['memeber']['memberId']."', ";
						$sql .= "now(), ";
						$sql .= "now()";
						foreach ($field as $key=>$data) {
							if ($data['field']) {
								$sql .= ', "'.$line[$data['position']].'"';
							}
						}
						$sql .= ")";
						$result	= sql_exec($sql);
						echo "Line $lineCount: Inserted ".$line[0]."<br />";

#echo "<br>$sql<br>";
					}
				}
			} ## END WHILE
			fclose($fp);
			echo "<h3>Export Completed!</h3>";
			echo "<h4>(".$count_update.") Records Updated</h4>";
			echo "<h4>(".$count_insert.") Records Inserted</h4>";
		} ## END GO FOR UPDATE ##
	} ## END IMPORT OF CSV ##

	if ($_POST['button'] != 'Import') {
		echo "<div id='form'>";
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
		echo "<hr />";
		echo "<p>Tab TXT file format is the only supported format, make sure the file you are uploading is correct.<br />Uploading incorrectly formatted files can cause damage or loss of data.</p>\n";
		echo "<hr />";
		echo "</div>";
	} # ($_POST['button'] != 'Import')


?>