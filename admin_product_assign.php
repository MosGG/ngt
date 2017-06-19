<?php
	$table['product']['productPart']['searchHeading']   = 'Part No';
	$table['product']['productTitle']['searchHeading']  = 'Title';
	$table['product']['productPrice1']['searchHeading'] = 'Price';

	$layout  = $table['product'];
	$tableId = $site['database']['product'];

	include '/home/hosting/template-v0d/admin_product_assign.php';
?>