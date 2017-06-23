<?php
function product_template($line, $image) {
	if($line['productStock'] <= "0" && $line['productCategory'] != "Out of Stock" && $line['productCategory'] != "Coming Soon"){
	}
	else{
	global $site;
	global $pointer;
	$pointer ++;
	$odd = $pointer%2;
	if ($odd == 0) {
		$class = 'even';
	} else {
		$class = 'odd';
	}
	echo "<div class='productitem ".$class."'>\n";
	echo "<div class='productimage'><a href='#' onclick=\"openproduct('".$line['productId']."', '0', '".$line['productTitle']."'); return false\"><img src='".$site['url']['full'].image_display($site['path']['product']['thumb'], $image['0']['productImageFile'])."' border='0' alt='".$line['productTitle']."' title='".$line['productTitle']."'>&nbsp;</a></div>\n";
	echo "<div class='producttitle'>";
	$text = breaktext($line['productTitle'], 40);
	$text['text'] = "<span style='font-size: 10px;'>".$line['productPart']." (".$line['productStock']." Available)</span><br />".$text['text'];
	echo "<a href='#' class='line".$text['lines']."' onclick=\"openproduct('".$line['productId']."', '0', '".$line['productTitle']."'); return false\">";
	echo $text['text'];
		if ($line['productCategory'] == "Out of Stock") {
			echo "<span class='sold'><b>Out of Stock</b></span>";
		}
		if ($line['productCategory'] == "Coming Soon") {
			echo "<span class='soon'><b>Coming Soon</b></span>";
		}
	echo "<br /><span style='text-transform: none; font-size: 9px; color: #ff0000; text-decoration: underline;'>Open Product</span></a>";
	echo "</div>\n";
	$line[productDescription] = str_replace("\r\n", "<br />", $line[productDescription]);
	echo "<div class='productprice'>";
		if (!$_SESSION['membership'] && !$_SESSION['member']) {
			echo "<span class='call'>Login for Price</span>";
		} else if ($line['productPrice1'] == "0") {
			echo "<span class='call'>Call for Price</span>";
		} else {
			if ($_SESSION['membership']['mmemberDiscount']) {
				$a = "0.".$_SESSION['membership']['mmemberDiscount'];
				$b = $a * $line['productPrice1'];
				$c = $_SESSION['membership']['mmemberDiscount']."%";
				$d = $line['productPrice1'] - $b;

				$line['productPrice1'] = $d;
				echo "<span class='discount'>$".number_format($b, $site['template']['price']['decimal'])." discount</span><br />";
				$nodisc = "";
			} else {
				$nodisc = " nodisc";
			}
			echo "<span class='price".$nodisc."'><b>$".number_format($line['productPrice1'], $site['template']['price']['decimal'])." <span style='text-transform: lowercase;'>ea</span></b></span>";
#			if ($m = getmin($line['productId'])) {
#				echo "<br /><span class='cartnote'>Min Order: 1 ".$m."</span>";
#			}
			echo "<br /><span class='cartnote'>Min Order Qty: ".$line['productInner']."<br />Carton Qty: ".$line['productCarton']."</span>";

		}
	echo "</div>\n";
	echo "<div class='productlink'>";
	if ($line['productPrice1'] > 0 && $line['productStock'] != "0" && ($line['productCategory'] != "Out of Stock" && $line['productCategory'] != "Coming Soon") && ($_SESSION['membership'] || $_SESSION['member'])) {
		echo "<a href='".$site['url']['full']."order_cart/add/".$line['productId']."'>Add to Cart</a>";
	} else if (!$_SESSION['membership'] && !$_SESSION['member']) {
		echo "<a href='".$site['url']['full']."login/'>Login</a>";
	} else {

			echo "<a href='".$site['url']['full']."contact/?description=".$line['productTitle']." - Stock Item ".$line['productPart']."'>Contact Us</a>";
	}
	echo "</div>\n";
	echo "</div>\n <!-- productitem -->";
	
	// echo "<br />\n";

} # (product_template)
?>
