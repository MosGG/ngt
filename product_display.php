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

		echo "<div class='productdisplayleft'>";
			echo "<div class='productdisplaylarge'>";
				echo "<img src='".$site['url']['full'].image_display($site['path']['product']['full'], $image_array[$_GET['image']]['productImageFile'])."' alt='".$image_array[$_GET['image']]['productImageDescription']."' title='".$image_array[$_GET['image']]['productImageDescription']."' />";
			echo "</div>";
		echo "</div>"; ## END DISPLAY LEFT ##

		echo "<div class='productdisplayright'>";
			echo "<div class='productdisplaytitle'>";
				echo "<h1>".$value['productTitle']."</h1>";
				echo "<span>".$value['productPart']."</span>";
			echo "</div>";
			echo "<div class='productdisplaystock'>";
				if ($value['productCategory'] == "Out of Stock") {
					echo "<span class='productsold'>Out of Stock</span>";
				} else if ($value['productCategory'] == "Coming Soon") {
					echo "<span class='productsoon'>Coming Soon</span>";
				} else {
					echo "<span class='productstock'>Availability: ".$value['productStock']." In Stock</span>";
				}
				echo "<span class='cartnote'>Min Order qty: ".$value['productInner']."<br />Carton Qty: ".$value['productCarton']."</span>";
			echo "</div>";
			if (!empty($value['productDescription'])) {
				echo "<div class='productdisplaytext'>";
					echo "<span class='productdisplayDesTtl'>DESCRIPTION</span><br>";
					$value['productDescription'] = str_replace("\r\n", "<br />", $value['productDescription']);
					echo "<span id='productdisplaydes'>".$value['productDescription']."</span>";
				echo "</div>";
			}
			echo "<div class='productdisplayprice'>";
				if (!$_SESSION['membership'] && !$_SESSION['member']) {
					echo "Login for Price";
				} else if ($value['productPrice1'] == '0') {
					echo "Call for Price";
				} else {
					echo "<b>$".number_format($value['productPrice1'], $site['template']['price']['decimal'])." ea</b>";
					if (!empty($value['productPrice2'])){
						echo "<b style='color:#9B9B9B;padding-left:10px;text-decoration:line-through;'>$".number_format($value['productPrice2'], $site['template']['price']['decimal'])." ea</b>";
					}
#					if ($m = getmin($value['productId'])) {
#						echo "<br /><span class='cartnote'>Min Order: 1 ".$m."</span>";
#					}
					// echo "<br /><span class='cartnote'>Min Order qty: ".$value['productInner']."<br />Carton Qty: ".$value['productCarton']."</span>";

				}
				echo "<div class='productdisplaylink'>";
					if ($value['productPrice1'] > 0 && ($value['productCategory'] != "Out of Stock" && $value['productCategory'] != "Coming Soon") && ($_SESSION['membership'] || $_SESSION['member'])) {
						echo "<a href='".$site['url']['full']."order_cart/add/".$value['productId']."'><img src='/images/new/shopping-bag.png'>Add to Cart</a>";
					} else if (!$_SESSION['membership'] && !$_SESSION['member']) {
						echo "<a href='".$site['url']['full']."login/'>Login</a>";
					} else {
						echo "<a href='".$site['url']['full']."contact/?description=".$value['productTitle']." - Stock Item ".$value['productPart']."'>Contact Us</a>";
					}
				echo "</div>";
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