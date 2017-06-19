<?php
	#####################################################################################
	# UPDATES
	#
	# 08/11/2010 - David	- Fixed Span error with page tree when editing
	# 21/06/2011 - David	- Load Multiple photos
	# 12/07/2011 - Janelle  - Added option for notes next to input, text and dropdown fields
	# 05/12/2011 - David	- Added if around category selection to stop foreach error
	# 31/01/2012 - William	- Added sorting ability to list of product (unassigned and search)
	# 31/01/2012 - William	- Cleaned some code, removed excess spacing, removed unnecessary notes and unused duplicated code
	# 30/05/2012 - William	- Added updated ckEditor/FCKEditor code. Left old code in it's place just hashed out incase of issues.
	# 19/07/2012 - William	- Fixed issue of editor not clearing all details after leaving the page... unset($_SESSION['submit']) if not $_GET['edit'];
	# 20/07/2012 - William	- Yesterdays fix caused the product editor to show false results when searching because the submit session was being cleared even if a post only just put something in it, its been fixed with a if not post around it
	# 30/01/2013 - William	- Removed the browse field while editing an image
	# 			- Made image cancel button actually work, this never seemed to have code to actually act on a person cancelling
	# 			- Fixed issue of session storing product information after a person leaves the page, then returns
	# 			- Updated full cancel button to clear everything used by this form and unset GET variables to properly cancel
	# 11/02/2013 - David	- Search button ahead of insert
	# 29/07/2013 - William	- Added safety if around a foreach on line 413. $_FILES loop which produced error when no file fields are in the form.
	# 04/11/2013 - William	- Added Virtual Product Extension interface allowing people to have sub-products for each of their products.
	#			- Increased Validity of HTML markup
	# 05/11/2013 - William	- Completed Virtual Product Extension addition started yesterday
	# 19/11/2013 - William	- Minor tidying of code
	#			- Went to add auto-ordering of photo as they are bulk uploaded, but it already existed
	# 15/05/2014 - William	- Fixed several warnings of illegal offsets for the values of fields by using an isset() check 
	#			  in ternary expression to set the default values for empty form
	# 22/05/2014 - William	- Updated all mysql functions to mysqli
	# 18/08/2014 - William	- Fixed several E_WARNING messages about illegal offsets for buttons pressed after hitting cancel
	# 12/12/2014 - Steve	- Added Product ID to Product display listing
	# 26/02/2015 - William	- Added anchors at each section heading to return users to their last position when they update, delete, upload, unassign etc a product.
	# 23/04/2015 - William	- Added better handling of file extensions for file uploads at the time of insertion of new records - see variable $ext in upload sections.
	# 28/04/2015 - William	- Removed support for old fckEditor, leaving only support for ckEditor
	#			- Removed requirement of $_SESSION['edit'] to edit and work with products and switched to a hidden field and post variable EDIT_IDENT to identify the currently edited product
	#				- This allows multiple instances of the Product Editor to be open each editing a different product without clashing or overwriting each other.
	#				- To be clear - the session variable edit still exists and is set as always. But in this core code is not used. May be used elsewhere though, unsure.
	#			- Added integer typecasting to some get variables to increase sql injection security
	#			- Added code that actually handles the PDF cancel button
	#
	#####################################################################################

	#####################################################################
	# Cancel
	#####################################################################

	if (isset($_POST['submit']['button']) && $_POST['submit']['button'] == "Cancel") {
		$_SESSION['search'] = array();
		$_SESSION['submit'] = "";
		$_POST['submit'] = "";
	}

	// Handle IMAGE cancel button
	if (isset($_POST['image']['button']) && $_POST['image']['button'] == "Cancel") {
		unset($_GET['modify']);
		$_POST['image'] = "";
	}

	// Handle PDF cancel button
	if (isset($_POST['pdf']['button']) && $_POST['pdf']['button'] == "Cancel") {
		unset($_GET['modifypdf']);
		$_POST['pdf'] = "";
	}

	#####################################################################

	if (isset($_POST['submit']) && $_POST['submit']) { $_SESSION['submit'] = $_POST['submit']; }

	// Virtual Product Extension
	if (isset($_POST['item']) && $_POST['item']) { $_SESSION['item']   = $_POST['item']; }

	if (isset($_GET['edit']) === true) {
		$_GET['edit'] = (int) $_GET['edit'];
		$_SESSION['edit'] = $_GET['edit'];
	} else {
		// If there is no search going on and not editing, clear the submit session to empty the form
		if (isset($_POST['submit']['button']) === false || $_POST['submit']['button'] != "Search") {
			$_SESSION['submit'] = "";
		}
	}

	if (isset($_GET['delete']) === true) {
		$_SESSION['edit'] = (int) $_GET['delete'];
	}

	foreach ($layout as $fieldKey => $fieldData) {
		if ($fieldData['type'] == "B") {
			$button = $fieldKey;
		}
		if ($fieldData['type'] == "Index") {
			$index = $fieldKey;
		}
	}

	function display_layout($key) {
		global $layout, $error, $site, $static;

		$result = "";
		if (is_array($_SESSION['submit']) !== false) {
			if (isset($_SESSION['submit'][$key]) === false && isset($layout[$key]['default'])) {
				$_SESSION['submit'][$key] = $layout[$key]['default'];
			}
		}
		$_SESSION['submit'][$key] = (isset($_SESSION['submit'][$key]) === true) ? $_SESSION['submit'][$key] : "";
		switch ($layout[$key]['type']) {
			# INPUT
			case "I";
				if (isset($layout[$key]['required']) && $layout[$key]['required']) { $required = "<b>*</b>"; } else { $required = ""; }
				if (isset($layout[$key]['search']) && $layout[$key]['search'])   { $required = "<b>(s)</b>"; } else { $required = ""; }
				$result  = "<tr><td align='right'>";
				if (isset($error[$key])) {
					$result .= "<span style='color: #ff0000;'>";
				}
				$result .= $layout[$key]['heading'].":$required";
				if (isset($error[$key])) {
					$result .= "</span>";
				}
				$result .= "</td><td><input type='text' size='".$layout[$key]['size']."' name='submit[".$key."]' value='".$_SESSION['submit'][$key]."' /> ".((isset($layout[$key]['note'])) ? $layout[$key]['note'] : "")."</td></tr>";
				break;

			# TEXTAREA
			case "T";
				if (isset($layout[$key]['required']) && $layout[$key]['required']) { $required = "<b>*</b>"; } else { $required = ""; }
				if (isset($layout[$key]['search']) && $layout[$key]['search'])   { $required = "<b>(s)</b>"; } else { $required = ""; }
				$result  = "<tr><td align='right'>";
				if (isset($error[$key])) { $result .= "<font color='#ff0000'>"; }
				$result .= $layout[$key]['heading'].":$required";
				if (isset($error[$key])) { $result .= "</font>"; }
				$layout[$key]['rows'] = (isset($layout[$key]['rows'])) ? $layout[$key]['rows'] : "8";
				$result .= "</td><td><textarea cols='".$layout[$key]['size']."' rows='".$layout[$key]['rows']."' name='submit[".$key."]'>".$_SESSION['submit'][$key]."</textarea> ".((isset($layout[$key]['note'])) ? $layout[$key]['note'] : "")."</td></tr>";
				break;

			# EDITOR
			case "E";
				if (isset($layout[$key]['required']) && $layout[$key]['required']) { $required = "<b>*</b>"; } else { $required = ""; }
				if (isset($layout[$key]['search']) && $layout[$key]['search'])   { $required = "<b>(s)</b>"; } else { $required = ""; }
				$result  = "<tr><td align='right'>";
				if (isset($error[$key])) { $result .= "<font color='#ff0000'>"; }
				$result .= $layout[$key]['heading'].":$required";
				if (isset($error[$key])) { $result .= "</font>"; }
				$result .= "</td><td>";
				echo $result;
				$result = '';

				#New CKEditor#
				$var = $_SESSION['submit'][$key];
				if (file_exists($site['url']['path'].'ckeditor/ckeditor.php')) {
					include($site['url']['path']."ckeditor/ckeditor.php");
					$sBasePath = $site['url']['full']."/ckeditor/";
					$oFCKeditor = new CKEditor();
					$oFCKeditor->editor("submit[$key]", "$var");
					$oFCKeditor->basePath   = $sBasePath;
					$oFCKeditor->Value      = $var;
				}

				$result = "</td></tr>";				
				break;

			# CHECK
			case "C";
				$checked = "";
				if (isset($_SESSION['submit'][$key]) && $_SESSION['submit'][$key] == "Y") { $checked = "checked"; }
				$result = "<tr><td align='right'>".$layout[$key]['heading'].":</td><td><input type='checkbox' name='submit[".$key."]' value='Y' ".$checked." /></td></tr>";
				break;

			# BUTTON
			case "B";
				$result = "<tr><td align='right'>&nbsp;</td><td><input type='submit' name='submit[".$key."]' value='".$layout[$key]['heading']."' /></td></tr>";
				break;

			# DATE
			case "d";
				$result = "<tr><td align='right'>".$layout[$key]['heading']."</td><td><input type='text' size='".$layout[$key]['size']."' name='submit[".$key."]' value='".$_SESSION['submit'][$key]."' /></td></tr>";
				break;

			# DROPDOWN
			case "D";
				if (isset($layout[$key]['search']) && $layout[$key]['search'])   { $required = "<b>(s)</b>"; } else { $required = ""; }
				$result  = "<tr><td align='right'>".$layout[$key]['heading'].":".$required."</td><td>";
				$result .= "<select name='submit[".$key."]'>";
				foreach ($layout[$key]['item'] as $item) {
					$selected = "";
					if ($_SESSION['submit'][$key] == $item) { $selected = "selected"; }
					$result .= "<option $selected>".$item."</option>";
				}
				$result .= "</select>";
				$result .= " ".((isset($layout[$key]['note'])) ? $layout[$key]['note'] : "")."</td></tr>";
				break;
		}

		return $result;
	}

	#####################################################################
	# EDIT
	#####################################################################

	if (isset($_GET['edit']) === true) {
		$sql = "SELECT * FROM ".$tableId." WHERE `".$index."` = '".$_GET['edit']."'";
		$result = sql_exec($sql);
		$line = $result->fetch_assoc();

		$_SESSION['submit'] = $line;
	}

	#####################################################################
	# Image Upload & Delete & Update
	#####################################################################

	if (isset($_POST['image']['button']) && ($_POST['image']['button'] == "Upload" || $_POST['image']['button'] == "Update")) {
		if (!$_POST['input']['productImageTitle']) {
			$_POST['input']['productImageTitle'] = $_SESSION['submit']['productTitle'];
		}
		$_POST['input']['productImageTitle'] = str_replace(array("&", "-", "`"), array("+", " ", "'"), $_POST['input']['productImageTitle']);
		$_POST['input']['productImageTitle'] = preg_replace("/[^a-z0-9\\ \\-\\+]/i", "", $_POST['input']['productImageTitle']);
		if (!$_POST['input']['productImageDescription']) { $_POST['input']['productImageDescription'] = $_POST['input']['productImageTitle']; }
	}

	if (isset($_POST['image']['button']) && $_POST['image']['button'] == "Upload" && $_FILES['input']['error'] == 0) {
		$ext = strtolower(pathinfo($_FILES['input']['name'], PATHINFO_EXTENSION));
		$buffer_name = 	$_POST['EDIT_IDENT']."_".$_POST['input']['productImageTitle'].".".$ext;
		file_upload($_FILES['input'], $site['url']['path'].$site['path']['product']['buffer'].$buffer_name);

		$insert['product_image'] = $_POST['input'];

		$sql  = "INSERT INTO ".$site['database']['product-image']." ( `productImageId`, `productImageProduct`";
		foreach ($table['product_image'] as $data) {
			$sql .= ", `".$data."`";
		}
		$sql .= ") VALUES ( NULL,'".$_POST['EDIT_IDENT']."'";
		foreach ($table['product_image'] as $data) {
			$sql .= ", '".$insert['product_image'][$data]."'";
		}
		$sql .= ")";
		$result = sql_exec($sql);

		$insert['product_image']['productImageTitle'] = str_replace(" ", "_", $_POST['input']['productImageTitle']);
		$new_name = $insert['product_image']['productImageTitle']."-".$db->insert_id."-".$_POST['EDIT_IDENT'].".".$ext;

		$sql = "UPDATE ".$site['database']['product-image']." SET `productImageFile` = '".$new_name."' WHERE `productImageId` = '".$db->insert_id."';";
		$result = sql_exec($sql);

		$info = image_convert($site['url']['path'].$site['path']['product']['buffer'].$buffer_name, $site['url']['path'].$site['path']['product']['thumb'].$new_name, $page['product_image']['thumb']['x'], $page['product_image']['thumb']['y'], $page['product_image']['quality']);
		$info = image_convert($site['url']['path'].$site['path']['product']['buffer'].$buffer_name, $site['url']['path'].$site['path']['product']['full'].$new_name,  $page['product_image']['full']['x'],  $page['product_image']['full']['y'],  $page['product_image']['quality']);

		unlink($site['url']['path'].$site['path']['product']['buffer'].$buffer_name);
	}

	// UPDATE IMAGE ARRAY TO REFLECT ANY NEWLY UPLOADED IMAGES
	$image_array = array();
	if (isset($_GET['edit'])) {
		$sql  = "SELECT * FROM ".$site['database']['product-image']." WHERE `productImageProduct` = '".$_GET['edit']."' ORDER BY `productImageOrder`, `productImageTitle`";
		$result = sql_exec($sql);
		while ($line = $result->fetch_assoc()) {
			$image_array[$line['productImageId']] = $line;
		}
	}

	if (isset($_POST['image']['button']) && $_POST['image']['button'] == "Update") {
		$old_name = $_POST['input']['productImageFile'];
		$ext = strtolower(pathinfo($old_name, PATHINFO_EXTENSION));
		$insert['product_image']['productImageTitle'] = str_replace(" ", "_", $_POST['input']['productImageTitle']);
		$new_name = $insert['product_image']['productImageTitle']."-".$image_array[$_GET['modify']]['productImageId']."-".$image_array[$_GET['modify']]['productImageProduct'].".".$ext;

		if ($old_name != $new_name) {
			rename($site['url']['path'].$site['path']['product']['thumb'].$old_name, $site['url']['path'].$site['path']['product']['thumb'].$new_name);
			rename($site['url']['path'].$site['path']['product']['full'].$old_name,  $site['url']['path'].$site['path']['product']['full'].$new_name);
		}
		$sql = "UPDATE ".$site['database']['product-image']." SET ";
		$sql .= "`productImageOrder`       = '".$_POST['input']['productImageOrder']."', ";
		$sql .= "`productImageTitle`       = '".$_POST['input']['productImageTitle']."', ";
		$sql .= "`productImageDescription` = '".$_POST['input']['productImageDescription']."', "; 
		$sql .= "`productImageFile`        = '".$new_name."' "; 
		$sql .= "WHERE `productImageId`    = '".$_GET['modify']."'";
		$result = sql_exec($sql);
		$image_array = array();
		$sql  = "SELECT * FROM ".$site['database']['product-image']." WHERE `productImageProduct` = '".$_POST['EDIT_IDENT']."' ORDER BY `productImageOrder`, `productImageTitle`";
		$result = sql_exec($sql);
		while ($line = $result->fetch_assoc()) {
			$image_array[$line['productImageId']] = $line;
		}
		unset($_GET['modify']);
	}

	if (isset($_POST['image']['button']) && $_POST['image']['button'] == "Delete") {
		if (file_exists($site['url']['path'].$site['path']['product']['thumb'].$image_array[$_GET['modify']]['productImageFile'])) {
			unlink($site['url']['path'].$site['path']['product']['thumb'].$image_array[$_GET['modify']]['productImageFile']);
		}
		if ($site['url']['path'].$site['path']['product']['full'].$image_array[$_GET['modify']]['productImageFile']) {
			unlink($site['url']['path'].$site['path']['product']['full'].$image_array[$_GET['modify']]['productImageFile']);
		}
		$sql = "DELETE FROM ".$site['database']['product-image']." WHERE `productImageId` = '".$_GET['modify']."';";
		$result = sql_exec($sql);
		unset($image_array[$_GET['modify']]);
		unset($_GET['modify']);
	}

	#####################################################################
	# PDF Upload & Delete & Update
	#####################################################################

	if (isset($_POST['pdf']['button']) && ($_POST['pdf']['button'] == "Upload" || $_POST['pdf']['button'] == "Update")) {
		if (!$_POST['input']['productPdfDescription']) {
			$_POST['input']['productPdfDescription'] = $_SESSION['submit']['productTitle'];
		}
		$_POST['input']['productPdfDescription'] = str_replace(array("&", "-", "`"), array("and", " ", "'"), $_POST['input']['productPdfDescription']);
		$_POST['input']['productPdfDescription'] = preg_replace("/[^a-z0-9\\ \\-\\+]/i", "", $_POST['input']['productPdfDescription']);
	}

	if (isset($_POST['pdf']['button']) && $_POST['pdf']['button'] == "Upload" && $_FILES['input']['error'] == 0) {
		$ext = "pdf";
		$buffer_name = $_POST['EDIT_IDENT']."_".$_POST['input']['productPdfDescription'].".".$ext;
		file_upload($_FILES['input'], $site['url']['path'].$site['path']['product']['pdf'].$buffer_name);

		$insert['product_pdf'] = $_POST['input'];

		$sql  = "INSERT INTO ".$site['database']['product-pdf']." ( `productPdfId`, `productPdfProduct`";
		foreach ($table['product_pdf'] as $data) {
			$sql .= ", `".$data."`";
		}
		$sql .= ") VALUES ( NULL,'".$_POST['EDIT_IDENT']."'";
		foreach ($table['product_pdf'] as $data) {
			$sql .= ", '".$insert['product_pdf'][$data]."'";
		}
		$sql .= ")";
		$result = sql_exec($sql);

		$insert['product_pdf']['productPdfDescription'] = str_replace(" ", "_", $_POST['input']['productPdfDescription']);
		$new_name = $insert['product_pdf']['productPdfDescription']."-".$db->insert_id."-".$_POST['EDIT_IDENT'].".".$ext;

		$sql = "UPDATE ".$site['database']['product-pdf']." SET `productPdfFile` = '".$new_name."' WHERE `productPdfId` = '".$db->insert_id."';";
		$result = sql_exec($sql);

		rename($site['url']['path'].$site['path']['product']['pdf'].$buffer_name,  $site['url']['path'].$site['path']['product']['pdf'].$new_name);
	}

	$pdf_array = array();
	if (isset($_GET['edit']) && $site['database']['product-pdf']) {
		$sql  = "SELECT * FROM ".$site['database']['product-pdf']." WHERE `productPdfProduct` = '".$_GET['edit']."' ORDER BY `productPdfOrder`, `productPdfDescription`";
		$result = sql_exec($sql);
		while ($line = $result->fetch_assoc()) {
			$pdf_array[$line['productPdfId']] = $line;
		}
	}

	if (isset($_POST['pdf']['button']) && $_POST['pdf']['button'] == "Update") {
		$old_name = $pdf_array[$_GET['modifypdf']]['productPdfFile'];
		$ext = "pdf";
		$insert['product_pdf']['productPdfDescription'] = str_replace(" ", "_", $_POST['input']['productPdfDescription']);
		$new_name = $insert['product_pdf']['productPdfDescription']."-".$pdf_array[$_GET['modifypdf']]['productPdfId']."-".$pdf_array[$_GET['modifypdf']]['productPdfProduct'].".".$ext;

		if ($old_name != $new_name) {
			rename($site['url']['path'].$site['path']['product']['pdf'].$old_name, $site['url']['path'].$site['path']['product']['pdf'].$new_name);
		}
		$sql = "UPDATE ".$site['database']['product-pdf']." SET ";
		$sql .= "`productPdfOrder`       = '".$_POST['input']['productPdfOrder']."', ";
		$sql .= "`productPdfDescription` = '".$_POST['input']['productPdfDescription']."', "; 
		$sql .= "`productPdfFile`        = '".$new_name."' "; 
		$sql .= "WHERE `productPdfId`    = '".$_GET['modifypdf']."'";
		$result = sql_exec($sql);

		if ($_FILES['input']['error'] == '0') {
			file_upload($_FILES['input'], $site['url']['path'].$site['path']['product']['pdf'].$new_name);
		}

		$image_array = array();
		$sql  = "SELECT * FROM ".$site['database']['product-pdf']." WHERE `productPdfProduct` = '".$_POST['EDIT_IDENT']."' ORDER BY `productPdfOrder`, `productPdfDescription`";
		$result = sql_exec($sql);
		while ($line = $result->fetch_assoc()) {
			$pdf_array[$line['productPdfId']] = $line;
		}
		unset($_GET['modifypdf']);
	}

	if (isset($_POST['pdf']['button']) && $_POST['pdf']['button'] == "Delete" ) {
		if (file_exists($site['url']['path'].$site['path']['product']['pdf'].$pdf_array[$_GET['modifypdf']]['productPdfFile'])) {
			unlink($site['url']['path'].$site['path']['product']['pdf'].$pdf_array[$_GET['modifypdf']]['productPdfFile']);
		}
		$sql = "DELETE FROM ".$site['database']['product-pdf']." WHERE `productPdfId` = '".$_GET['modifypdf']."';";
		$result = sql_exec($sql);
		unset($pdf_array[$_GET['modifypdf']]);
		unset($_GET['modifypdf']);
	}

	#####################################################################
	# Virtual Product Item Update & Delete
	#####################################################################

	if (isset($site['database']['product-item']) && $site['database']['product-item'] && is_array($input['product_item'])) {
		include 'admin_product-item-sql.php';
	}

	#####################################################################
	# INSERT
	#####################################################################

	if (isset($_POST['submit']['button']) === true && $_POST['submit']['button'] == "Insert") {

		$error = "";

		foreach ($layout as $fieldKey => $fieldData) {
			if ($fieldData['required'] == 'Y') {
				if (!$_POST['submit'][$fieldKey]) { $error[$fieldKey] = "Y"; }
			}
		}
		if (!$error) {
			$sql   = "INSERT INTO ".$site['database']['product']." (";
			$counter = 1;
			foreach ($layout as $fieldKey => $fieldData) {
				if ($fieldData['type'] != "B" && $fieldData['type'] != "Index") {
					if ($counter > 1) { $sql .= ", ";}
					$counter ++;
					$sql .= "`".$fieldKey."`";
				}
			}
			$sql .= ") VALUES (";
			$counter = 1;
			foreach ($layout as $fieldKey => $fieldData) {
				if ($fieldData['type'] != "B" && $fieldData['type'] != "Index") {
					if ($fieldKey != 'productDescription') { $_POST['submit'][$fieldKey] = str_replace(array("'", '"'), array("`", "&quot;"), $_POST['submit'][$fieldKey]); }
					if ($counter > 1) { $sql .= ", "; }
					$counter ++;
					if ($_POST['submit'][$fieldKey]) {
						$sql .= "'".$_POST['submit'][$fieldKey]."'";
					} else {
						if ($fieldData['type'] == "d" && $fieldData['insert'] == "NOW") {
							$sql .= "now()";
						} else {
							$sql .= "'".$fieldData['insert']."'";
						}
					}
				}
			}
			$sql .= ")";
			$result = sql_exec($sql);
			$insert_id = $db->insert_id;
		}

		## MULTI IMAGE SECTION ##
		// Prepare file name
		$image_description = $_POST['submit']['productTitle'];
		$image_description = str_replace("&", "and", $image_description);
		$image_description = str_replace("-", "_", $image_description);
		$image_description = preg_replace("/[^a-z0-9\\ \\-\\+]/i", "", $image_description);

		$c = 0;
		if (is_array($_FILES['image']['error'])) {
			foreach ($_FILES['image']['error'] as $k => $e) {
				if ($e == 0 && !$error) {
					$c++;
					$ext = strtolower(pathinfo($_FILES['image']['name'][$k], PATHINFO_EXTENSION));
					$buffer_name = 	$insert_id."_".$k.".".$ext;
					file_upload($_FILES['image'], $site['url']['path'].$site['path']['product']['buffer'].$buffer_name, $k);

					$sql  = "INSERT INTO ".$site['database']['product-image']." (`productImageOrder`, `productImageProduct`, `productImageTitle`, `productImageDescription`, `productImageFile`";
					$sql .= ") VALUES ( '".$c."', '".$insert_id."', '".$image_description."', '".$image_description."', '')";
					$result = sql_exec($sql);

					$image_file = str_replace(" ", "_", $image_description);
					$new_name = $image_file."-".$db->insert_id."-".$insert_id.".".$ext;

					$sql = "UPDATE ".$site['database']['product-image']." SET `productImageFile` = '".$new_name."' WHERE `productImageId` = '".$db->insert_id."';";
					$result = sql_exec($sql);

					$info = image_convert($site['url']['path'].$site['path']['product']['buffer'].$buffer_name, $site['url']['path'].$site['path']['product']['thumb'].$new_name, $page['product_image']['thumb']['x'], $page['product_image']['thumb']['y'], $page['product_image']['quality']);
					$info = image_convert($site['url']['path'].$site['path']['product']['buffer'].$buffer_name, $site['url']['path'].$site['path']['product']['full'].$new_name,  $page['product_image']['full']['x'],  $page['product_image']['full']['y'],  $page['product_image']['quality']);

					if (file_exists($site['url']['path'].$site['path']['product']['buffer'].$buffer_name)) {
						unlink($site['url']['path'].$site['path']['product']['buffer'].$buffer_name);
					}
				}
			}
		}
		$_SESSION['submit'] = "";
	}

	#####################################################################
	# UPDATE
	#####################################################################

	if (isset($_POST['submit']['button']) === true && $_POST['submit']['button'] == "Update") {
		$sql   = "UPDATE ".$tableId." SET ";
		$counter = 1;
		foreach ($layout as $fieldKey => $fieldData) {
			if ($fieldData['type'] != "B" && $fieldData['type'] != "Index" && $fieldData['update'] != "NULL") {
				if ($fieldKey != 'productDescription') { $_POST['submit'][$fieldKey]    = str_replace("'", "`", $_POST['submit'][$fieldKey]); }
				if ($fieldKey != 'productDescription') { $_POST['submit'][$fieldKey]    = str_replace('"', "&quot;", $_POST['submit'][$fieldKey]); }

				if ($counter > 1) { $sql .= ", ";}
				$counter ++;
				$sql .= "`".$fieldKey."` = ";
				if ($_POST['submit'][$fieldKey]) {
					$sql .= "'".$_POST['submit'][$fieldKey]."'";
				} else {
					if ($fieldData['type'] == "d" && $fieldData['update'] == "NOW") {
						$sql .= "now()";
					} else {
						$sql .= "'".$fieldData['update']."'";
					}
				}
			}
		}
		$sql .= " WHERE `".$index."` = '".$_POST['EDIT_IDENT']."'";
		$result = sql_exec($sql);
		$_SESSION['submit'] = "";
	}

	#####################################################################
	# DELETE
	#####################################################################

	if (isset($_GET['delete'])) {
		$sql = "UPDATE ".$tableId." SET `productFlag` = 'D' WHERE `".$index."` = '".$_GET['delete']."'";
		$result = sql_exec($sql);
		$sql = "DELETE FROM ".$site['database']['product-image']." WHERE `productImageProduct` = '".$_GET['delete']."'";
		$result = sql_exec($sql);

		$sql   = "UPDATE ".$tableId." SET ";
		$counter = 1;
		foreach ($layout as $fieldKey => $fieldData) {
			if ($fieldData['type'] != "B" && $fieldData['type'] != "Index" && $fieldData['update'] != "NULL" && $fieldData['update']) {
				if ($counter > 1) { $sql .= ", "; }
				$counter ++;
				$sql .= "`".$fieldKey."` = ";
				if ($fieldData['type'] == "d" && $fieldData['update'] == "NOW") {
					$sql .= "now()";
				} else {
					$sql .= "'".$fieldData['update']."'";
				}
			}
		}
		$sql .= " WHERE `".$index."` = '".$_GET['delete']."'";
		$result = sql_exec($sql);

		$_SESSION['submit'] = "";
		if ($image_array) {
			foreach ($image_array as $key => $data) {
				if (file_exists($site['url']['path'].$site['path']['product']['thumb'].$data['productImageFile'])) {
					unlink($site['url']['path'].$site['path']['product']['thumb'].$data['productImageFile']);
				}
				if (file_exists($site['url']['path'].$site['path']['product']['full'].$data['productImageFile'])) {
					unlink($site['url']['path'].$site['path']['product']['full'].$data['productImageFile']);
				}
			}
		}
	}

	#####################################################################
	# UNASSIGN
	#####################################################################

	if (isset($_GET['unassign'])) {
		$sql = "DELETE FROM ".$site['database']['product-link']." WHERE `product-linkProduct` = '".$_GET['unassign']."';";
		$result = sql_exec($sql);

		$sql = "UPDATE ".$site['database']['product']." SET `productFlag` = 'N' WHERE `productId` = '".$_GET['unassign']."';";
		$result = sql_exec($sql);

		$_SESSION['submit'] = "";
	}

	if (isset($_GET['unassignlink'])) {
		$sql = "DELETE FROM ".$site['database']['product-link']." WHERE `product-linkProduct` = '".$_GET['edit']."' AND `product-linkId` = '".$_GET['unassignlink']."'";
		$result = sql_exec($sql);
		$where = "`product-linkProduct` = '".$_GET['edit']."'";
		$database = $site['database']['product-link'];
		$count = sql_count($database, $where);
		if (!$count) {
			$sql = "UPDATE ".$site['database']['product']." SET `productFlag` = 'N' WHERE `productId` = '".$_GET['edit']."';";
			$result = sql_exec($sql);
		}
	}

	#####################################################################
	# INSERT PRODUCT EXTRA FIELDS
	#####################################################################

	if (isset($_POST['buttonExtra']) && $_POST['buttonExtra'] == "Update") {
		$sql = "DELETE FROM ".$site['database']['product-extra']." WHERE `productExtraProduct` = '".$_POST['EDIT_IDENT']."'"; 
		$result = sql_exec($sql);

		foreach ($table['productExtra']['category'] as $categoryId => $categoryHeading) {
			$counter = 0;
			foreach ($_POST['extra'][$categoryId]['info'] as $key => $data) {
				$counter += 2;
				$sql  = "INSERT INTO ".$site['database']['product-extra']." (";
				$sql .= "`productExtraProduct`, ";
				$sql .= "`productExtraCategory`, ";
				$sql .= "`productExtraOrder`, ";
				$sql .= "`productExtraHeading`, ";
				$sql .= "`productExtraInfo`";
				$sql .= ") VALUES (";
				$sql .= "'".$_POST['EDIT_IDENT']."', ";
				$sql .= "'".$categoryId."', ";
				$sql .= "'".$counter."', ";
				$sql .= "'".$_POST['extra'][$categoryId]['heading'][$key]."', ";
				$data = htmlentities($data, ENT_COMPAT | ENT_QUOTES | ENT_HTML401, "UTF-8", false);
				$data = str_replace(array("&lt;", "&gt;", "&quot;"), array("<", ">" , '"'), $data);
				$sql .= "'".$data."'";
				$sql .= ")";
				if ($data) {
					$result = sql_exec($sql);
				}
			}
		}
	}

	#####################################################################
	# MOVE PRODUCT EXTRA FIELD
	#####################################################################

	if (isset($_GET['category']) === true && isset($_GET['item']) === true) {

		if ($_GET['move'] == "up") {
			$newPosition = $_GET['item']-3;
		}

		if ($_GET['move'] == "down") {
			$newPosition = $_GET['item']+3;
		}

		$sql  = "UPDATE ".$site['database']['product-extra']." ";
		$sql .= "SET `productExtraOrder` = '$newPosition' WHERE `productExtraProduct` = '".$_GET['edit']."' AND `productExtraCategory` = '".$_GET['category']."' AND `productExtraOrder` = '".$_GET['item']."'";
		$result = sql_exec($sql);

		$sql = "SELECT * FROM ".$site['database']['product-extra']." WHERE `productExtraProduct` = '".$_GET['edit']."' AND `productExtraCategory` = '".$_GET['category']."' ORDER BY `productExtraOrder`";
		$result = sql_exec($sql);
		while ($line = $result->fetch_assoc()) {
			$buffer[] = $line;
		}

		$sql = "DELETE FROM ".$site['database']['product-extra']." WHERE `productExtraProduct` = '".$_GET['edit']."' AND `productExtraCategory` = '".$_GET['category']."'";
		$result = sql_exec($sql);

		$counter = 0;
		foreach ($buffer as $data) {
			$counter += 2;
			$sql  = "INSERT INTO ".$site['database']['product-extra']." (";
			$sql .= "`productExtraProduct`, ";
			$sql .= "`productExtraCategory`, ";
			$sql .= "`productExtraOrder`, ";
			$sql .= "`productExtraHeading`, ";
			$sql .= "`productExtraInfo`";
			$sql .= ") VALUES (";
			$sql .= "'".$data['productExtraProduct']."', ";
			$sql .= "'".$data['productExtraCategory']."', ";
			$sql .= "'".$counter."', ";
			$sql .= "'".$data['productExtraHeading']."', ";
			$sql .= "'".$data['productExtraInfo']."'";
			$sql .= ")";
			$result = sql_exec($sql);
		}
	}

	#####################################################################
	# DISPLAY FORM
	#####################################################################

	echo "<div id='adminpage'>";
	if (isset($message) && $message != "") {
		echo "<p align='left'>".$message."</p>";
	}
	echo "<form enctype='multipart/form-data' action='".$site['url']['actual']."#edit_product' method='post'>";
	echo "<input='hidden' name='MAX_FILE_SIZE' value='".$page['product_image']['max_size']."' />";
	// Add hidden input for the ID of the product
	if (isset($_GET['edit'])) {
		echo "<input type='hidden' name='EDIT_IDENT' value='".$_GET['edit']."' />";
	}
	echo "<table id='edit_product'><tr><td>";
	echo "<fieldset>";
	echo "<table>";
		foreach ($layout as $key => $data) {
			if (isset($layout[$key]['display']) && $layout[$key]['display'] == 'Y') {
				echo display_layout($key);
			}
		}
		## Multi Image If ##
		if (isset($_GET['edit']) === false) {
			if ($page['product_image']['qty'] > 10) {
				$page['product_image']['qty'] = 10;
			}
			for ($i = 1; $i <= $page['product_image']['qty']; $i++) {
				echo "<tr>";
				echo "<td align='right'>Image $i:</td>";
				echo "<td><input name='image[$i]' type='file' size='".$input['product_image']['productImageFile']['size']."' /></td>";
				echo "</tr>";
			}
		}
		echo "<tr><td align='right'>&nbsp;</td>";
		echo "<td>";
		if (isset($_GET['edit']) === false) {
			echo "<input type='submit' name='submit[button]' value='Search' id='search_button' />";
			echo "<input type='submit' name='submit[button]' value='Insert' />";
		} else {
			echo "<input type='submit' name='submit[button]' value='Update' />";
		}
		echo "<input type='submit' name='submit[button]' value='Cancel' />";
		echo "</td></tr>";
	echo "</table>";
	echo "</fieldset>";
	if (isset($error) && $error != "") {
		echo "<span style='color: crimson;'><b>*All required fields need to be entered.</b></span>";
	}
	echo "</td></tr></table>";
	echo "</form>";

	// SHOW PAGES ASSIGNED TO
	if (isset($_GET['edit']) === true) {
		echo "<hr id='edit_unassign' />";
		echo "<h2>Assigned to</h2>";

		$sql  = "SELECT * FROM ".$site['database']['product-link']." WHERE `product-linkProduct` = '".$_GET['edit']."' ORDER BY `product-linkId`, `product-linkPage`";
		$result = sql_exec($sql);
		$countrecords = $result->num_rows;
		if ($result->num_rows > 0) {
			while ($line = $result->fetch_assoc()) {
				echo "<a href='".$site['url']['actual']."?edit=".$_GET['edit']."&unassignlink=".$line['product-linkId']."#edit_unassign'><img src='".$site['url']['full']."images/b_delete.png' border='0' /></a>";
				echo "<span class='producttree'> ".url_treedisplay($line['product-linkPage'])."</span><br />";
			}
		} else {
			echo "<p>This product is not assigned to any page.</p>";
		}
	}

	if ((isset($_POST['submit']['button']) && $_POST['submit']['button'] == "Insert") && !$error){
		echo "<div><h2 style='text-align: center;'>Record Inserted</h2></div>";
		$_SESSION['submit'] = "";
	}

	#####################################################################
	# Virtual Product Item Editor
	#####################################################################

	if (isset($site['database']['product-item']) && $site['database']['product-item'] && is_array($input['product_item'])) {
		include 'admin_product-item-editor.php';
	}

	#####################################################################
	# PRODUCT EXTRA FIELDS (Specs etc.)
	#####################################################################

	if (isset($_GET['edit']) && isset($site['database']['product-extra'])) {

		$sql = "SELECT * FROM ".$site['database']['product-extra']." WHERE `productExtraProduct` = '".$_GET['edit']."' LIMIT 1";
		$result = sql_exec($sql);

		echo "<hr id='edit_productExtra' />";
		echo "<h2>Extra Details</h2>";
		echo "<form action='".$site['url']['actual']."?edit=".$_GET['edit']."#edit_productExtra' method='post' name='productExtra'>";
			echo "<input type='hidden' name='EDIT_IDENT' value='".$_GET['edit']."' />";
			echo "<table>";
			if ($result->num_rows > 0) {
				foreach ($table['productExtra']['category'] as $categoryId => $categoryHeading) {
					echo "<tr>";
						echo "<th colspan='4'>".$categoryHeading."</th>";
					echo "</tr>";
					$buffer = array();
					$sql = "SELECT * FROM ".$site['database']['product-extra']." WHERE `productExtraProduct` = '".$_GET['edit']."' AND `productExtraCategory` = '".$categoryId."' ORDER BY `productExtraOrder`";
					$result = sql_exec($sql);
					while ($line = $result->fetch_assoc()) {
						$buffer[] = $line;
					}
					foreach ($buffer as $key => $line) {
						echo "<tr>";
						// If full width array exists AND the categoryId exists in that array... show full width field, else show heading and field combo.
						if (isset($table['productExtra']['category_full']) && in_array($categoryId, $table['productExtra']['category_full'])) {
							echo "<td colspan='2'><input type='text' size='85' name='extra[$categoryId][info][]' value='".$line['productExtraInfo']."' /></td>";
						} else {
							echo "<th><input type='text' size='20' name='extra[$categoryId][heading][]'  value='".$line['productExtraHeading']."' /></th><td><input type='text' size='60' name='extra[$categoryId][info][]' value='".$line['productExtraInfo']."' /></td>";
						}
						// Up Arrow
						if ($line['productExtraOrder'] > 2) {
							echo "<td><a href='".$site['url']['actual']."?edit=".$_GET['edit']."&category=".$categoryId."&item=".$line['productExtraOrder']."&move=up#productExtra' style='font-family: \"Source Sans Pro\", Helvetica, Arial, sans-serif; text-decoration: none; font-weight: bold; font-size: 1.5em;'>&#8593;</a></td>";
						} else {
							echo "<td></td>";
						}
						// Down Arrow
						if ($buffer[$key+1]['productExtraOrder'] == $line['productExtraOrder']+2)
						{
							echo "<td><a href='".$site['url']['actual']."?edit=".$_GET['edit']."&category=".$categoryId."&item=".$line['productExtraOrder']."&move=down#productExtra' style='font-family: \"Source Sans Pro\", Helvetica, Arial, sans-serif; text-decoration: none; font-weight: bold; font-size: 1.5em;'>&#8595;</a></td>";
						} else {
							echo "<td></td>";
						}
					}
					if (isset($table['productExtra']['category_full']) && in_array($categoryId, $table['productExtra']['category_full'])) {
						echo "<tr><td colspan='2'><input type='text' size='85' name='extra[$categoryId][info][]' /></td>";
					} else {
						echo "<tr><th><input type='text' size='20' name='extra[$categoryId][heading][]' /></th><td><input type='text' size='60' name='extra[$categoryId][info][]' /></td>";
					}
					echo "</tr>";
				}
			} else {
				foreach ($table['productExtra']['category'] as $categoryId => $categoryHeading) {
					echo "<tr>";
						echo "<th colspan='4'>".$categoryHeading."</th>";
					echo "</tr>";
					foreach ($table['productExtra']['headings'][$categoryId] as $category) {
						if (isset($table['productExtra']['category_full']) && in_array($categoryId, $table['productExtra']['category_full'])) {
							echo "<tr>";
								echo "<td colspan='2'><input type='text' size='85' name='extra[$categoryId][info][]' />";
							echo "</td>";
						} else {
							echo "<tr>";
								echo "<th><input type='text' size='20' value='".$category."' name='extra[$categoryId][heading][]' /></th>";
								echo "<td><input type='text' size='60' name='extra[$categoryId][info][]' /></td>";
							echo "</tr>";
						}
					}
				}
			}
			echo "<tr>";
				echo "<th colspan='4'>";
					echo "<input type='submit' name='buttonExtra' value='Update' />";
				echo "</th>";
			echo "</tr>";
		echo "</table>";
		echo "</form>";
	}

	#####################################################################
	# ADD IMAGE
	#####################################################################

	if (isset($_GET['edit'])) {
 		$modifyimage = "default.jpg";
		if (isset($_GET['modify'])) {
			$modify = $image_array[$_GET['modify']];
			$modifyimage = $modify['productImageFile'];
		}
		echo "<hr id='edit_photo' />";
		echo "<h2>Photos</h2>";
		echo "<form enctype='multipart/form-data' action='".$site['url']['actual']."?edit=".$_GET['edit'];
		if (isset($_GET['modify'])) {
			echo "&modify=".$_GET['modify'];
		} 
		echo "#edit_photo";
		echo "' method='post' />";
		echo "<input type='hidden' name='MAX_FILE_SIZE' value=".$page['product_image']['max_size']."' />";
		echo "<input type='hidden' name='EDIT_IDENT' value='".$_GET['edit']."' />";
		echo "<table>";
		echo "<tr>";
			echo "<th rowspan='5' align='center' valign='middle' height='".$page['product_image']['thumb']['y']."' width='".$page['product_image']['thumb']['x']."'><img src='".$site['url']['full'].$site['path']['product']['thumb'].$modifyimage."' /></th>";
			echo "<td>".$input['product_image']['productImageOrder']['heading'].":</td>";
			echo "<td><input name='input[productImageOrder]' type='text' size='".$input['product_image']['productImageOrder']['size']."' value='".((isset($modify['productImageOrder'])) ? $modify['productImageOrder'] : "")."' /></td>";
		echo "</tr>";
		if (isset($input['product_image']['productImageTitle'])) {
			echo "<tr>";
				echo "<td>".$input['product_image']['productImageTitle']['heading'].":</td>";
				echo "<td><input name='input[productImageTitle]' type='text' size='".$input['product_image']['productImageTitle']['size']."' value='".((isset($modify['productImageTitle'])) ? $modify['productImageTitle'] : "")."' /></td>";
			echo "</tr>";
		}
		echo "<tr>";
			echo "<td>".$input['product_image']['productImageDescription']['heading'].":</td>";
			echo "<td><input name='input[productImageDescription]' type='text' size='".$input['product_image']['productImageDescription']['size']."' value='".((isset($modify['productImageDescription'])) ? $modify['productImageDescription'] : "")."' /></td>";
		echo "</tr>";
		if (!isset($_GET['modify'])) {
			echo "<tr>";
				echo "<td>".$input['product_image']['productImageFile']['heading'].":</td>";
				echo "<td><input name='input' type='file' size='".$input['product_image']['productImageFile']['size']."' /></td>";
			echo "</tr>";
		} else {
			echo "<input name='input[productImageFile]' type='hidden' value='".((isset($modify['productImageFile'])) ? $modify['productImageFile'] : "")."' />";
		}
		echo "<tr>";
			echo "<td align='center' colspan='2'>";
			if (isset($_GET['modify'])) {
				echo "<input type='submit' value='Update' name='image[button]' />";
				echo "<input type='submit' value='Delete' name='image[button]' />";
				echo "<input type='submit' value='Cancel' name='image[button]' />";
			} else {
				if (count($image_array) < $page['product_image']['qty']) {
					echo "<input type='submit' value='Upload' name='image[button]' />";
				} else {
					echo "<input type='submit' value='Upload' name='image[button]' disabled />";
				}
			}
			echo "</td>";
		echo "</tr>";
		echo "</table>";
		echo "</form>";
		$x = 0;
		if ($image_array) {
			echo "<table>";
			foreach ($image_array as $key => $data) {
				if ($x == 0) { echo "<tr>"; }
				echo "<td>";
				echo "<a href='".$site['url']['actual']."?edit=".$_GET['edit']."&modify=".$data['productImageId']."#edit_photo'>";
				echo "<img src='".$site['url']['full'].$site['path']['product']['thumb'].$data['productImageFile']."' alt='".$data['productImageDescription']."' title='".$data['productImageTitle']."' border='0' />";
				echo "</a>";
				echo "</td>";
				$x++;
				if ($x >= $page['page_gallery']['display']) {
					echo "</tr>";
					$x = 0;
				}
			}
			echo "</tr>";
			echo "</table>";
		}
	}

	#####################################################################
	# ADD PDF
	#####################################################################

	if (isset($_GET['edit']) && $input['product_pdf']) {
		if (isset($_GET['modifypdf'])) {
			$modify    = $pdf_array[$_GET['modifypdf']];
			$modifypdf = $modify['productPdfFile'];
		}
		echo "<hr id='edit_pdf' />";
		echo "<h2>PDF Documents</h2>";
		echo "<form enctype='multipart/form-data' action='".$site['url']['actual']."?edit=".$_GET['edit'];
		if (isset($_GET['modifypdf'])) {
			echo "&modifypdf=".$_GET['modifypdf'];
		}
		echo "#edit_pdf";
		echo "' method='post' />";
		echo "<input type='hidden' name='MAX_FILE_SIZE' value=".$page['product_pdf']['max_size']."' />";
		echo "<input type='hidden' name='EDIT_IDENT' value='".$_GET['edit']."' />";
		echo "	<table>";
		echo "	<tr>";
		echo "		<td>".$input['product_pdf']['productPdfOrder']['heading'].":</td>";
		echo "		<td><input name='input[productPdfOrder]' type='text' size='".$input['product_pdf']['productPdfOrder']['size']."' value='".((isset($modify['productPdfOrder'])) ? $modify['productPdfOrder'] : "")."' />";
		if (isset($modify['productPdfFile'])) {
			echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href='".$site['url']['full'].$site['path']['product']['pdf'].$modify['productPdfFile']."' target='_blank'>View Pdf</a>";
		}
		echo "</td>";
		echo "	</tr>";
		echo "	<tr>";
		echo "		<td>".$input['product_pdf']['productPdfDescription']['heading'].":</td>";
		echo "		<td><input name='input[productPdfDescription]' type='text' size='".$input['product_pdf']['productPdfDescription']['size']."' value='".((isset($modify['productPdfDescription'])) ? $modify['productPdfDescription'] : "")."' /></td>";
		echo "	</tr>";
		echo "	<tr>";
		echo "		<td>".$input['product_pdf']['productPdfFile']['heading'].":</td>";
		echo "		<td><input name='input' type='file' size='".$input['product_pdf']['productPdfFile']['size']."' /></td>";
		echo "	</tr>";
		echo "	<tr>";
		echo "		<td align='center' colspan='2'>";
		if (isset($_GET['modifypdf'])) {
			echo "<input type='submit' value='Update' name='pdf[button]' />";
			echo "<input type='submit' value='Delete' name='pdf[button]' />";
			echo "<input type='submit' value='Cancel' name='pdf[button]' />";
		} else {
			if (count($pdf_array) < $page['product_pdf']['qty']) {
				echo "<input type='submit' value='Upload' name='pdf[button]' />";
			} else {
				echo "<input type='submit' value='Upload' name='pdf[button]' disabled />";
			}
		}
		echo "		</td>";
		echo "	</tr>";
		echo "</table>";
		echo "</form>";
		if ($pdf_array) {
			echo "<table>";
			foreach ($pdf_array as $key => $data) {
				echo "<tr><td rowspan='2'>";
				echo "<a href='".$site['url']['actual']."?edit=".$_GET['edit']."&modifypdf=".$data['productPdfId']."#edit_pdf'>";
				echo "<img src='".$site['url']['full']."images/pdf.png' alt='".$data['productPdfDescription']."' title='".$data['productPdfDescription']."' border='0' />";
				echo "</a>";
				echo "</td>";
				echo "<td>Order: ".$data['productPdfOrder']."</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td>Description: ".$data['productPdfDescription']."</td>";
				echo "</tr>";
			}
			echo "</table>";
		}
	}

	#####################################################################
	# SEARCH
	#####################################################################

	if (isset($_SESSION['search']['page']) && $_SESSION['search']['page'] != $page['pageId']) {
		$_SESSION['search'] = array();
	}

	$sql  = "SELECT * FROM ".$tableId." WHERE ";

	if ((isset($_POST['submit']['button']) === true && $_POST['submit']['button'] == "Search") || (isset($_SESSION['search']['sql']) && isset($_GET['so']))) {
		## RUN SEARCH AS SPECIFIED ##
		$sql .= "`productFlag` <> 'D'";
		foreach ($layout as $fieldKey => $fieldData) {
			if (isset($fieldData['search']) && $fieldData['search'] == "Y") {
				$sql .= " AND `".$fieldKey."` LIKE '%".$_SESSION['submit'][$fieldKey]."%'";
			}
		}
	} else if (isset($_SESSION['search']['sql']) === false) {
		## SELECT ALL NOT ASSIGNED ##
		$sql .= "`productFlag` = 'N'";
	}

	$sql .= " ORDER BY ";

	## DEFAULT SORTING ##
	$_SESSION['search']['order'] = (isset($_SESSION['search']['order'])) ? $_SESSION['search']['order'] : "productTitle";
	$_SESSION['search']['direction'] = (isset($_SESSION['search']['direction'])) ? $_SESSION['search']['direction'] : "ASC";

	## CUSTOM SORTING ##
	if (isset($_GET['so'])) {
		$_SESSION['search']['order'] = $_GET['so'];
		$_SESSION['search']['direction'] = $_GET['sd'];
	}

	$sql .= "`".$_SESSION['search']['order']."` ".$_SESSION['search']['direction'];

	## ADD TO SESSION ##
	if ((isset($_POST['submit']['button']) === true && $_POST['submit']['button'] == "Search") || (isset($_SESSION['search']['sql']) && isset($_GET['so']))) {
		$_SESSION['search']['sql'] = $sql;
		$_SESSION['search']['page'] = $page['pageId'];
	}

	echo "<hr />";

	## SETTINGS FOR PRODUCT LIST DISPLAY ##
	if (isset($_SESSION['search']['sql'])) {
		$sql = $_SESSION['search']['sql'];
		echo "<h2>Product Search</h2>";
		$deleteCol = "Unassign";
	} else {
		echo "<h2>Unassigned Product</h2>";
		$deleteCol = "Delete";
	}

	#####################################################################
	# DISPLAY SEARCH AND UNASSIGNED LIST
	#####################################################################

	echo "<table class='productedit' cellpadding='10' cellspacing='1'>";
	$headings = "<tr>";
