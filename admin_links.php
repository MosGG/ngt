<?php
	$page['heading'] = "Links Library";

	$input['link']['linkCategory']    = array('heading'=>'Category:',    'size'=>'94');
	$input['link']['linkName']        = array('heading'=>'Link Name:',   'size'=>'94');
#	$input['link']['linkDescription'] = array('heading'=>'Details: <br /><font size="1">Use - at start of each line</font>');
	$input['link']['linkDescription'] = array('heading'=>'Details:',     'size'=>'59');
	$input['link']['linkUrl']         = array('heading'=>'Web Address:', 'size'=>'94');

	$table['link'][] = 'linkCategory';
	$table['link'][] = 'linkName';
	$table['link'][] = 'linkDescription';
	$table['link'][] = 'linkUrl';

	$page['link']['image']['x'] = '125';
	$page['link']['image']['y'] = '125';
	$page['link']['quality']    = '80';
	$page['link']['max_size']   = '4096000';

	include '/home/hosting/template-v0d/admin_links.php';
?>