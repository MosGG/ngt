<?php
	include 'include/common.inc.php';
	$page['productId'] = $_GET['id'];
	include 'include/log.inc.php';

	$sql  = "SELECT * FROM ".$site['database']['product']." ";
	$sql .= "WHERE `productId` = '".$_GET['id']."'";
	$sqlresult = sql_exec($sql);
	$value = $sqlresult->fetch_assoc();
	if ($value['productFlag'] != "") {
		echo "<p>This product no longer exist!</p>";
	} else {
		$sql  = "SELECT * FROM ".$site['database']['product-image']." WHERE `productImageProduct` = '".$_GET['id']."' ORDER BY `productImageOrder`, `productImageTitle`";
		$result = sql_exec($sql);
		while ($line = $result->fetch_assoc()) {
			$image_array[] = $line;
		}

		echo "<div class='productdisplayouter'>";
		echo "<div class='productdisplayinner'>";
		// echo "<div class='productdisplayclose'><a href='#' onClick='productwin.hide(); return false'>Close <span id='closex'>X</span></a></div>";

		echo "<div class='productdisplayleft'>";
			echo "<div class='productdisplaylarge'>";
				echo "<img src='".$site['url']['full'].image_display($site['path']['product']['full'], $image_array[$_GET['image']]['productImageFile'])."' alt='".$image_array[$_GET['image']]['productImageDescription']."' title='".$image_array[$_GET['image']]['productImageDescription']."' />";
			echo "</div>";
		echo "</div>"; ## END DISPLAY LEFT ##

		echo "<div class='productdisplayright'>";
			echo "<div class='productdisplaytitle'>";
				echo "<span style='font-size: 12px;'>".$value['productPart']."</span>";
				echo "<h1>".$value['productTitle']."</h1>";
				if ($value['productCategory'] == "Out of Stock") {
					echo "<span class='productsold'>Out of Stock</span>";
				}
				if ($value['productCategory'] == "Coming Soon") {
					echo "<span class='productsoon'>Coming Soon</span>";
				}
			echo "</div>";
			echo "<div class='productdisplaytext'>";
				$value[productDescription] = str_replace("\r\n", "<br />", $value[productDescription]);
				echo "<p>".$value['productDescription']."</p>";
			echo "</div>";
			echo "<div class='productdisplayprice'>";
				echo "<div class='productdisplaylink'>";
					if ($value['productPrice1'] > 0 && ($value['productCategory'] != "Out of Stock" && $value['productCategory'] != "Coming Soon") && ($_SESSION['membership'] || $_SESSION['member'])) {
						echo "<a href='".$site['url']['full']."order_cart/add/".$value['productId']."'>Add to Cart</a>";
					} else if (!$_SESSION['membership'] && !$_SESSION['member']) {
						echo "<a href='".$site['url']['full']."login/'>Login</a>";
					} else {
						echo "<a href='".$site['url']['full']."contact/?description=".$value['productTitle']." - Stock Item ".$value['productPart']."'>Contact Us</a>";
					}
				echo "</div>";
				if (!$_SESSION['membership'] && !$_SESSION['member']) {
					echo "Login for Price";
				} else if ($value['productPrice1'] == '0') {
					echo "Call for Price";
				} else {
					echo "<b>$".number_format($value['productPrice1'], $site['template']['price']['decimal'])."</b>";
					if (($value['productPrice2'] !== null) && ($value['productPrice2'] !== 0)){
						echo "<b style='padding-left:10px;text-decoration:line-through;'>WAS $".number_format($value['productPrice2'], $site['template']['price']['decimal'])."</b>";
					}
					
#					if ($m = getmin($value['productId'])) {
#						echo "<br /><span class='cartnote'>Min Order: 1 ".$m."</span>";
#					}
					echo "<br /><span class='cartnote'>Min Order qty: ".$value['productInner']."<br />Carton Qty: ".$value['productCarton']."</span>";

				}
			echo "</div>";
			if (count($image_array) > '1') {
				echo "<div class='productdisplayimages'>";
				foreach ($image_array as $key=>$data) {
					echo "<div class='productdisplayimage'>";
					echo "<a href='#' onClick=\"openproduct('".$_GET['id']."','".$key."', '".$value['productTitle']."'); return false\">";
					$size = getimagesize($site['url']['path'].$site['path']['product']['thumb'].$data['productImageFile']);
					echo "<img src='".$site['url']['full'].$site['path']['product']['thumb'].$data['productImageFile']."' border='0' width='".($size['0']*0.778)."' height='".($size['1']*0.778)."' alt='".$data['productImageDescription']."' title='".$data['productImageDescription']."'>";
					echo "</a>";
					echo "</div>";
				} # foreach
				echo "</div>"; ## END DISPLAY IMAGES ##
			}
		echo "</div>"; ## END DISPLAY RIGHT ##

#		echo "<a href='".$site['url']['full']."contact/?description=".$value['productTitle']." - Stock Item ".$value['productPart']."'><img src='".$site['url']['full']."images/enquiry.jpg' align='middle' border='0' /></a>&nbsp;";
		echo "</div> <!-- productdisplayinner -->";
		echo "</div> <!-- productdisplayouter -->";
	}
?>