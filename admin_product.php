<?php
	
	$table['product']['productId']['searchHeading']		= 'ID';
	$table['product']['productDateUpdate']['searchHeading']		= 'Update Date';
	$table['product']['productCategory']['display']		= 'Y';
	$table['product']['productCategory']['searchHeading']	= 'Status';
	$table['product']['productPart']['display']		= 'Y';
	$table['product']['productPart']['search']		= 'Y';
	$table['product']['productPart']['searchHeading']	= 'Stock No';
	$table['product']['productTitle']['display']		= 'Y';
	$table['product']['productTitle']['search']		= 'Y';
	$table['product']['productTitle']['searchHeading']	= 'Title';
	$table['product']['productDescription']['display']	= 'Y';
	$table['product']['productDescription']['search']	= 'Y';
	$table['product']['productPrice1']['display']		= 'Y';
	$table['product']['productPrice1']['searchHeading']	= 'Unit';
	$table['product']['productPrice2']['display']		= 'Y';
	$table['product']['productPrice2']['searchHeading']	= 'Was';
	$table['product']['productStock']['display']		= 'Y';
	$table['product']['productInner']['display']		= 'Y';
	$table['product']['productInner']['searchHeading']	= 'Inner Qty';
	$table['product']['productCarton']['display']		= 'Y';
	$table['product']['productCarton']['searchHeading']	= 'Carton Qty';
	$table['product']['productFlag']['insert']		= 'N';
	$table['product']['productFlag']['update']		= 'NULL';
	$table['product']['productAdmin']['insert']		= $_SESSION['member']['memberId'];
	$table['product']['productAdmin']['update']		= $_SESSION['member']['memberId'];
	$table['product']['productDateCreate']['insert']	= 'NOW';
	$table['product']['productDateCreate']['update']	= 'NULL';
	$table['product']['productDateUpdate']['insert']	= 'NOW';
	$table['product']['productDateUpdate']['update']	= 'NOW';

	$layout  = $table['product'];
	$tableId = $site['database']['product'];
	
	$input['product_image']['productImageOrder']		= array('heading'=>'Order',       'size'=>'1');
	$input['product_image']['productImageTitle']		= array('heading'=>'Title',       'size'=>'55');
	$input['product_image']['productImageDescription']	= array('heading'=>'Description', 'size'=>'55');
	$input['product_image']['productImageFile']		= array('heading'=>'File',        'size'=>'41');

	$table['product_image'][] = 'productImageOrder';
	$table['product_image'][] = 'productImageTitle';
	$table['product_image'][] = 'productImageDescription';
	$table['product_image'][] = 'productImageFile';

	$page['product_image']['qty']		= '6';
	$page['product_image']['thumb']['x']	= '75';
	$page['product_image']['thumb']['y']	= '75';
	$page['product_image']['full']['x']	= '400';
	$page['product_image']['full']['y']	= '400';
	$page['product_image']['quality']	= '90';
	$page['product_image']['max_size']	= '5096000';

#	include '/home/hosting/template-v0d/admin_product.php';
	include 'admin_product_v0d-template.php';
?>