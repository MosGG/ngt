<?php
#	$page['heading'] = "Image Library";

	$input['image']['imageCategory']      = array('heading'=>'Category',		      'size'=>'50');
#	$input['image']['imageTitle']         = array('heading'=>'Title',		      'size'=>'50');
	$input['image']['imageDescription']   = array('heading'=>'Description',		      'size'=>'72');
	$input['image']['imageSize']          = array('heading'=>'Size',		      'item'=>array(100=>'Small (100x100)', 150=>'Small/Medium (150x150)', 200=>'Medium (200x200)',300=>'Medium/Large (300x300)',500=>'Large (500x500)', 1=>'Actual'));
	$input['image']['imageBorderSize']    = array('heading'=>'Border',		      'item'=>array('None','1','2','3', 'Shadow'));
	$input['image']['imageBorderColour']  = array('heading'=>'Border Colour',	      'item'=>array('white','black','red','darkred','orange','green','blue','yellow','grey','pink','deeppink','gold','purple','aquamarine'));
	$input['image']['imageBorderPadding'] = array('heading'=>'Border Padding',	      'item'=>array('0','5','10','15','20'));
	$input['image']['imageAlignment']     = array('heading'=>'Alignment',		      'item'=>array('None','Left','Center','Right'));
	$input['image']['imageExtraCSS']      = array('heading'=>'Extra Css Code',	      'size'=>'72');
	$input['image']['imageLink']          = array('heading'=>'Hyperlink',		      'size'=>'72');
	$input['image']['imageLinkNewpage']   = array('heading'=>'Open Link In New Page/Tab', 'item'=>array('Yes','No'));	
	$input['image']['imageFile']          = array('heading'=>'File',		      'size'=>'58');

	$table['image'][] = 'imageCategory';
	$table['image'][] = 'imageTitle';
	$table['image'][] = 'imageDescription';
	$table['image'][] = 'imageSizeX';
	$table['image'][] = 'imageSizeY';
	$table['image'][] = 'imageBorderSize';
	$table['image'][] = 'imageBorderColour';
	$table['image'][] = 'imageBorderPadding';
	$table['image'][] = 'imageAlignment';
	$table['image'][] = 'imageExtraCSS';
	$input['image'][] = 'imageLink';
	$input['image'][] = 'imageLinkNewPage';
	$table['image'][] = 'imageFile';

	$page['image']['thumb']['x'] = '125';
	$page['image']['thumb']['y'] = '94';
	$page['image']['quality']    = '80';
	$page['image']['max_size']   = '4096000';

	$page['thumb']  = 'download/image/thumb/';
	$page['full']   = 'download/image/full/';
	$page['buffer'] = 'download/image/buffer/';

	include '/home/hosting/template-v0d/admin_image.php';
?>