<?php
	$page['image']['thumb']['x'] = '125';
	$page['image']['thumb']['y'] = '125';
	$page['image']['quality']    = '80';
	$page['image']['max_size']   = '4096000';

	$page['thumb']  = 'download/page_image/thumb/';
	$page['full']   = 'download/page_image/full/';
	$page['buffer'] = 'download/page_image/buffer/';

#	$setup['width']          = "125";
#	$setup['height']         = "125";
#	$setup['buffer']['url']  = $site['url']['full']."download/site/buffer/";
#	$setup['buffer']['path'] = $site['url']['path']."download/site/thumb/";
#	$setup['thumb']['url']   = $site['url']['full']."download/site/buffer/";
#	$setup['thumb']['path']  = $site['url']['path']."download/site/thumb/";

	include '/home/hosting/template-v0d/admin_page_thumb.php';
?>