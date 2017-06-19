<?php
	$table['type']['e'] = "Email";

	$table['list'] = $table['membership']['mmemberType']['item'];
	unset($table['list']['%']);

	$input['message']['template']['heading']   = 'Template';
	$input['message']['template']['item']['1'] = array('heading'=>'Website Template',	'file'=>'template2');
	$input['message']['template']['item']['2'] = array('heading'=>'Plain Template',		'file'=>'template1');
	$input['message']['list']    = array('heading'=>'Message List');
	$input['message']['subject'] = array('heading'=>'Message Subject', 'size'=>'75');
	$input['message']['body']    = array('heading'=>'Message Body',    'height'=>'400');
	$input['message']['text']    	= array('heading'=>'Plain Text Message', 'rows'=>'15', 'cols'=>'85');
	
	$layout  = $table['membership'];
	
	include '/home/hosting/template-v0d/admin_edm_creator_v4.php';
?>