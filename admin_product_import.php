<?php
	$field_title[] = 'Id';
	$field_title[] = 'Category';
	$field_title[] = 'Part';
	$field_title[] = 'Title';
	$field_title[] = 'Description';
	$field_title[] = 'Price1';
	$field_title[] = 'Price2';
	$field_title[] = 'Delete';

	$tableId = $site['database']['product'];

	if (($_POST['button'] == 'Import' && $_FILES['file']['error'] == '0')) {
		$file = $site['url']['path'].'content/export/product_data_import.csv';
		file_upload($_FILES['file'], $file);
		$fp = fopen($file, 'r') or die("Couldn't open $file");
		$lineCount = '1';
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
		echo "<p>'I' in Id field to insert a new item.</p>\n";
		echo "<p>'D' in Delete field to remove an item.</p>\n";
		
		echo "</div>";
	} # ($_POST['button'] != 'Import')



	if (($_POST['button'] == 'Import' && $_FILES['file']['error'] == '0') && !$error) {
		$lineCount = '1';
		while (!feof($fp)) {
			$lineCount ++;
			$line = fgetcsv($fp, 4096);
			if ($line) {

				# Fix Quotes and Double Quotes #

				foreach ($line as $k=>$d) {
					$line[$k] = str_replace('"', '&quot;', $d);
					$line[$k] = str_replace("'", '`', $line[$k]);
				}

				# Make sure category is valid #

				$category = "";
				foreach ($table['product']['productCategory']['item'] as $c) {
					if ($line[$fieldName['Category']] == $c) { $category = "Found"; }
				}
				if (!$category) { $line[$fieldName['Category']] = $table['product']['productCategory']['item']['0']; }

				# Update entry if changed

				if ($line[$fieldName['Id']] && $line[$fieldName['Id']] != 'I' && !$line[$fieldName['Delete']]) {
					$sql = "SELECT * FROM ".$tableId." WHERE `productId` = '".$line[$fieldName['Id']]."'";
					$result = sql_exec($sql);
					$data = $result->fetch_assoc();
					$nochange = "";
					foreach ($fieldName as $k=>$d) {
						if ($data['product'.$k] != $line[$d]) { $nochange = "Changed"; }
					}
					if ($nochange == "Changed") {
						echo "<br />[Line $lineCount, Id ".$line[$fieldName['Id']]."] '".$line[$fieldName['Title']]."' - Updated<br />";
						$sql = "UPDATE ".$tableId." SET ";
						foreach ($fieldName as $k=>$d) {
							if ($k != 'Id' && $k != 'Delete') {
								$sql .= '`product'.$k.'` = "'.$line[$d].'", ';
							}
						}						
						$sql .= "`productDateUpdate` = now(), `productAdmin` = '".$_SESSION['member']['memberId']."' WHERE `productId` = '".$line[$fieldName['Id']]."'";
						$result = sql_exec($sql);
					}
				} # if()

				# Insert new record

				if ($line[$fieldName['Id']] == 'I' && !$line[$fieldName['Delete']]) {
					echo "<br />[Line $lineCount, Id ".$line[$fieldName['Id']]."] '".$line[$fieldName['Title']]."' - Inserted<br />";

					$sql  = "INSERT INTO ".$tableId." (";
					foreach ($fieldName as $k=>$d) {
						if ($k != 'Id' && $k != 'Delete') {
							$sql .= "`product$k`, ";
						}
					}
					$sql .= "`productFlag`, `productAdmin`, `productDateCreate`, `productDateUpdate`) VALUES (";
					foreach ($fieldName as $k=>$d) {
						if ($k != 'Id' && $k != 'Delete') {
							$sql .= "'".$line[$d]."', ";
						}
					}
					$sql .= "'N', '".$_SESSION['member']['memberId']."', now(), now())";
					$result = sql_exec($sql);
				}

				# Remove entry if deleted

				if ($line[$fieldName['Id']] && $line[$fieldName['Delete']]) {
					
					
					echo "<br />[Line $lineCount, Id ".$line[$fieldName['Id']]."] '".$line[$fieldName['Title']]."' - Deleted<br />";
					$sql = "UPDATE ".$tableId." SET ";
					$sql .= "`productDateUpdate` = now(), `productAdmin` = '".$_SESSION['member']['memberId']."', `productFlag` = 'D' WHERE `productId` = '".$line[$fieldName['Id']]."'";
					$result = sql_exec($sql);
						
					$sql  = "SELECT * FROM ".$site['database']['product-image']." WHERE `productImageProduct` = '".$line[$fieldName['Id']]."' ORDER BY `productImageOrder`, `productImageTitle`";
					$result = sql_exec($sql);
					while ($lineA = $result->fetch_assoc()) {
					       $image_array[$lineA['productImageId']] = $lineA;
					}
						
					$sql = "DELETE FROM ".$site['database']['product-image']." WHERE `productImageProduct` = '".$line[$fieldName['Id']]."'";
					$result = sql_exec($sql);
						
					if ($image_array) {
						foreach ($image_array as $key=>$data) {
							@unlink($site['url']['path'].$site['path']['product']['thumb'].$data['productImageFile']);
							@unlink($site['url']['path'].$site['path']['product']['full'].$data['productImageFile']);
						}
					}

					$sql = "DELETE FROM ".$site['database']['product-link']." WHERE `product-linkProduct` = '".$line[$fieldName['Id']]."'";
					$result = sql_exec($sql);
											

#					$sql  = "SELECT * FROM ".$site['database']['product-pdf']." WHERE `productPdfProduct` = '".$line[$fieldName['Id']]."'";
#					$result = sql_exec($sql);
#					$lineA = $result->fetch_assoc();
#					if ($lineA) {
#						@unlink($site['url']['path'].$site['path']['product']['pdf'].$lineA['productPdfFile']);
#						$sql = "DELETE FROM ".$site['database']['product-pdf']." WHERE `productPdfProduct` = '".$line[$fieldName['Id']]."'";
#						$result = sql_exec($sql);
#					}	
		
				}

			}
		}
		fclose($fp);

	}

?>