<?php
	# 09/06/2011 - Matt - Updated the "Continue to Checkout" button, unset $_POST['cart']
	# 26/08/2011 - William - Quantity automatically updates without button press
	# 29/11/2011 - William - Cleaned up code layout, removed excess whitespace, commented code
	# 01/07/2012 - David - Showed Inner and Carton Qty
	# 03/12/2012 - David - Added Business name to Order


	$currentEmail = $_SESSION['membership']['mmemberEmail'];

	echo content_converter($page['pageText']);

	if ($_POST['button'] == "Continue to Checkout" || $_POST['button-checkout_x']) {
		unset($_POST['cart']);
	}

	## SECURE CERTIFICATE ##
	if ($site['ssl'] == 'y' && $static['server']['name'] == 'mercury.ubcserver.com') {
		$site['url']['form'] = "https://".$site['url']['url']."/order_cart";
		$site['url']['cart'] = "http://".$site['url']['url']."/order_cart";
	} else {
		$site['url']['form'] = $site['url']['actual'];
	}

	## ADD TO CART ##
	if ($site['url']['decode']['add']) {
		if($currentEmail == NULL){
			$_SESSION['cart'][$site['url']['decode']['add']] = "1-".getmin($site['url']['decode']['add']);
		}else{
		$sql  = "SELECT id FROM ".$site['database']['shopping_cart']."WHERE `mmemberEmail` = '".$currentEmail."'";
		$resultid = sql_exec($sql);
	  $rid = $resultid->fetch_assoc();
		if($rid == NULL){
			$sql = "INSERT INTO ".$site['database']['shopping_cart']." (`mmemberEmail`) VALUES ('".$currentEmail. "')";
			sql_exec($sql);
		}
		$_SESSION['cart'][$site['url']['decode']['add']] = "1-".getmin($site['url']['decode']['add']);
		$json_session = json_encode($_SESSION['cart']);
		$sql ="UPDATE".$site['database']['shopping_cart']."SET cart = '".$json_session."' WHERE `mmemberEmail` = '".$currentEmail."'";
		sql_exec($sql);
	  }
		// $sql  = "SELECT id FROM ".$site['database']['shopping_cart']."WHERE `mmemberEmail` = '".$currentEmail."'";
		// $resultid = sql_exec($sql);
	  // $rid = $resultid->fetch_assoc();
		// if($rid == NULL){
		// 	$sql = "INSERT INTO ".$site['database']['shopping_cart']." (`mmemberEmail`) VALUES ('".$currentEmail. "')";
		// 	sql_exec($sql);
		// }
		//
		// $sql  = "SELECT cart FROM ".$site['database']['shopping_cart']."WHERE `mmemberEmail` = '".$currentEmail."' ";
		// $resultcart = sql_exec($sql);
		// $rcart = $resultcart->fetch_assoc();
		// $previoussession = json_decode($rcart['cart']);
		//
		// if($previoussession == NULL){
		// // echo "have nothing";
    // $_SESSION['cart'][$site['url']['decode']['add']] = "1-".getmin($site['url']['decode']['add']);
		// $json_session = json_encode($_SESSION['cart']);
		// // echo $json_session;
		// $sql ="UPDATE".$site['database']['shopping_cart']."SET cart = '".$json_session."' WHERE `mmemberEmail` = '".$currentEmail."'";
		// sql_exec($sql);
	  // }
		// else{
		// 	$_SESSION['cart'][$site['url']['decode']['add']] = "1-".getmin($site['url']['decode']['add']);
		// 	// echo "havesomthing";
		// 	// echo var_dump($_SESSION['cart']);
		// 	foreach($previoussession as $id => $qty){
		// 		if(array_key_exists($id, $_SESSION['cart'])){
		// 			// echo "dsadsa".$id;
		// 			Continue;
		// 		}else{
		// 			$_SESSION['cart'][$id] = $qty;
		// 			// echo "hereboy";
		// 		}
		// 	}
		// echo var_dump($_SESSION['cart']);
		// $json_session = json_encode($_SESSION['cart']);
		// $sql ="UPDATE".$site['database']['shopping_cart']."SET cart = '".$json_session."' WHERE `mmemberEmail` = '".$currentEmail."'";
		// sql_exec($sql);
		// }
	}

	## UPDATE QTY ##
	if ($_POST['cart'] && $_POST['size']) {
		foreach ($_POST['cart'] as $id => $qty) {
			$_SESSION['cart'][$id] = $qty;
			if ($_POST['size'][$id]) {
				$_SESSION['cart'][$id] .= "-".$_POST['size'][$id];
			} else {
				$min = getmin($id);
				if ($min) {
					$_SESSION['cart'][$id] .= "-".$min;
				}
			}
		}
		if($currentEmail == NULL){
		}else{
		$json_session = json_encode($_SESSION['cart']);
		$sql ="UPDATE".$site['database']['shopping_cart']."SET cart = '".$json_session."' WHERE `mmemberEmail` = '".$currentEmail."'";
		sql_exec($sql);
	  }
	}

	## REMOVE PRODUCT ##
	if ($_GET['remove']) {
		unset($_SESSION['cart'][$_GET['remove']]);
		if($currentEmail == NULL){
		}else{
		$json_session = json_encode($_SESSION['cart']);
		$sql ="UPDATE".$site['database']['shopping_cart']."SET cart = '".$json_session."' WHERE `mmemberEmail` = '".$currentEmail."'";
		sql_exec($sql);
	  }
	}


	if ($_POST['button'] == "Checkout" || $_POST['button-checkout_x']) {
		$_SESSION['cartMode'] = "Enter Details";
	}

	if ($_POST['button'] == "Edit Cart" || $_POST['button-editcart_x'] || $_GET['edit_cart'] == 'y') {
		$_SESSION['cartMode'] = "";
	}

	if ($_POST['button'] == "Order") {
		$_SESSION['cartMode'] = "Confirm Order";
	}

	if ($_POST['button'] == "Edit Details") {
		$_SESSION['cartMode'] = "Enter Details";
	}

	if ($_POST['button'] == "Place Order" || $_POST['button-placeorder_x']) {
		$_SESSION['cartMode'] = "Process Details";
	}

	if ($_POST['submit']) {
		foreach ($_POST['submit'] as $key => $data) {
			$_SESSION['submit'][$key] = $data;
		}
	}

	#########################
	# DISPLAY LAYOUT SWITCH #
	#########################

	function display_layout($key) {
		global $layout;
		global $site;
		global $error;
		$result = "";
		if (!$_SESSION['submit'][$key] && $layout[$key]['default']) {
			$_SESSION['submit'][$key] = $layout[$key]['default'];
		}
		$disabled = "";
		if ($layout[$key]['update']) {
			$disabled = " disabled";
		}
		switch ($layout[$key]['type']) {
			# HEADING
			case "H";
				$result  = "<tr><th align='center' colspan='2'>\n";
				$result .= $layout[$key]['heading'];
				break;

			# MONEY
			case "M";
				$result  = "<tr><th align='right'>\n";
				$result .= $layout[$key]['heading'].":";
				$result .= "</th><td>$ ".number_format($_SESSION['submit'][$key], $site['template']['price']['decimal']);
				if ($_SESSION['membership']['mmemberDiscount'] && $key == "orderPrice") {
					$result .= "<br />Includes ".$_SESSION['membership']['mmemberDiscount']."% discount";
				}
				$result .= "</td></tr>\n";
				break;

			# INPUT
			case "I";
				$required = "";
				if ($layout[$key]['search'])   { $required = "<b>(s)</b>"; } else { $required = ""; }
				if ($layout[$key]['required']) { $required .= "<b>*</b>"; } else { $required .= ""; }
				$result  = "<tr><th align='left'>";
				if ($error[$key]) { $result .= "<span class='error'>"; }
				$result .= $layout[$key]['heading'].":$required";
				if ($error[$key]) { $result .= "</span>"; }
				$result .= "</th><td><input type='text' size='".$layout[$key]['size']."' name='submit[".$key."]' value='".$_SESSION['submit'][$key]."'".$disabled."></td></tr>\n";
				break;

			# TEXT BOX
			case "T";
				$required = "";
				if ($layout[$key]['search'])   { $required = "<b>(s)</b>"; } else { $required = ""; }
				if ($layout[$key]['required']) { $required .= "<b>*</b>"; } else { $required .= ""; }
				$result  = "<tr><th align='left' valign='top'>";
				if ($error[$key]) { $result .= "<span class='error'>"; }
				$result .= $layout[$key]['heading'].":$required";
				if ($error[$key]) { $result .= "</span>"; }
				$result .= "</th><td><textarea cols='".$layout[$key]['cols']."' rows='".$layout[$key]['rows']."' name='submit[".$key."]'>".$_SESSION['submit'][$key]."</textarea></td></tr>\n";
				break;

			# CHECK
			case "C";
				if ($layout[$key]['search'])   { $required = "<b>(s)</b>"; } else { $required = ""; }
				$result  = "<tr><th align='left' valign='top'>".$layout[$key]['heading'].":$required</th><td>";
				foreach ($layout[$key]['item'] as $index=>$item) {
					$selected = "";
					if ($_SESSION['submit'][$key]) {
						if ($_SESSION['submit'][$key][$index] == $index) { $selected = "checked"; }
					}
					if ($_POST['get'][$key][$index] == $index) { $selected = "checked"; }
					$result .= "<input type='checkbox' name='submit[".$key."][".$index."]' value='".$index."' $selected>$item<br />";
				}
				$result .= "</td></tr>\n";
				break;

			# BUTTON
			case "B";
				$result = "<tr><th align='left'>&nbsp;</th><td><input type='submit' name='submit[".$key."]' value='".$layout[$key]['heading']."'></td></tr>\n";
				break;

			# DATE
			case "d";
				$date   = convert_date($_SESSION['submit'][$key]);
				$result = "<tr><th align='left'>".$layout[$key]['heading']."</th><td><input type='text' size='".$layout[$key]['size']."' name='submit[".$key."]' value='".$date['string']."'".$disabled."></td></tr>\n";
				break;

			# DROPDOWN
			case "D";
				if ($layout[$key]['search'])   { $required = "<b>(s)</b>"; } else { $required = ""; }
				if ($layout[$key]['required']) { $required .= "<b>*</b>"; }
				$result  = "<tr><th align='left'>";
				if ($error[$key]) { $result .= "<span class='error'>"; }
				$result .= $layout[$key]['heading'].":".$required;
				if ($error[$key]) { $result .= "</span>"; }
				$result .= "</th><td>";
				$result .= "<select name='submit[".$key."]'>";
				foreach ($layout[$key]['item'] as $index=>$item) {
					$selected = "";
					if ($_SESSION['submit'][$key] == $index) { $selected = "selected"; }
					$result .= "<option value='".$index."' $selected>$item</option>";
				}
				$result .= "</select>";
				$result .= "</td></tr>\n";
				break;

			# FREIGHT
			case "F";
				if ($layout[$key]['search'])   { $required = "<b>(s)</b>"; } else { $required = ""; }
				$result  = "<tr><th align='right'>".$layout[$key]['heading'].":$required</th><td>";
				$result .= "<select name='submit[".$key."]'>";
				foreach ($layout[$key]['item'] as $index=>$item) {
					$selected = "";
					if ($_SESSION['submit'][$key] == $index) { $selected = "selected"; }
					$result .= "<option value='".$index."' $selected>$item</option>";
				}
				$result .= "</select>";
				$result .= "</td></tr>\n";
				break;
		} # end switch

		return $result;
	} # END display_layout


	########################################
	#  Process Details - check for errors  #
	########################################

	if ($_SESSION['cartMode'] == "Process Details") {
		$error = "";
		foreach ($layout as $fieldKey => $fieldData) {
			if ($fieldData['required'] == "Y") {
				if (!$_POST['submit'][$fieldKey]) { $error[$fieldKey] = "Y"; }
			}
		}
		if ($error) {
			$_SESSION['cartMode'] = "Enter Details";
		}
	}


	################
	#  Start Cart  #
	################

	if (!$_SESSION['cartMode']) {
		if (!$_SESSION['cart'] && $productlink) {
			echo "<p>The ".$page['pageMenu']." is currently empty.</p>\n";
			echo "<p>Please take a look at our ".content_converter("[page|$productlink|Product Range]").".</p>\n";
			echo "<p>To add an item to the ".$page['pageMenu']." just click on the \"Add to Cart\" button.</p>\n";
		} else if (!$_SESSION['cart'] && !$productlink && $message['empty']) {
			echo $message['empty'];
		} else if (!$_SESSION['cart'] && !$productlink) {
			echo "<p>The ".$page['pageMenu']." is currently empty.</p>\n";
			echo "<p>Please take a look at our Product Range.</p>\n";
			echo "<p>To add an item to the ".$page['pageMenu']." just click on the \"Add to Cart\" button.</p>\n";
		} else {
			echo "<form action='".$site['url']['form']."' method='post'>\n";
			echo "<div id='cart'>\n";
			echo "<table id='cart-tb' cellspacing='0' cellpadding='3' border='0' width='533'>\n";
				echo "<tr>\n";
					echo "<th width='80'>".$display['cart']['title']['heading']."</th>\n";
					// echo "<th>&nbsp;</th>\n";
					if ($display['cart']['part']['heading']) {
						echo "<th align='center' width='90'></th>\n";
					}
					echo "<th width='100' align='center'>".$display['cart']['price']['heading']."</th>\n";
					echo "<th width='100' align='center'>".$display['cart']['quantity']['heading']."</th>\n";
					echo "<th width='100'>".$display['cart']['subPrice']['heading']."</th>\n";
				echo "</tr>\n";
				$ordercolspan = "3";
				$_SESSION['order']  = "<table>";
				$_SESSION['order'] .= "<tr>";

					if ($display['cart']['part']['heading']) {
						$_SESSION['order'] .= "<th align=\"left\">".$display['cart']['part']['heading']."</th>";
						$ordercolspan++;
					}
					if ($display['cart']['title']['heading']) {
						$_SESSION['order'] .= "<th align=\"left\">".$display['cart']['title']['heading']."</th>";
						$ordercolspan++;
					}
					$_SESSION['order'] .= "<th>".$display['cart']['price']['heading']."</th>";
					$_SESSION['order'] .= "<th>".$display['cart']['quantity']['heading']."</th>";
					$_SESSION['order'] .= "<th>".$display['cart']['subPrice']['heading']."</th>";
				$_SESSION['order'] .= "</tr>";
				$totalweight = "";

				foreach ($_SESSION['cart'] as $id => $qty) {
					$sql  = "SELECT * FROM ".$site['database']['product']." ";
					$sql .= "WHERE `productId` = '".$id."' ";
					$resultproduct = sql_exec($sql);
					$product = $resultproduct->fetch_assoc();

					$totalweight += $product['productWeight']*$_SESSION['cart'][$id];
					if ($product['productTitle']) {
						$product['productTitle'] = str_replace("'", "`", $product['productTitle']);
					}
					$image_array  = array();
					$sql  = "SELECT * FROM ".$site['database']['product-image']." WHERE `productImageProduct` = '".$id."' ORDER BY `productImageOrder`, `productImageTitle`";
					$resultimage = sql_exec($sql);
					while ($image = $resultimage->fetch_assoc()) {
						$image_array[] = $image;
					}
					echo "<tr>\n";
						echo "<td align='center'><img src='".$site['url']['full'].image_display($site['path']['product']['thumb'], $image_array['0']['productImageFile'])."' border='0' alt='".$product['productTitle']."' title='".$product['productTitle']."' /></td>\n";
						echo "<td align='center'><span class='cart-title'>".$product['productTitle']."</span>";
						if ($display['cart']['weight']['heading']) {
							echo "<br /><span class='cartweight'>(".$product['productWeight']." ".$display['cart']['weight']['unit']." each)</span>";
						}
						// echo "</td>\n";
						if ($display['cart']['part']['heading']) {
							echo "<br><span class='cart-des'>".$product['productPart']."</span></td>\n";
						}

						## SUBTOTAL CALCULATION ##
						$p = "";
						$fallback = "";
						$minorder = "";
						$qtysize = explode("-", $_SESSION['cart'][$id]);

						if ($product['productCarton'] && !$product['productInner']) {
							$minorder = "Carton";
						} else if ($product['productInner']) {
							$minorder = "Inner";
						} else {
							$minorder = "Unit";
						}

						if ($_SESSION['membership']['mmemberDiscount']) {
							$a = $_SESSION['membership']['mmemberDiscount'];
							$b = ($a * $product[$site['template']['price']['field']]) / 100;
							$d = $product[$site['template']['price']['field']] - $b;
							$product[$site['template']['price']['field']] = $d;
						}

					#	if ($_SESSION['membership']['mmemberDiscount']) {
					#		$a2 = $_SESSION['membership']['mmemberDiscount'];
					#		$b2 = ($a2 * $p) / 100;
					#		$d2 = $p - $b2;
					#		$p = $d2;
					#	}

						$cost = $product[$site['template']['price']['field']];

						if ($qtysize['1'] == "Inner" && $product['productInner']) {
							$multiple = $product['productInner'];
						}
						if ($qtysize['1'] == "Carton" && $product['productCarton']) {
							$multiple = $product['productCarton'];
						}
						if ($qtysize['1'] == "Unit" && $product['productPrice1']) {
							$multiple = 1;
						}

						$subtotal = ($cost*$multiple)*$qtysize['0'];

						$minorder = getmin($product['productId']);

						echo "<td align='center'><span class='cart-title'>$".number_format($product[$site['template']['price']['field']],$site['template']['price']['decimal'])." ea</span>";
						if ($minorder || $_SESSION['membership']['mmemberDiscount']) {
							echo "<br />";
							if ($_SESSION['membership']['mmemberDiscount']) {
								echo "<span style='color: #FC4A1A;font-size:10px;'>$".number_format($b, $site['template']['price']['decimal'])." discount</span><br />";
							}
							if ($minorder) {
								echo "<span class='cart-des'>MIN ORDER:<br />1 ".$minorder."</span>";
							}
						}
						echo "</td>\n";
						echo "<td align='center' height='90'><input type='text' size='1' name='cart[".$id."]' value='".$qtysize['0']."' onChange='form.submit()' />";

						## Give Min Order Options, If They Exist ##
						if (($product['productCarton'] || $product['productInner']) && $product['productPrice1']) {
							echo "<select name='size[".$id."]' onChange='form.submit()'>\n";
								if ($product['productInner']) {
									if ($qtysize['1'] == "Inner") {
										$sel = "selected";
										$amount = $qtysize['0']*$product['productInner'];
#										$amount = $_POST['cart'][$id]*$product['productInner'];
									}
									echo "<option value='Inner' ".$sel.">Inner</option>\n";
								}
								$sel = "";
								if ($product['productCarton']) {
									if ($qtysize['1'] == "Carton") {
										$sel = "selected";
										$amount = $qtysize['0']*$product['productCarton'];
#										$amount = $_POST['cart'][$id]*$product['productCarton'];
									}
									echo "<option value='Carton' ".$sel.">Carton</option>\n";
								}
								$sel = "";
							echo "</select>\n";
							echo "<br />Total $amount\n";
						}

						echo "</td>\n";
						echo "<td style='text-align:right'><span class='cart-title'>$".number_format($subtotal, $site['template']['price']['decimal'])."&nbsp;&nbsp;</span><a class='cart-delete' href='#' onclick=\"confirmWindow('Are you sure you want to remove this item?','".$site['url']['actual']."?remove=".$product['productId']."'); return false;\">X&nbsp;&nbsp;REMOVE</a></td>\n";
						$total += $subtotal;
					echo "</tr>\n";
					$_SESSION['order'] .= "<tr>";
					if ($display['cart']['part']['heading']) {
						$_SESSION['order'] .= "<td>".$product['productPart']."</td>";
					}
					if ($display['cart']['title']['heading']) {
						$_SESSION['order'] .= "<td>".$product['productTitle']."</td>";
					}
					$_SESSION['order'] .= "<td align=\"right\">$".number_format($product[$site['template']['price']['field']],$site['template']['price']['decimal']);
					if ($_SESSION['membership']['mmemberDiscount']) {
						$_SESSION['order'] .= "<br />$".number_format($b, $site['template']['price']['decimal'])." discount";
					}
					$_SESSION['order'] .= "</td>";

					## This used existing qty figuring out to show qty in inners, units or cartons depending on what is selected
					# $_SESSION['order'] .= "<td align=\"center\">".$qtysize['0']." ".$qtysize['1']."</td>";

					## this uses its own stuff to figure our how many units are ordered depending on all selections
					if ($qtysize['1'] != "Unit") {
						## $qtysize['1'] is Carton or inner depending on selection
						$var  = "product".$qtysize[1]; ## sql column to check depending on option selected
						$qty1 = $qtysize['0']; ## Amount of cartons or inners
						$qty2 = $product[$var]; ## gets the ammount of units inside the selected inner or carton
						$unitQty = $qty1*$qty2;
					} else {
						$unitQty = $qtysize['0'];
					}
					$_SESSION['order'] .= "<td align=\"center\">".$unitQty." Units</td>";

					$_SESSION['order'] .= "<td align=\"right\">$".number_format($subtotal, $site['template']['price']['decimal'])."</td>";
					$_SESSION['order'] .= "</tr>";

				} ## End Foreach
				echo "</table>\n";
				echo "<div id='cart-summary'>\n";
				echo "<div id='summary-title'><span class='cart-title'>ORDER SUMMARY</span></div>";
					echo "<div id='total'>\n";

					if ($display['cart']['weight']['heading']) {
						echo "<span class='cartweight'>(Total ".$display['cart']['weight']['heading'].": ".$totalweight." ".$display['cart']['weight']['unit'].")</span> \n";
					}
					echo "<span style='float:left'>Sub Total</span><span style='float:right'>$".number_format($total, $site['template']['price']['decimal'])."&nbsp;&nbsp;</span>";
					if ($_SESSION['membership']['mmemberDiscount']) {
						echo "<br /><span style='font-size:12px;color:#FC4A1A;'>Including ".$_SESSION['membership']['mmemberDiscount']."% discount</span>";
					}
					echo "</div>\n";

			$_SESSION['order'] .= "<tr><td colspan=\"".$ordercolspan."\" align=\"right\">Total Price: $".number_format($total, $site['template']['price']['decimal']);
			if ($_SESSION['membership']['mmemberDiscount']) {
				$_SESSION['order'] .= "<br />Including ".$_SESSION['membership']['mmemberDiscount']."% discount";
			}
			$_SESSION['order'] .= "</td></tr>";
			$_SESSION['order'] .= "</table>";
			$_SESSION['submit']['orderOrder'] = $_SESSION['order'];

			if ($table['order']['orderFreight']['insert']) {
				$_SESSION['submit']['orderPrice'] = $total;
				$_SESSION['submit']['orderFreight'] = $table['order']['orderFreight']['insert'];
				$_SESSION['submit']['orderTotal'] = $total+$table['order']['orderFreight']['insert'];
			} else {
				$_SESSION['submit']['orderPrice'] = $total;
				$_SESSION['submit']['orderTotal'] = $total;
				$_SESSION['submit']['orderWeight'] = $totalweight;
			}
			echo "<div><a id='shopping-browse' href='".$_SESSION['pageurl']."'>Browse</a></div>";

			if ($table['order']['orderFreight']['insert']) {
				echo "<p align='center'>*Please note that $".$table['order']['orderFreight']['insert']." freight will be added to you order.</p>\n";
			}
			// if ($button['cart']['update']['image']) {
			// 	echo "<input type='image' src='".$site['url']['full']."images/".$button['cart']['update']['image']."' name='button-update' width='".$button['cart']['update']['width']."' height='".$button['cart']['update']['height']."'/> \n";
			// } else {
			// 	echo "<input type='submit' name='button' value='Update' /> \n";
			// }
			if ($button['cart']['checkout']['image']) {
				echo "<input type='image' src='".$site['url']['full']."images/".$button['cart']['checkout']['image']."' name='button-checkout' width='".$button['cart']['checkout']['width']."' height='".$button['cart']['checkout']['height']."'/> \n";
			} else {
				echo "<div><button id='shopping-checkout' type='submit' name='button' value='Checkout'><img id='checkout-lock' src='/images/new/checkout-lock.png'>&nbsp;&nbsp;Checkout</button></div> \n";
			}

			echo "</div>\n";
			echo "</div>\n";
			echo "</form>\n";
		}
	}

	##############
	#  End Cart  #
	##############


	#########################
	#  Start Enter Details  #
	#########################

	if ($_SESSION['cartMode'] == "Enter Details") {
		// echo "<p>".$message['info']."</p>\n";
		echo "<form id='edit-cart-form' action='".$site['url']['cart']."' method='post'>\n";
		if ($button['cart']['editcart']['image']) {
			echo "<a href='".$site['url']['cart']."/?edit_cart=y'><img src='".$site['url']['full']."images/".$button['cart']['editcart']['image']."' border='0' /></a>\n";
		} else {
			echo "<input type='submit' name='button' value='Edit Cart' />\n";
		}
		echo "</form>\n";
		echo "<form action='".$site['url']['form']."' method='post'>\n";
		echo "<table class='table'>\n";
		foreach ($layout as $key=>$data) {
			if ($layout[$key]['display'] == 'Y' && $layout[$key]['type'] != 'B') {
				echo display_layout($key);
			}
		}
		if ($layout['card']) {
			echo display_layout('card');
			echo "<tr><th align='left'>".$layout['orderCardType']['heading'].":</th><td>\n";
			echo "<select name='submit[orderCardType]'>\n";
			foreach ($layout['orderCardType']['item'] as $index=>$item) {
				$selected = "";
				if ($_SESSION['submit']['orderCardType'] == $index) { $selected = "selected"; }
				echo "<option value='".$index."' $selected>$item</option>\n";
			}
			echo "</select> &nbsp;\n";
			foreach ($layout['orderCardType']['item'] as $index=>$item) {
				if ($index == 'Mastercard') { echo "&nbsp;<img src='".$site['url']['full']."images/card-mastercard.jpg' border='1' alt='Mastercard' />\n"; }
				if ($index == 'Visa')       { echo "&nbsp;<img src='".$site['url']['full']."images/card-visa.jpg' border='1' alt='Visa' />\n"; }
				if ($index == 'Amex')       { echo "&nbsp;<img src='".$site['url']['full']."images/card-amex.jpg' border='1' alt='American Express' />\n"; }
			}
			echo "</td></tr>\n";
			echo display_layout('orderCardNumber');
			echo display_layout('orderCardName');
			echo "<tr>\n";
				echo "<th align='left'>Expiry Date:</th>\n";
				echo "<td>\n";
				echo $layout['orderCardExpiryM']['heading'].": <input type='text' name='submit[orderCardExpiryM]' size='".$layout['orderCardExpiryM']['size']."' value='".$_SESSION['submit']['orderCardExpiryM']."'>\n";
				echo " / ".$layout['orderCardExpiryY']['heading'].": <input type='text' name='submit[orderCardExpiryY]' size='".$layout['orderCardExpiryY']['size']."' value='".$_SESSION['submit']['orderCardExpiryY']."'>\n";
				if ($layout['orderCardCVV']['heading']) { echo "&nbsp;&nbsp;&nbsp;".$layout['orderCardCVV']['heading'].": <input type='text' name='submit[orderCardCVV]' size='".$layout['orderCardCVV']['size']."' value='".$_SESSION['submit']['orderCardCVV']."'>\n"; }
				echo "</td>\n";
			echo "</tr>\n";
		}
		echo display_layout('summary');
		echo display_layout('orderPrice');

		if ($layout['orderFreight']['type'] == "F") {
			$_SESSION['weightLevel'] = '';
			foreach ($table['freight'] as $a=>$b) {
				if ($_SESSION['submit']['orderWeight'] < $a && !$_SESSION['weightLevel']) {
					$_SESSION['weightLevel'] = $a;
				}
			}
			foreach ($table['freight'][$_SESSION['weightLevel']] as $key=>$data) {
				$layout['orderFreight']['item'][$data] = "$".$data." - ".$key;
			}

		}
#		echo display_layout('orderFreight');
		if ($table['order']['orderTotal']['display'] == "Y") {
			echo display_layout('orderTotal');
		}
		echo "<tr>\n";
		echo "<th colspan='2' align='center'>\n";
		if ($button['cart']['placeorder']['image']) {
			echo "<input type='image' src='".$site['url']['full']."images/".$button['cart']['placeorder']['image']."' name='button-placeorder' width='".$button['cart']['placeorder']['width']."' height='".$button['cart']['placeorder']['height']."'/>\n";
		} else {
			echo "<input id='place-order' type='submit' name='button' value='Place Order' />\n";
		}
		echo "</th>\n";
		echo "</tr>\n";
		echo "</table>\n";
		if ($error) {
			echo "<br /><p align='center' class='error'>*All required fields need to be entered.</p><br />\n";
		}
		echo "<div id='cartmessage'>\n";
			echo $message['freight'];
			echo $message['ssl'];
		echo "</div>\n";
		echo "</form>\n";
	}

	#######################
	#  End Enter Details  #
	#######################

	#########################
	#  Start Confirm Order  #
	#########################

	if ($_SESSION['cartMode'] == "Confirm Order") {
		echo "<form action='".$site['url']['form']."' method='post'>\n";
		echo "<input type='submit' name='button' value='Edit Details' />\n";
		echo "<p>Here are your order details</p>\n";
		echo "<div id='cart'>\n";
		echo "<table cellspacing='0' cellpadding='3' border='0' width='533'>\n";
			echo "<tr>\n";
				echo "<th width='150'></th>\n";
				echo "<th>Title</th>\n";
				echo "<th width='80'>Price</th>\n";
				echo "<th width='50'>Quantity</th>\n";
				echo "<th width='80'>Subtotal</th>\n";
			echo "</tr>\n";
			foreach ($_SESSION['cart'] as $id => $qty) {
				$sql  = "SELECT * FROM ".$site['database']['product']." ";
				$sql .= "WHERE `productId` = '".$id."' ";
				$resultproduct = sql_exec($sql);
				$product = $resultproduct->fetch_assoc();
				$image_array = array();
				$sql  = "SELECT * FROM ".$site['database']['product-image']." WHERE `productImageProduct` = '".$id."' ORDER BY `productImageOrder`, `productImageTitle`";
				$resultimage = sql_exec($sql);
				while ($image = $resultimage->fetch_assoc()) {
					$image_array[] = $image;
				}
				echo "<tr>\n";
					echo "<td><img src='".$site['url']['full'].image_display($site['path']['product']['thumb'], $image_array['0']['productImageFile'])."' border='0' alt='".$product['productTitle']."' title='".$product['productTitle']."'/></td>\n";
					echo "<td align='center'>".$product['productTitle']."</td>\n";
					echo "<td align='center'>$".number_format($product['productPrice1'], $site['template']['price']['decimal'])."</td>\n";
					$subtotal = $_SESSION['cart'][$id]*$product['productPrice1'];
					echo "<td align='center'>".$_SESSION['cart'][$id]."</td>\n";
					echo "<td align='right'>$".number_format($subtotal, $site['template']['price']['decimal'])." &nbsp;</td>\n";
					$total += $subtotal;
				echo "</tr>\n";
			}
			echo "<tr>\n";
				echo "<td colspan='6' align='right' id='total'>Total Price: $".number_format($total, $site['template']['price']['decimal'])." &nbsp;</td>\n";
			echo "</tr>\n";
		echo"</table>\n";
		echo "</div><br />\n";
		echo "<input type='submit' name='button' value='Checkout' />\n";
		echo "</form>\n";
	}

	#######################
	#  End Confirm Order  #
	#######################

	###########################
	#  Start Process Details  #
	###########################

	if ($_SESSION['cartMode'] == "Process Details") {

		if ($layout['orderFreight']['type'] == "F") {
			$_SESSION['submit']['orderTotal'] = $_SESSION['submit']['orderPrice']+$_SESSION['submit']['orderFreight'];
		}

		$sql   = "INSERT INTO ".$site['database']['order']." (";
		$counter = "1";
		foreach ($layout as $fieldKey=>$fieldData) {
			if ($fieldData['type'] != 'H' && $fieldData['type'] != 'B' && $fieldData['type'] != 'Index' && $fieldData['insert'] != 'NULL') {
				if ($counter > '1') { $sql .= ", ";}
				$counter ++;
				$sql .= "`".$fieldKey."`";
			}
		} # END foreach
		$sql .= ") VALUES (";
		$counter = "1";
		foreach ($layout as $fieldKey=>$fieldData) {
			if ($fieldData['type'] != 'H' && $fieldData['type'] != 'B' && $fieldData['type'] != 'Index' && $fieldData['insert'] != 'NULL') {
				if ($counter > '1') { $sql .= ", ";}
				$counter ++;
				if ($_SESSION['submit'][$fieldKey]) {
					if ($fieldData['type'] != 'd') {
						$sql .= "'".$_SESSION['submit'][$fieldKey]."'";
					} else {
						$date = explode_date($_SESSION['submit'][$fieldKey]);
						$date = reconvert_date($date);
						$sql .= "'".$date."'";
					}
				} else {
					if ($fieldData['type'] == 'd' && $fieldData['insert'] == 'NOW') {
						$sql .= "now()";
					} else {
						$sql .= "'".$fieldData['insert']."'";
					}
				}
			}
		} # END foreach
		$sql .= ")";
		$result = sql_exec($sql);

		if ($site['company']['email']['enabled'] == "y") {
			#####################
			# SMS               #
			#####################

			if($site['sms']['number'] && $static['sms']['enabled'] == 'y') {
				$mailSubject = "";
				$smsBody    = "[".$_SESSION['log']."] ";
				$smsBody   .= $_SESSION['submit']['orderNameF']." ".$_SESSION['submit']['orderNameS'].".";
				$smsBody   .= "P ".$_SESSION['submit']['orderPhone']." E ".$_SESSION['submit']['orderEmail']." ";
				$smsBody   .= "Online Order";
				mail($site['sms']['number'], $smsSubject, $smsBody, $site['sms']['email']."\r\nMIME-Version:1.0 \r\nContent-type:text/html; charset=iso-8859-1\r\n" ) or print $errorMSG;
				mail($static['sms']['email'], "SMS COPY - ".$site['company']['name'], $site['company']['name'].$smsBody, $site['sms']['email']."\r\nMIME-Version:1.0 \r\nContent-type:text/html; charset=iso-8859-1\r\n" ) or print $errorMSG;
			}


			#####################
			# EMAIL TO BUSINESS #
			#####################

			$mailSubject = "[".$_SESSION['log']."] ".$site['company']['name']." - Order";
			$mailBody    = "<!doctype html public '-//w3c//dtd html 4.0 transitional//en'>\n";
			$mailBody   .= "<html>\n";
			$mailBody   .= "<head>\n";
			$mailBody   .= "<meta http-equiv=content-type content='text/html; charset=windows-1252'>\n";
			$mailBody   .= "<link rel='stylesheet' href='".$site['url']['full']."include/recommend.css'>\n";
			$mailBody   .= "</head>\n";
			$mailBody   .= "<table>";
			$mailBody   .= "<tr>";
			$mailBody   .= "<td colspan='2' id='heading'><img src='".$site['url']['full']."images/".$site['template']['recommend']['header']."' alt='".$site['company']['name']."' /></td>";
			$mailBody   .= "</tr>";

			foreach ($layout as $fieldKey=>$fieldData) {
				if ($_SESSION['submit']['orderPaymentType'] != "CC") {
					if ($fieldData['type'] != "Index" && $fieldData['type'] != "B" && $fieldKey != "orderDateCreate" && $fieldKey != "orderDateUpdate" && $fieldKey != "orderUserUpdate" && $fieldKey != "orderStatus" && $fieldKey != "card" && $fieldKey != "orderCardType" && $fieldKey != "orderCardNumber" && $fieldKey != "orderCardName" && $fieldKey != "orderCardExpiryM" && $fieldKey != "orderCardExpiryY" && $fieldKey != "orderCardCVV") {
						if ($fieldData['type'] == "H") {
							$mailBody .= "<tr>";
							$mailBody .= "<th colspan='2' align='left'><br /><u>".$fieldData['heading']."</u></th>";
							$mailBody .= "</tr>";
						} else if ($fieldData['type'] == "D") {
							$mailBody .= "<tr>";
							$mailBody .= "<th align='left' valign='top'>".$fieldData['heading'].":</th>";
							$mailBody .= "<td>".$fieldData['item'][$_SESSION['submit'][$fieldKey]]."</td>";
							$mailBody .= "</tr>";
						} else if ($fieldData['type'] == "M") {
							$mailBody .= "<tr>";
							$mailBody .= "<th align='left' valign='top'>".$fieldData['heading'].":</th>";
							$mailBody .= "<td>$".number_format($_SESSION['submit'][$fieldKey], $site['template']['price']['decimal'])."</td>";
							$mailBody .= "</tr>";
						} else {
							$mailBody .= "<tr>";
							$mailBody .= "<th align='left' valign='top'>".$fieldData['heading'].":</th>";
							$mailBody .= "<td>".$_SESSION['submit'][$fieldKey]."</td>";
							$mailBody .= "</tr>";
						}
					}
				} else {
					if ($fieldData['type'] != "Index" && $fieldData['type'] != "B" && $fieldKey != "orderDateCreate" && $fieldKey != "orderDateUpdate" && $fieldKey != "orderUserUpdate" && $fieldKey != "orderStatus") {
						if ($fieldData['type'] == "H") {
							$mailBody .= "<tr>";
							$mailBody .= "<th colspan='2' align='left'><br /><u>".$fieldData['heading']."</u></th>";
							$mailBody .= "</tr>";
						} else if ($fieldData['type'] == "D") {
							$mailBody .= "<tr>";
							$mailBody .= "<th align='left' valign='top'>".$fieldData['heading'].":</th>";
							$mailBody .= "<td>".$fieldData['item'][$_SESSION['submit'][$fieldKey]]."</td>";
							$mailBody .= "</tr>";
						} else if ($fieldData['type'] == "M") {
							$mailBody .= "<tr>";
							$mailBody .= "<th align='left' valign='top'>".$fieldData['heading'].":</th>";
							$mailBody .= "<td>$".number_format($_SESSION['submit'][$fieldKey], $site['template']['price']['decimal'])."</td>";
							$mailBody .= "</tr>";
						} else {
							$mailBody .= "<tr>";
							$mailBody .= "<th align='left' valign='top'>".$fieldData['heading'].":</th>";
							$mailBody .= "<td>".$_SESSION['submit'][$fieldKey]."</td>";
							$mailBody .= "</tr>";
						}
					}
				}
			} # END foreach

			$mailBody   .= "<tr>";
			$mailBody   .= "<td colspan='2'><br /><hr /></td>";
			$mailBody   .= "<tr>";
			$mailBody   .= "</table>";
			$mailBody  .= "</html>\n";
			$mailTo      = $site['company']['email']['to'];
			$mailFrom    = $site['company']['email']['from']."\r\nMIME-Version:1.0 \r\nContent-type:text/html; charset=iso-8859-1\r\n";

			$errorMSG = "<br />";

			mail( $mailTo, $mailSubject, $mailBody, $mailFrom ) or print $errorMSG;
			mail( $static['mail']['test'], $mailSubject, $mailBody, $mailFrom ) or print $errorMSG;


			#####################
			# EMAIL TO CUSTOMER #
			#####################

			$mailRSubject = $site['company']['name']." - Order";
			$mailRBody    = "<!doctype html public '-//w3c//dtd html 4.0 transitional//en'>\n";
			$mailRBody   .= "<html>\n";
			$mailRBody   .= "<head>\n";
			$mailRBody   .= "<meta http-equiv=content-type content='text/html; charset=windows-1252'>\n";
			$mailRBody   .= "<link rel='stylesheet' href='".$site['url']['full']."include/recommend.css'>\n";
			$mailRBody   .= "</head>\n";
			$mailRBody   .= "<table>";
			$mailRBody   .= "<tr>";
			$mailRBody   .= "<td colspan='2' id='heading'><img src='".$site['url']['full']."images/".$site['template']['recommend']['header']."' alt='".$site['company']['name']."' /></td>";
			$mailRBody   .= "</tr>";
			$mailRBody   .= "<tr><td colspan='2'>";
			$mailRBody   .= "<br />Thank you ".$_SESSION['submit']['orderNameF'].",\n\n<br /><br />";
			$mailRBody   .= "Your order is currently being processed.\n\n<br /><br />";
			$mailRBody   .= "You will be contacted soon.\n\n<br /><br />";
			$mailRBody   .= "<hr />";
			$mailRBody   .= "<br /><b>Here are your order details:</b>\n\n<br />";
			$mailRBody   .= "</td></tr>";

			foreach ($layout as $fieldKey=>$fieldData) {
				if ($fieldKey != "card" && $fieldKey != "orderCardType" && $fieldKey != "orderCardNumber" && $fieldKey != "orderCardName" && $fieldKey != "orderCardExpiryM" && $fieldKey != "orderCardExpiryY" && $fieldKey != "orderCardCVV") {
					if ($fieldData['type'] != "Index" && $fieldData['type'] != "B" && $fieldKey != "orderDateCreate" && $fieldKey != "orderDateUpdate" && $fieldKey != "orderUserUpdate" && $fieldKey != "orderStatus") {
						if ($fieldData['type'] == "H") {
							$mailRBody .= "<tr>";
							$mailRBody .= "<th colspan='2' align='left'><br /><u>".$fieldData['heading']."</u></th>";
							$mailRBody .= "</tr>";
						} else if ($fieldData['type'] == "D") {
							$mailRBody .= "<tr>";
							$mailRBody .= "<th align='left' valign='top'>".$fieldData['heading'].":</th>";
							$mailRBody .= "<td>".$fieldData['item'][$_SESSION['submit'][$fieldKey]]."</td>";
							$mailRBody .= "</tr>";
						} else if ($fieldData['type'] == "M") {
							$mailRBody .= "<tr>";
							$mailRBody .= "<th align='left' valign='top'>".$fieldData['heading'].":</th>";
							$mailRBody .= "<td>$".number_format($_SESSION['submit'][$fieldKey], $site['template']['price']['decimal'])."</td>";
							$mailRBody .= "</tr>";
						} else {
							$mailRBody .= "<tr>";
							$mailRBody .= "<th align='left' valign='top'>".$fieldData['heading'].":</th>";
							$mailRBody .= "<td>".$_SESSION['submit'][$fieldKey]."</td>";
							$mailRBody .= "</tr>";
						}
					}
				}
			} # END foreach

			$mailRBody   .= "<tr>";
			$mailRBody   .= "<td colspan='2'>";
			$mailRBody   .= "<br /><hr />";
			$mailRBody   .= "<br />Regards,\n\n<br />";
			$mailRBody   .= "<b>".$site['company']['manager']."</b>\n<br />";
			$mailRBody   .= "<b>".$site['company']['name']."</b>\n";
			$mailRBody   .= "<b>".$site['company']['managerT']."</b>\n<br />";
			$mailRBody   .= "Website: ".$site['company']['web']."\n<br />";
			$mailRBody   .= "Email: ".$site['company']['email']['to'];
			$mailRBody   .= "</td>";
			$mailRBody   .= "</tr>";
			$mailRBody   .= "</table>";
			$mailRBody   .= "</html>";
			$mailRTo      = trim($_SESSION['submit']['orderEmail']);
			$mailRFrom    = $site['company']['email']['from']."\r\nMIME-Version:1.0 \r\nContent-type:text/html; charset=iso-8859-1\r\n";

			mail( $mailRTo, $mailRSubject, $mailRBody, $mailRFrom ) or print $errorMSG;
			mail( $static['mail']['test'], $mailRSubject, $mailRBody, $mailRFrom ) or print $errorMSG;

			#####################
		} # END if

		$sql  = "UPDATE ".$site['database']['logSession'];
		$sql .= " SET";
		$sql .= " logSStatus  = 'O'";
		$sql .= " WHERE logSId = '".$_SESSION['log']."' AND logSSiteId = '".$site['id']."';";
		$result = sql_exec($sql);

		if (!$message['thankyou']) {
			$message['thankyou'] = "<p>Your order is complete. An email will be sent with your order details.</p><p>You will be contacted soon to arrange payment.</p><br />";
		}

		echo $message['thankyou'];

		unset($_SESSION['cart']);
		unset($_SESSION['cartMode']);
		if($currentEmail == NULL){
		}else{
		$json_session = json_encode($_SESSION['cart']);
		$sql ="UPDATE".$site['database']['shopping_cart']."SET cart = '".$json_session."' WHERE `mmemberEmail` = '".$currentEmail."'";
		sql_exec($sql);
	  }
	}

	#########################
	#  End Process Details  #
	#########################
?>
