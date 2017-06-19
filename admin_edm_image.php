<?php
	$page['heading'] = "EDM Image Library";

	$input['image']['imageCategory']    = array('heading'=>'Category',    'size'=>'50');
#	$input['image']['imageTitle']       = array('heading'=>'Title',       'size'=>'50');
	$input['image']['imageDescription'] = array('heading'=>'Description', 'size'=>'100');
	$input['image']['imageSize']        = array('heading'=>'Size',        'item'=>array('100'=>'Small (100x100)', '150'=>'Medium (150x150)', '250'=>'Large (250x250)',  '500'=>'Banner (500x200)', '1'=>'Actual Size'));
#	$input['image']['imageBorder']      = array('heading'=>'Border',      'item'=>array('None','1','2','3','Shadow'));
	$input['image']['imageAlignment']   = array('heading'=>'Alignment',   'item'=>array('None','Left','Center','Right'));
	$input['image']['imageLink']        = array('heading'=>'Hyperlink',   'size'=>'100');
	$input['image']['imageLinkTrack']   = array('heading'=>'Record Link', 'item'=>array('y'=>'Yes', 'n'=>'No'));	
	$input['image']['imageFile']        = array('heading'=>'File',        'size'=>'86');

	$table['image'][] = 'imageCategory';
#	$table['image'][] = 'imageTitle';
	$table['image'][] = 'imageDescription';
	$table['image'][] = 'imageSizeX';
	$table['image'][] = 'imageSizeY';
#	$table['image'][] = 'imageBorder';
	$table['image'][] = 'imageAlignment';
	$table['image'][] = 'imageLink';
	$table['image'][] = 'imageLinkTrack';
	$table['image'][] = 'imageFile';

	$page['image']['thumb']['x'] = '125';
	$page['image']['thumb']['y'] = '94';
	$page['image']['quality']    = '80';
	$page['image']['max_size']   = '8096000';

	include '/home/hosting/template-v0d/admin_edm_image.php';
?>