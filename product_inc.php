<script type="text/javascript">
	function openproduct(productid, imageid, productTitle){ //Product Window
		productwin=dhtmlwindow.open('productwin', 'ajax', '<?php echo $site['url']['full']."product_display.php?id="; ?>'+productid+'&image='+imageid, productTitle,'width=auto,height=auto,resize=1,scrolling=1');
		putMiddle();
	}

	function putMiddle(){
		// var windowWidth = 822;
		var screenWidth = document.body.clientWidth;
		if (screenWidth < 991){
			var windowWidth = 722;
			var windowHeight = 502;
		} else {
			var windowWidth = 822;
			var windowHeight = 562;
		}
		var left = 0.5 * (screenWidth - windowWidth);
		var screenHeight = document.body.clientHeight;
		var top = 0.5 * (screenHeight - windowHeight);
		document.getElementById("productwin").style.top = top + "px";
		document.getElementById("productwin").style.left = left + "px";
	};
</script>
<?php
#	echo content_converter($page['pageText']);

	if (file_exists('include/product.tpl.php')) {
		include 'include/product.tpl.php';
	} else {
		include '/home/hosting/template-v0d/product.tpl.php';
	}

	$sql  = "SELECT count(*) FROM ".$site['database']['product-link'].", ".$site['database']['product']." ";
	$sql .= "WHERE `product-linkPage` = '".$page['pageId']."' AND `productId` = `product-linkProduct` AND `productPrice1` > '0' ";
	$sql .= "ORDER BY `productCategory`, `productTitle`;";
	$sqlresult = sql_exec($sql);
	while ($value = $sqlresult->fetch_assoc()) {
		$sqlcount = $value['count(*)'];
	}

	if ($sqlcount) {
		
		$sql  = "SELECT * FROM ".$site['database']['product-link'].", ".$site['database']['product']." ";
		$sql .= "WHERE `product-linkPage` = '".$page['pageId']."' AND `productId` = `product-linkProduct` AND `productPrice1` > '0'";
#		$sql .= "ORDER BY `productPart` DESC, `productTitle` ASC";
		$sql .= "ORDER BY `productDateUpdate` DESC, `productDateCreate` DESC, `productPart` DESC, `productTitle` ASC";

		$result = sql_exec($sql);
		$pointer = "0";

		$prodList = array();
		while ($line = $result->fetch_assoc()) {
			if ($line['productCategory'] !== 'In Stock' || $line['productStock'] > '0') {
				$prodList[] = $line;
			}
		}
		
		if (count($prodList) > 0) {
			//logic and html for slice page
			$itemsPerPage = 8;
			$offset = 0;
			$allPages = (count($prodList)%$itemsPerPage == 0)?(int)Floor(count($prodList) / $itemsPerPage):(int)Floor(count($prodList) / $itemsPerPage) + 1;
			$num = 1;

			if (!empty($_SERVER['QUERY_STRING'])){
				$query = substr($_SERVER['QUERY_STRING'], 0, 5);
				if ($query == 'page=') {
					$num = substr($_SERVER['QUERY_STRING'], 5);
					if ((int)($num) < 1){
						$num = 1;
					}
				}
			}
			$prod = array_slice($prodList, ($num - 1) * $itemsPerPage, $itemsPerPage);

			echo "<div id='product'>";
			if ($allPages > 1) {
				echo "<div id='layPage' class='laypage'></div>";
			}
			//get new arrival list
			$sql  = "SELECT `product-linkProduct` FROM ".$site['database']['product-link']." WHERE `product-linkPage` = '308'";
			$result = sql_exec($sql);
			$newArrivalList = array();
			while ($line = $result->fetch_assoc()) {
					$newArrivalList[$line['product-linkProduct']] = TRUE;
			}

			//echo product list
			echo "<form action='".$site['url']['actual']."' method='post'>";
			foreach($prod as $line) {
				if ($newArrivalList[$line['product-linkProduct']]) {
					$line['newarrival'] = true;
				}
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
			if ($allPages > 1) {
				echo "<div id='layPage2' class='laypage'></div>";
				?>
				<script type="text/javascript" src="/include/laypage/laypage.js"></script>
				<script type="text/javascript">
					laypage({
					  	cont: 'layPage',
						skin: '#4A4A4A',
						groups: 5,
						prev: "<img src='/images/new/prev.png'>",
					  	next: "<img src='/images/new/next.png'>",
					  	first: '1',
						last: <?php echo $allPages; ?>,
						pages: <?php echo $allPages; ?>,
						curr: function(){
							var page = location.search.match(/page=(\d+)/);
						    return page ? page[1] : 1;
					  	}(), 
						jump: function(e, first){
						    if(!first){
							    location.href = '?page='+e.curr;
						    }
						}
					});

					laypage({
					  	cont: 'layPage2',
						skin: '#4A4A4A',
						groups: 5,
						prev: "<img src='/images/new/prev.png'>",
					  	next: "<img src='/images/new/next.png'>",
					  	first: '1',
						last: <?php echo $allPages; ?>,
						pages: <?php echo $allPages; ?>,
						curr: function(){
							var page = location.search.match(/page=(\d+)/);
						    return page ? page[1] : 1;
					  	}(), 
						jump: function(e, first){
						    if(!first){
							    location.href = '?page='+e.curr;
						    }
						}
					});
				</script>
				<?php
			}
			echo "</div> <!-- product -->";
		}
	}

?>