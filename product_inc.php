<script type="text/javascript">
	function openproduct(productid, imageid, productTitle){ //Product Window
		productwin=dhtmlwindow.open('productwin', 'ajax', '<?php echo $site['url']['full']."product_display.php?id="; ?>'+productid+'&image='+imageid, productTitle,'width=705px,height=460px,left=100px,top=20px,resize=1,scrolling=1')
	}
</script>
<?php
#	echo content_converter($page['pageText']);

	if (file_exists('include/product.tpl.php')) {
		include 'include/product.tpl.php';
	} else {
		include '/home/hosting/template-v0d/product.tpl.php';
	}

	$sql  = "SELECT count(*) FROM ".$site['database']['product-link'].", ".$site['database']['product']." ";
	$sql .= "WHERE `product-linkPage` = '".$page['pageId']."' AND `productId` = `product-linkProduct` AND `productPrice1` > '0' AND `productStock` > '0' ";
	$sql .= "ORDER BY `productCategory`, `productTitle`;";
	$sqlresult = sql_exec($sql);
	while ($value = $sqlresult->fetch_assoc()) {
		$sqlcount = $value['count(*)'];
	}

	if ($sqlcount) {
		echo "<div id='product'>";
		echo "<form action='".$site['url']['actual']."' method='post'>";
		$sql  = "SELECT * FROM ".$site['database']['product-link'].", ".$site['database']['product']." ";
		$sql .= "WHERE `product-linkPage` = '".$page['pageId']."' AND `productId` = `product-linkProduct` AND `productPrice1` > '0' AND `productStock` > '0' ";
#		$sql .= "ORDER BY `productPart` DESC, `productTitle` ASC";
		$sql .= "ORDER BY `productDateUpdate` DESC, `productDateCreate` DESC, `productPart` DESC, `productTitle` ASC";

		$result = sql_exec($sql);
		$pointer = "0";
		while ($line = $result->fetch_assoc()) {
			$image_array = array();
			$sql  = "SELECT * FROM ".$site['database']['product-image']." WHERE `productImageProduct` = '".$line['productId']."' ORDER BY `productImageOrder`, `productImageId`, `productImageTitle`";
			$resultimage = sql_exec($sql);
			while ($image = $resultimage->fetch_assoc()) {
				$image_array[] = $image;
			}
			if ($site['database']['product-pdf']) {
				$pdf_array = array();
				$sql  = "SELECT * FROM ".$site['database']['product-pdf']." WHERE `productPdfProduct` = '".$line['productId']."' ORDER BY `productPdfOrder`, `productPdfDescription`";
				$resultpdf = sql_exec($sql);
				while ($pdf = $resultpdf->fetch_assoc()) {
					$pdf_array[] = $pdf;
				}
				product_template($line, $image_array, $pdf_array);
			}

			if (!$site['database']['product-pdf']) {
				product_template($line, $image_array);
			}

		} # (while-$line)
		echo "</form>";
		echo "</div> <!-- product -->";
	}
?>