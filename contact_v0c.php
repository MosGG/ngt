<?php
	$page['heading'] = "Contact ".$site['company']['name'];

	$contact['business'] = $site['company']['name'];
#	$contact['abn']      = $site['company']['abn'];
#	$contact['manager']  = $site['company']['manager'];
	$contact['mobile']   = $site['company']['mobile'];
	$contact['phone']    = $site['company']['phone'];
	$contact['fax']      = $site['company']['fax'];
	$contact['postal']   = $site['company']['postal'];
	$contact['address']  = $site['company']['address'];
	$contact['mapref']   = $site['company']['mapref'];
	$contact['email']    = $site['company']['email'];

#	$button['contact']['continue']['image']  = 'continue.jpg';
#	$button['contact']['continue']['width']  = '111';
#	$button['contact']['continue']['height'] = '20';
#	$button['contact']['submit']['image']    = 'submit.jpg';
#	$button['contact']['submit']['width']    = '111';
#	$button['contact']['submit']['height']   = '20';
#	$button['contact']['edit']['image']      = 'editdetails.jpg';
#	$button['contact']['edit']['width']      = '111';
#	$button['contact']['edit']['height']     = '20';

	$ContactMessage = "<p>For further information or enquiries please contact <b>".$site['company']['name']."</b> or <b>Submit</b> your details below and we will contact you promptly</p>";

	$ContactRecommendImage = "recommend.png";
	
#	include '/home/hosting/template-v0d/contact.php';
	include 'include/contact.tpl.php';
?>