#		$headings .= "<th>ID</th>";
		$headings .= "<th>Edit</th>";
		$headings .= "<th>".$deleteCol."</th>";
		$headings .= "<th>Assigned</th>";
		$headings .= "<th>Image</th>";
		foreach ($layout as $fieldKey => $fieldData) {
			if (isset($fieldData['searchHeading'])) {
				if ($_SESSION['search']['order'] == $fieldKey && $_SESSION['search']['direction'] == "ASC") {
					$searchdir = "&sd=DESC";
					$searcharrow = "&nbsp;<span style='font-size: 9px;'>&#x25BC;";
				} else if ($_SESSION['search']['order'] == $fieldKey && $_SESSION['search']['direction'] == "DESC") {
					$searchdir = "&sd=ASC";
					$searcharrow = "&nbsp;<span style='font-size: 9px;'>&#x25B2;";
				} else {
					$searchdir = "&sd=ASC";
					$searcharrow = "";
				}
				$headings .= "<th><a href='".$site['url']['actual']."?so=".$fieldKey.$searchdir."'>".$fieldData['searchHeading']."</a>".$searcharrow."</th>";
			}
		}
	$headings .= "</tr>";
	echo $headings;
	$result = sql_exec($sql);
	$class = "productedit";
	$product_row_count = 0;
	while ($line = $result->fetch_assoc()) {
		if ($product_row_count >= 20) {
			echo $headings;
			$product_row_count = 0;
		}
		if ($class != "productedit") {
			$class = "productedit";
		} else {
			$class = "productedit2";
		}
		echo "<tr class='".$class."'>";
#		echo "<td>".$line['productId']."</td>";

		echo "<td><a href='".$site['url']['actual']."?edit=".$line[$index]."'><img src='".$site['url']['full']."images/b_edit.png' border='0' /></a></td>";
		echo "<td>";
			if ($deleteCol == "Delete") {
				echo "<a href='".$site['url']['actual']."?".strtolower($deleteCol)."=".$line[$index]."' onclick='return confirm(\"This will delete the product.\\nAre you sure?\");'><img src='".$site['url']['full']."images/b_delete.png' border='0' /></a>";
			} else {
				echo "<a href='".$site['url']['actual']."?".strtolower($deleteCol)."=".$line[$index]."'><img src='".$site['url']['full']."images/b_delete.png' border='0' /></a>";
			}
		echo "</td>";

		$where = "`product-linkProduct` = '".$line['productId']."'";
		$database = $site['database']['product-link'];
		$count = sql_count($database, $where);
		echo "<td>".$count." page".(($count != 1) ? "s" : "")."</td>";

		$where = "`productImageProduct` = '".$line['productId']."'";
		$database = $site['database']['product-image'];
		$count = sql_count($database, $where);
		echo "<td>".$count."</td>";

		foreach ($layout as $fieldKey => $fieldData) {
			if (isset($fieldData['searchHeading'])) {
				echo "<td>".$line[$fieldKey]."</td>";
			}
		}
		echo "</tr>";
		++$product_row_count;
	}
	echo "</table>";
	echo "</div>"; ## ADMINPAGE
?>