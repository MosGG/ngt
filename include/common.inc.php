<?php
	session_start();

	##############################################################
	# DEVELOPED BY UBC WEB DESIGN - ubcwebdesign.com.au 
	##############################################################

	##############################################################
	# SITE INFORMATION 
	##############################################################

	$site['email']['development']		= "janelle@ubcwebdesign.com.au";

	$site['company']['name']		= "New Global Trading";
	$site['company']['web']			= 'www.newglobalmel.com.au';
	$email					= "sales@newglobalmel.com.au";
	$site['company']['email']['to']		= $email;
	$site['company']['email']['from']	= "From: ".$site['company']['name']."<".$email.">";
	$site['company']['email']['fromH']	= $site['company']['email']['from']."\r\nX-Priority: 1 (Highest)";
	$site['company']['email']['enabled']	= "y";
	$site['company']['footer']		= 'New Global Trading Pty. Ltd.';

	$site['company']['manager']		= 'Stanley Shi';
	$site['company']['managerT']		= 'Manager';
	$site['company']['phone']		= '(03) 9563 2655';
	$site['company']['fax']			= '(03) 9563 2656';
	$site['company']['address']		= '4 Croft St,<br />Oakleigh Vic 3166';
	$site['company']['address2']		= '6/25 Alfred Rd,<br />Chipping Norton NSW 3166';
#	$site['company']['abn']			= 'xx xxx xxx xxx';

#	$site['timeZone']			= 0;        # 54000 60*60*15 +15 Hrs Difference to Dallas Texas

	$site['copyright']			= "2005";   # Copyright Date

#	$site['sms']['number']			= '0407568871@sms.smsglobal.com.au';  #change mobile number to customers number

	$site['ssl']				= "n";

	$site['contact']['message']		= '';
	$site['contact']['url']			= 'http://'.$site['company']['web'].'/contact';
	$site['contact']['link']		= 'Contact '.$site['company']['name'];

	$site['social']['facebook']		= '';
	$site['social']['twitter']		= '';
	$site['social']['youtube']		= '';

	$site['template']['header']['image']		= 'header.png';

	$site['template']['recommend']['header']	= 'email-header.jpg';
	$site['template']['recommend']['image']		= 'recommendimage.jpg';
	$site['template']['recommend']['blurb']		= '';

	$site['template']['shadow']['offset']		= '29';
	
	$site['template']['subpage']['columns']		= '5';
	$site['template']['subpageadmin']['columns']	= '5';
	$site['template']['page_gallery']['display']	= '4';
	$site['template']['page_gallery']['title']	= 'N';

	$site['template']['price']['field']		= 'productPrice1';
	$site['template']['price']['decimal']		= '2';

	$table['product']['productId']		= array('type'=>'Index', 'heading'=>'Index');
	$table['product']['productTitle']	= array('type'=>'I', 'heading'=>'Title',	'size'=>'50');
	$table['product']['productCategory']	= array('type'=>'D', 'heading'=>'Status',	'item'=>array('In Stock', 'Coming Soon', 'Out of Stock'));
	$table['product']['productPart']	= array('type'=>'I', 'heading'=>'Part No.',	'size'=>'50');
	$table['product']['productDescription']	= array('type'=>'T', 'heading'=>'Description',	'size'=>'50');
	$table['product']['productPrice1']	= array('type'=>'I', 'heading'=>'Unit Price',	'size'=>'20');
	$table['product']['productPrice2']	= array('type'=>'I', 'heading'=>'Was Price',	'size'=>'20');
	$table['product']['productStock']	= array('type'=>'I', 'heading'=>'Stock Level',	'size'=>'20');
	$table['product']['productInner']	= array('type'=>'I', 'heading'=>'Inner Qty',	'size'=>'20');
	$table['product']['productCarton']	= array('type'=>'I', 'heading'=>'Carton Qty',	'size'=>'20');
	$table['product']['productFlag']	= array('type'=>'I', 'heading'=>'Flag',		'size'=>'20');
	$table['product']['productAdmin']	= array('type'=>'I', 'heading'=>'Admin',	'size'=>'20');
	$table['product']['productDateCreate']	= array('type'=>'d', 'heading'=>'Create Date',	'size'=>'20');
	$table['product']['productDateUpdate']	= array('type'=>'d', 'heading'=>'Update Date',	'size'=>'20');	

	$table['order']['orderId']		= array('type'=>'Index', 'heading'=>'Index');
	$table['order']['customer']		= array('type'=>'H', 'heading'=>'Customer Details');
	$table['order']['orderBusiness']	= array('type'=>'I', 'heading'=>'Business',		'size'=>'54');
	$table['order']['orderNameF']		= array('type'=>'I', 'heading'=>'First Name',		'size'=>'54');
	$table['order']['orderNameS']		= array('type'=>'I', 'heading'=>'Surname',		'size'=>'54');
	$table['order']['orderPhone']		= array('type'=>'I', 'heading'=>'Telephone',		'size'=>'54');
	$table['order']['orderEmail']		= array('type'=>'I', 'heading'=>'Email',		'size'=>'54');
	$table['order']['orderAddress']		= array('type'=>'I', 'heading'=>'Address',		'size'=>'54');
	$table['order']['orderSuburb']		= array('type'=>'I', 'heading'=>'City/Town',		'size'=>'30');
	$table['order']['orderState']		= array('type'=>'D', 'heading'=>'State',		'item'=>array(''=>'Please Select', 'VIC'=>'Victoria', 'NSW'=>'New South Wales', 'ACT'=>'ACT', 'QLD'=>'Queensland', 'NT'=>'Northern Territory', 'WA'=>'Western Australia', 'SA'=>'South Australia', 'TAS'=>'Tasmania'));
	$table['order']['orderPostcode']	= array('type'=>'I', 'heading'=>'Postcode',		'size'=>'10');
	$table['order']['orderComments']	= array('type'=>'T', 'heading'=>'Comments',		'cols'=>'40', 'rows'=>'5');
	$table['order']['orderPaymentType']	= array('type'=>'D', 'heading'=>'Payment Type',		'item'=>array(''=>'Please Select', 'DD'=>'Direct Deposit', 'CH'=>'Cheque/Money Order'));
