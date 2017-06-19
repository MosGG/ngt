<?php
    	#include 'include/common.inc.php';	

	$table['product']['productCategory']['display']    = 'Y';
	$table['product']['productPart']['display']        = 'Y';
	$table['product']['productTitle']['display']       = 'Y';
	$table['product']['productDescription']['display'] = 'Y';
	$table['product']['productPrice1']['display']      = 'Y';
	$table['product']['productPrice2']['display']      = 'Y';

	$layout  = $table['product'];
	$tableId = $site['database']['product'];
	
	$field_title[] = 'Id';
	$field_title[] = 'Category';
	$field_title[] = 'Part';
	$field_title[] = 'Title';
	$field_title[] = 'Description';
	$field_title[] = 'Price1';
	$field_title[] = 'Price2';

	$field   = '';
	$heading = '';
	
	foreach ($field_title as $data) {
		if ($field) {
			$field   .= ', ';
			$heading .= ',';
		}
		$field .= "`product$data`";
		$heading .= $data;
	}


	$fp = fopen($site['url']['path'].'content/export/product_data.csv', 'w');
	
	$heading .= ", Delete";
	
	fwrite($fp, $heading."\n");

	$sql  = "SELECT $field FROM ".$site['database']['product']." ";
	$sql .= " WHERE `productFlag` <> 'D' ";
	$sql .= " ORDER BY `productTitle` ";
	$result = sql_exec($sql);
	while ($line = $result->fetch_assoc()) {

		$output = "";

		foreach ($line as $key=>$data) {
			
			$data = str_replace('"', '&quot;', $data);
			if ($output) { $output .= ","; }
			$comma = strpos($data, ',');
			if ($comma) { $output .= '"'; }
			$output .= $data;
			if ($comma) { $output .= '"'; }
		}

		$output = str_replace(chr(10), '<br /> ', $output);
		$output = str_replace(chr(13), '<br /> ', $output);
		fwrite($fp, $output."\n");
	}
	fclose($fp);

	echo "<p>Download Product Data <a href='".$site['url']['full']."content/export/product_data.csv'>Click Here</a></p>";

?>