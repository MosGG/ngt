<?php
	if ($_GET['edit']) {
		$_SESSION['pageedit'] = $_GET['edit'];
	}

	$pageField['pageParent']		= array('access'=>'90', 'size'=> '3',	'heading'=>'Parent');
	$pageField['pageOrder']			= array('access'=>'50', 'size'=> '3',	'heading'=>'Order');
	if ($_SESSION['pageedit'] == '1') {
		$pageField['pageUrl']		= array('access'=>'90', 'size'=> '40',	'heading'=>'Url');
	} else {
		$pageField['pageUrl']		= array('access'=>'50', 'size'=> '40',	'heading'=>'Url');
	}
	$pageField['pageAdmin']			= array('access'=>'90', 'size'=> '3',	'heading'=>'Admin Page (Y)');
	$pageField['pageMenu']			= array('access'=>'50', 'size'=> '40',	'heading'=>'Sub Menu');
	$pageField['pageMenuH']			= array('access'=>'50', 'size'=> '30',	'heading'=>'H Menu');
	$pageField['pageMenuHAccess']		= array('access'=>'90', 'size'=> '3',	'heading'=>'H Menu A');
	$pageField['pageMenuV']			= array('access'=>'50', 'size'=> '30',	'heading'=>'V Menu');
	$pageField['pageMenuVAccess']		= array('access'=>'90', 'size'=> '3',	'heading'=>'V Menu A');
	$pageField['pageMetaTitle']		= array('access'=>'50', 'size'=> '95',	'heading'=>'Meta Title');
	$pageField['pageMetaKeywords']		= array('access'=>'50', 'size'=> '95',	'heading'=>'Meta Keywords');
	$pageField['pageMetaDescription']	= array('access'=>'50', 'size'=> '95',	'heading'=>'Meta Description');
	$pageField['pageTemplate']		= array('access'=>'90', 'size'=> '20',	'heading'=>'Template File');
	$pageField['pageFile']			= array('access'=>'90', 'size'=> '20',	'heading'=>'Page File');
#	$pageField['pageLink']			= array('access'=>'50', 'size'=> '60',	'heading'=>'Link');
	$pageField['pageText']			= array('access'=>'50', 'size'=> 'text','heading'=>'Content', 'height'=>'400');

	$pageInput['insert']['size']		= '60';

	include 'template-v0d/admin_page.php';
?>