#	$table['order']['card']			= array('type'=>'H', 'heading'=>'Credit Card Details');
#	$table['order']['orderCardType']	= array('type'=>'D', 'heading'=>'Card Type',		'item'=>array(''=>'Please Select', 'Visa'=>'Visa', 'Mastercard'=>'Mastercard', 'Amex'=>'American Express'));
#	$table['order']['orderCardNumber']	= array('type'=>'I', 'heading'=>'Card Number',		'size'=>'54');
#	$table['order']['orderCardName']	= array('type'=>'I', 'heading'=>'Name on Card',		'size'=>'54');
#	$table['order']['orderCardExpiryM']	= array('type'=>'I', 'heading'=>'Month',		'size'=>'4');
#	$table['order']['orderCardExpiryY']	= array('type'=>'I', 'heading'=>'Year',			'size'=>'4');
#	$table['order']['orderCardCVV']		= array('type'=>'I', 'heading'=>'CVV',			'size'=>'6');
	$table['order']['summary']		= array('type'=>'H', 'heading'=>'Order Summary');
	$table['order']['orderOrder']		= array('type'=>'O', 'heading'=>'Order');
	$table['order']['orderPrice']		= array('type'=>'M', 'heading'=>'Price');
#	$table['order']['orderFreight']		= array('type'=>'M', 'heading'=>'Freight');
	$table['order']['orderTotal']		= array('type'=>'M', 'heading'=>'Total');
	$table['order']['orderDateCreate']	= array('type'=>'d', 'heading'=>'Create Date');
	$table['order']['orderDateUpdate']	= array('type'=>'d', 'heading'=>'Update Date');
	$table['order']['orderUserUpdate']	= array('type'=>'I', 'heading'=>'User Update',		'size'=>'10');
	$table['order']['orderStatus']		= array('type'=>'D', 'heading'=>'Status',		'item'=>array('%'=>'Display All', 'N'=>'New', 'P'=>'Processed', 'C'=>'Cancelled'));
#	$table['order']['buttonInsert']		= array('type'=>'B', 'heading'=>'Submit Details');

