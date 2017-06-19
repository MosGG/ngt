<?php
	$page['heading'] = "Page Gallery";

	$input['page_gallery']['pageGalleryCategory']    = array('heading'=>'Category',    'size'=>'54');
	$input['page_gallery']['pageGalleryOrder']       = array('heading'=>'Order',       'size'=>'2');
#	$input['page_gallery']['pageGalleryTitle']       = array('heading'=>'Title',       'size'=>'54');
	$input['page_gallery']['pageGalleryDescription'] = array('heading'=>'Description', 'size'=>'54');
	$input['page_gallery']['pageGalleryFile']        = array('heading'=>'File',        'size'=>'42');

	$table['page_gallery'][] = 'pageGalleryCategory';
	$table['page_gallery'][] = 'pageGalleryOrder';
	$table['page_gallery'][] = 'pageGalleryTitle';
	$table['page_gallery'][] = 'pageGalleryDescription';
	$table['page_gallery'][] = 'pageGalleryFile';

	$page['page_gallery']['display']    = '5';
#	$page['page_gallery']['thumb']['u'] = 'Unique'; # Unique Thumb
	$page['page_gallery']['thumb']['x'] = '125';
	$page['page_gallery']['thumb']['y'] = '94';
	$page['page_gallery']['full']['x']  = '500';
	$page['page_gallery']['full']['y']  = '500';
	$page['page_gallery']['quality']    = '80';
	$page['page_gallery']['max_size']   = '8096000';
	
	include '/home/hosting/template-v0d/admin_page_gallery.php';
?>