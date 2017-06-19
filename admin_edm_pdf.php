<?php
#	$page['heading'] = "PDF Library";

	$input['pdf']['pdfCategory']  = array('heading'=>'Category',  'size'=>'50');
	$input['pdf']['pdfTitle']     = array('heading'=>'Title',     'size'=>'50');
	$input['pdf']['pdfHyperlink'] = array('heading'=>'Hyperlink', 'size'=>'60');
	$input['pdf']['pdfFile']      = array('heading'=>'File',      'size'=>'50');

	$table['pdf'][] = 'pdfCategory';
	$table['pdf'][] = 'pdfTitle';
	$table['pdf'][] = 'pdfHyperlink';
	$table['pdf'][] = 'pdfFile';

	$page['pdf']['max_size'] = '8096000';

	include '/home/hosting/template-v0d/admin_edm_pdf.php';
?>