#	$table['freight']['100']		= array('Within Australia'=>'1.00');
#	$table['freight']['250']		= array('Within Australia'=>'5.00');
#	$table['freight']['500']		= array('Within Australia'=>'6.50');
#	$table['freight']['1000']		= array('Within Australia'=>'10.00');
#	$table['freight']['3000']		= array('Within Australia'=>'13.00');
#	$table['freight']['8000']		= array('Within Australia'=>'22.00');
#	$table['freight']['10000']		= array('Within Australia'=>'25.00');
#	$table['freight']['20000']		= array('Within Australia'=>'30.00');

	$table['membership']['mmemberId']		= array('type'=>'Index', 'heading'=>'Index');
#	$table['membership']['mmemberType']		= array('type'=>'D', 'heading'=>'Membership Type',	'item'=>array('%'=>'Display All', ''=>'Not in a List', 'W'=>'Wholesaler', 'C'=>'Member', 'T'=>'Test Mail List', 'V'=>'VIC', 'N'=>'NSW', 'A'=>'TAS', 'M'=>'WA', 'Q'=>'QLD', 'S'=>'SA', 'Z'=>'ACT', 'D'=>'NT', 'O'=>'OVERSEAS'));
	$table['membership']['mmemberType']		= array('type'=>'D', 'heading'=>'Membership Type',	'item'=>array('%'=>'Display All', ''=>'Not in a List', 'T'=>'Test Mail List', 'V'=>'VIC', 'N'=>'NSW', 'A'=>'TAS', 'M'=>'WA', 'Q'=>'QLD', 'S'=>'SA', 'Z'=>'ACT', 'D'=>'NT', 'O'=>'OVERSEAS'));
	$table['membership']['mmemberBusiness']		= array('type'=>'I', 'heading'=>'Business',		'size'=>'54');
	$table['membership']['mmemberABN']		= array('type'=>'I', 'heading'=>'ABN',			'size'=>'20');
	$table['membership']['mmemberNameF']		= array('type'=>'I', 'heading'=>'First Name',		'size'=>'54');
	$table['membership']['mmemberNameS']		= array('type'=>'I', 'heading'=>'Surname',		'size'=>'54');
	$table['membership']['mmemberEmail']		= array('type'=>'I', 'heading'=>'Email',		'size'=>'54');
	$table['membership']['mmemberPassword']		= array('type'=>'I', 'heading'=>'Password',		'size'=>'54');
	$table['membership']['mmemberAddress']		= array('type'=>'I', 'heading'=>'Address',		'size'=>'54');
	$table['membership']['mmemberSuburb']		= array('type'=>'I', 'heading'=>'Suburb',		'size'=>'54');
	$table['membership']['mmemberState']		= array('type'=>'D', 'heading'=>'State',		'item'=>array(''=>'Please Select', 'VIC'=>'Victoria', 'NSW'=>'New South Wales', 'ACT'=>'ACT', 'QLD'=>'Queensland', 'NT'=>'Northern Territory', 'WA'=>'Western Australia', 'SA'=>'South Australia', 'TAS'=>'Tasmania'));
	$table['membership']['mmemberPostcode']		= array('type'=>'I', 'heading'=>'Postcode',		'size'=>'54');
	$table['membership']['mmemberPhone']		= array('type'=>'I', 'heading'=>'Phone',		'size'=>'54');

	## Entries for bulk SMS functionality.
	$table['membership']['mmemberMobilePhone']	= array('type'=>'I', 'heading'=>'Mobile Phone',		'size'=>'54');
	$table['membership']['mmemberSMSStatus']	= array('type'=>'D', 'heading'=>'SMS Status',		'item'=>array('%'=>'', 'A'=>'Active', 'N'=>'Non-Active', 'O'=>'Opted Out'));
	$table['membership']['mmemberSMSList']		= array('type'=>'D', 'heading'=>'SMS List',		'item'=>array('%'=>'', '1'=>'Local SMS List', '2'=>'Retailers', '3'=>'NSW List'));

	$table['membership']['mmemberDiscount']		= array('type'=>'I', 'heading'=>'Discount',		'size'=>'10');
	$table['membership']['mmemberCaptcha']		= array('type'=>'c', 'heading'=>'Security Code',	'size'=>'25', 'input'=>'Enter the Security Code', 'background'=>'#cdcece', 'forground'=>'#000000');
	$table['membership']['mmemberSource']		= array('type'=>'D', 'heading'=>'Source',		'item'=>array('M'=>'Manual', 'B'=>'Bulk Load', 'W'=>'Web'));
	$table['membership']['mmemberDateCreate']	= array('type'=>'d', 'heading'=>'Create Date',		'size'=>'10');
	$table['membership']['mmemberDateUpdate']	= array('type'=>'d', 'heading'=>'Update Date',		'size'=>'10');
	$table['membership']['mmemberUserUpdate']	= array('type'=>'I', 'heading'=>'User Update',		'size'=>'10');
	$table['membership']['mmemberStatus']		= array('type'=>'D', 'heading'=>'Status',		'item'=>array('%'=>'Display All', 'N'=>'Not Validated', 'A'=>'Active', 'S'=>'Suspended', 'C'=>'Cancelled'));
	$table['membership']['buttonInsert']		= array('type'=>'B', 'heading'=>'Insert');
	$table['membership']['buttonUpdate']		= array('type'=>'B', 'heading'=>'Update');
	$table['membership']['buttonSearch']		= array('type'=>'B', 'heading'=>'Search');
	$table['membership']['buttonCancel']		= array('type'=>'B', 'heading'=>'Cancel');

	##############################################################
	# EDM translation options. 
	##############################################################


	$mail['translate']['nameF']  = array('heading'=>'= Recipients First Name',  'field'=>'mmemberNameF');
	$mail['translate']['nameS']  = array('heading'=>'= Recipients Surname',     'field'=>'mmemberNameS');
	$mail['translate']['email']  = array('heading'=>'= Recipients Email',       'field'=>'mmemberEmail');


	$site['admin']['url']				= 'ace';

	$contactType[] = 'General Enquiry';

	##############################################################
	# DATABASE SETTINGS 
	##############################################################

	$dbUser     = 'root';//'newg_ubc';	# database user
	$dbPassword = '';//'eureka2009';	# database password
	$dbDatabase = 'newg_hosting';	# database definition file
	$dbServer   = 'localhost';	# database server -- usually localhost, but one never knows

	// MySQLi Connection
	include "template-v0d/global-sqli.php";

	$dbPrefix				= "`ubc-";
	$site['database']['log']		= $dbPrefix."log`";
	$site['database']['logBrowser']		= $dbPrefix."log_browser`";
	$site['database']['logIp']		= $dbPrefix."log_ip`";
	$site['database']['logPage']		= $dbPrefix."log_page`";
	$site['database']['logReferer']		= $dbPrefix."log_referer`";
	$site['database']['logSession']		= $dbPrefix."log_session`";
	$site['database']['logUrl']		= $dbPrefix."log_url`";
	$site['database']['member']		= $dbPrefix."member`";
	$site['database']['contact']		= $dbPrefix."contact`";
	$dbPrefix				= "`newg_";
	$site['database']['page']		= $dbPrefix."page`";
	$site['database']['page-archive']	= $dbPrefix."page_archive`";
	$site['database']['page-gallery']	= $dbPrefix."page_gallery`";
	$site['database']['image']		= $dbPrefix."image`";
	$site['database']['pdf']		= $dbPrefix."pdf`";
	$site['database']['link']		= $dbPrefix."link`";
	$site['database']['product']		= $dbPrefix."product`";
	$site['database']['product-image']	= $dbPrefix."product_image`";
	$site['database']['product-link']	= $dbPrefix."product_link`";
	$site['database']['order']		= $dbPrefix."order`";
	$site['database']['membership']		= $dbPrefix."edm_member`";
	$site['database']['edm-history']	= $dbPrefix."edm_history`";
	$site['database']['edm-image']		= $dbPrefix."edm_image`";
	$site['database']['edm-pdf']		= $dbPrefix."edm_pdf`";
	$site['database']['edm-sent']		= $dbPrefix."edm_sent`";
	$site['database']['sms']		= $dbPrefix."sms`";
	
	$site['id']				= "64"; # New Global Mel

	##############################################################
	# GLOBAL SETTINGS 
	##############################################################

	include 'template-v0d/global.php';

	$site['sms']['email']			= "From: ".$site['company']['name']."<".$static['sms']['email'].">";

	if ($static['mail']['enabled'] == 'n') {
		$site['company']['email']['enabled'] = 'n';
	}

	if ($static['server']['name'] == 'pluto.ubcserver.com') {
		if ($site['email']['development']) {
			$site['company']['email']['to'] = $site['email']['development'];
		} else {
			$site['company']['email']['to'] = $static['mail']['dev'];
		}
	}

	if ($static['server']['name'] == 'pluto.ubcserver.com') {
		$site['url']['id']		= "/ubc/2014/newg/14_01_08/";
		$site['url']['full']		= $static['server']['url'].substr($site['url']['id'],1);
		$site['url']['path']		= $static['server']['path'].substr($site['url']['id'],1);
	} else {
		$site['url']['id']		= "/"; # Live
		$site['url']['url']		= "www.newglobalmel.com.au";
		if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) {
			$static['server']['url'] = "https://";
		}
		$site['url']['full']		= $static['server']['url'].$site['url']['url']."/";
		$site['url']['path']		= "C:/Users/Lmz/Documents/Cheee/cheee website project/NewGlobal/newglobal/";//$static['server']['path']."newg/public_html/";

