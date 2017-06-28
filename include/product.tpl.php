<?php
function product_template($line, $image) {
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
	echo "<div class='productimage' style='background:url(".$site['url']['full'].image_display($site['path']['product']['full'], $image['0']['productImageFile']).") no-repeat center;background-size:cover;'><a href='#' onclick=\"openproduct('".$line['productId']."', '0', '".$line['productTitle']."'); putMiddle(); return false\"><img src='".$site['url']['full'].image_display($site['path']['product']['full'], $image['0']['productImageFile'])."' border='0' alt='".$line['productTitle']."' title='".$line['productTitle']."'></a></div>\n";
	echo "<div class='producttitle'>";
	$text = breaktext($line['productTitle'], 40);
	$text['text'] = "<span style='font-size: 10px;'>".$line['productPart']." (".$line['productStock']." Available)</span><br />".$text['text'];
	echo "<a href='#' class='line".$text['lines']."' onclick=\"openproduct('".$line['productId']."', '0', '".$line['productTitle']."'); putMiddle(); return false\">";
	echo $text['text'];
	echo "<br /></a>";
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
				$nodisc = "";
			} else {
				$nodisc = " nodisc";
			}
			if (isset($line['productPrice2']) and $line['productPrice2'] != '0') {					
				echo "<span class='price".$nodisc."'><b><span class='prod-sale'>$".number_format($line['productPrice1'], $site['template']['price']['decimal'])." ea</span></b></span>";
				echo "&nbsp;<span class='prod-was'>$".number_format($line['productPrice2'], $site['template']['price']['decimal'])." ea</span>";
			} else {
				echo "<span class='price".$nodisc."'><b><span class='prod-price'>$".number_format($line['productPrice1'], $site['template']['price']['decimal'])." ea</span></b></span>";
			}
#			if ($m = getmin($line['productId'])) {
#				echo "<br /><span class='cartnote'>Min Order: 1 ".$m."</span>";
#			}
			echo "<br /><span class='cartnote-out'>Min Order Qty: ".$line['productInner']."<br />Carton Qty: ".$line['productCarton']."</span>";

		}
	echo "</div>\n";
	echo "<div class='productlink'>";
	if ($line['productPrice1'] > 0 && $line['productStock'] != "0" && ($line['productCategory'] != "Out of Stock" && $line['productCategory'] != "Coming Soon") && ($_SESSION['membership'] || $_SESSION['member'])) {
		echo "<a href='".$site['url']['full']."order_cart/add/".$line['productId']."'><div class='add-to-cart-shopping-bag'></div></a>";
	} else if (!$_SESSION['membership'] && !$_SESSION['member']) {
		echo "<a href='".$site['url']['full']."login/'>Login</a>";
	} else {

			echo "<a href='".$site['url']['full']."contact/?description=".$line['productTitle']." - Stock Item ".$line['productPart']."'>Contact</a>";
	}
	echo "</div>\n";
	//discount block 
	if ($_SESSION['membership']['mmemberDiscount']) {
		echo "<div class='discount'><span>".$_SESSION['membership']['mmemberDiscount']."% Extra Off</span></div>";
	}
	//out of stock and coming soon block
	if ($line['productCategory'] == "Out of Stock") {
		echo "<div class='sold'><span>Out of Stock</span></div>";
	}
	if ($line['productCategory'] == "Coming Soon") {
		echo "<div class='soon'><span>Coming Soon</span></div>";
	}
	//sale block
	if (isset($line['productPrice2']) and $line['productPrice2'] != '0') {
		echo "<div class='sale-div'>SALE</div>";
	}
	//new arrival block
	if (isset($line['newarrival'])){
		echo "<div class='new-div'>NEW</div>";
	}
	echo "</div>\n <!-- productitem -->";
}# (product_template)
?>