#		$site['url']['id']		= "~newg/"; # No Domain
#		$site['url']['full']		= $static['server']['url']."venus.ubcserver.com/".$site['url']['id'];
#		$site['url']['path']		= $static['server']['path'].substr($site['url']['id'],1)."public_html/";
	}

	if ($static['server']['name'] == 'david.ubcserver.com') {
		$site['url']['id']		= "/2014/newg/14_01_08/";
		$site['url']['full']		= $static['server']['url'].substr($site['url']['id'],1);
		$site['url']['path']		= $static['server']['path'].substr($site['url']['id'],1);
	}

	$site['url']['switch'][]		= "add";
	$site['url']['switch'][]		= "display";
	$site['url']['switch'][]		= "item";
	$site['url']['switch'][]		= "action";
	$site['url']['switch'][]		= "id";
	$site['url']['switch'][]		= "email";
	$site['url']['switch'][]		= "edmid";

	$site['path']['image']['buffer']	= "content/image/buffer/";
	$site['path']['image']['thumb']		= "content/image/thumb/";
	$site['path']['image']['full']		= "content/image/full/";

	$site['path']['page']['buffer']		= "content/page/buffer/";
	$site['path']['page']['thumb']		= "content/page/thumb/";
	$site['path']['page']['full']		= "content/page/full/";

	$site['path']['product']['buffer']	= "content/product/buffer/";
	$site['path']['product']['thumb']	= "content/product/thumb/";
	$site['path']['product']['full']	= "content/product/full/";
	$site['path']['product']['csv']		= "content/product/csv/";

	$site['path']['link']['buffer']		= "content/link/buffer/";
	$site['path']['link']['image']		= "content/link/image/";

	$site['path']['page_gallery']['buffer']	= "content/page_gallery/buffer/";
	$site['path']['page_gallery']['thumb']	= "content/page_gallery/thumb/";
	$site['path']['page_gallery']['full']	= "content/page_gallery/full/";
	
	$site['path']['edm']['image']['buffer']	= "content/edm/image/buffer/";
	$site['path']['edm']['image']['thumb']	= "content/edm/image/thumb/";
	$site['path']['edm']['image']['full']	= "content/edm/image/full/";
	$site['path']['edm']['pdf']		= "content/edm/pdf/";
	$site['path']['edm']['template']	= "content/edm/template/";

	$site['path']['pdf']			= "content/pdf/";

	##############################################################
	# FUNCTIONS 
	##############################################################

	$site['url']['decode'] = url_decode();

	#######################################################################
	# Get Minimum Order Size Available (Unit, Inner or Cart)
	#######################################################################

	function getmin($productId) {
		global $site;
		global $p;
		global $fallback;

		$sql  = "SELECT * FROM ".$site['database']['product']." ";
		$sql .= "WHERE `productId` = '".$productId."' ";
		$result = sql_exec($sql);
		$product = $result->fetch_assoc();

		$p = "";
		$fallback = "";
		$minorder = "";

		if ($product['productCarton'] && !$product['productInner']) {
			$minorder = "Carton";
		} else if ($product['productInner']) {
			$minorder = "Inner";
		} else {
			$minorder = "Unit";
		}

		return $minorder;
	}
?>