<?php
#	$page['heading'] = 'Order Cart';

#	$message1 = '<p>Message 1</p>';
#	$message2 = '<p>Message 2</p>';
#	$message3 = '<p>Message 3</p>';

#	$emailmessage = '<p>*Email Message</p>';

#	$heading['quantity'] =  $site['admin']['product']['status']['cart'];
#	$heading['image']    =  $site['admin']['product']['status']['image'];
#	$heading['status']   =  $site['admin']['product']['status']['category'];
#	$heading['number']   =  $site['admin']['product']['status']['number'];
#	$heading['title']    =  $site['admin']['product']['status']['title'];
#	$heading['price']    =  $site['admin']['product']['status']['price'];

#	$message['freight'] = "<p align='center'><img src='".$site['url']['full']."images/freight.png' alt='Freight' />&nbsp; <span id='cartmessage'><b>You will be contacted with freight charges prior to shipping.</b></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>";

	if ($site['ssl'] == "y") {
		$message['ssl']  = '<table border="0" align="center">';
		$message['ssl'] .= '<tr>';
		$message['ssl'] .= '<td><img src="'.$site['url']['full'].'images/lock.gif" alt="SSL Secure" /><td>';
		$message['ssl'] .= '<td><span id="ssl">Your payment details are protected and encrypted by SSL technology.<br />Your privacy and security is guaranteed.</span><td>';
		$message['ssl'] .= '</tr>';
		$message['ssl'] .= '</table><br />';
	}

	$message['thankyou'] = "<p>Your order is complete. An email will be sent with your order details.</p><p>You will be contacted soon.</p><br />";
#	$message['thankyou'] = "<p>Your order is complete. An email will be sent with your order details.</p><p>You will be contacted soon to be advised of freight costing.</p><br />";

	$productlink = "";
	$message['empty'] = '<p>The Order Cart is currently empty.</p><p>Please take a look at our '.content_converter("[page|products|Products]").'.</p><p>To add an item to the Order Cart, just click on the "Add to Cart" button.</p>';

	$display['cart']['part']['heading']     = 'Item No.';
	$display['cart']['title']['heading']    = 'Title';
	$display['cart']['price']['heading']    = 'Price';
	$display['cart']['quantity']['heading'] = 'Quantity';
	$display['cart']['subPrice']['heading'] = 'Subtotal';

#	$button['cart']['update']['image']      = 'update.jpg';
#	$button['cart']['update']['width']      = '111';
#	$button['cart']['update']['height']     = '20';
#	$button['cart']['checkout']['image']    = 'checkout.jpg';
#	$button['cart']['checkout']['width']    = '111';
#	$button['cart']['checkout']['height']   = '20';
#	$button['cart']['editcart']['image']    = 'editcart.jpg';
#	$button['cart']['editcart']['width']    = '111';
#	$button['cart']['editcart']['height']   = '20';
#	$button['cart']['placeorder']['image']  = 'placeorder.jpg';
#	$button['cart']['placeorder']['width']  = '111';
#	$button['cart']['placeorder']['height'] = '20';

	if ($_SESSION['membership'] && !$_SESSION['submit']) {
		$_SESSION['submit']['orderBusiness']	= $_SESSION['membership']['mmemberBusiness'];
		$_SESSION['submit']['orderNameF']	= $_SESSION['membership']['mmemberNameF'];
		$_SESSION['submit']['orderNameS']	= $_SESSION['membership']['mmemberNameS'];
		$_SESSION['submit']['orderPhone']	= $_SESSION['membership']['mmemberPhone'];
		$_SESSION['submit']['orderEmail']	= $_SESSION['membership']['mmemberEmail'];
		$_SESSION['submit']['orderAddress']	= $_SESSION['membership']['mmemberAddress'];
		$_SESSION['submit']['orderSuburb']	= $_SESSION['membership']['mmemberSuburb'];
		$_SESSION['submit']['orderState']	= $_SESSION['membership']['mmemberState'];
		$_SESSION['submit']['orderPostcode']	= $_SESSION['membership']['mmemberPostcode'];
	}

	$table['order']['customer']['display']          = 'Y';
	$table['order']['orderBusiness']['display']     = 'Y';
	$table['order']['orderBusiness']['required']    = 'Y';
	$table['order']['orderNameF']['display']        = 'Y';
	$table['order']['orderNameF']['required']       = 'Y';
	$table['order']['orderNameS']['display']        = 'Y';
	$table['order']['orderNameS']['required']       = 'Y';
	$table['order']['orderPhone']['display']        = 'Y';
	$table['order']['orderPhone']['required']       = 'Y';
	$table['order']['orderEmail']['display']        = 'Y';
	$table['order']['orderEmail']['required']       = 'Y';
	$table['order']['orderAddress']['display']      = 'Y';
	$table['order']['orderAddress']['required']     = 'Y';
	$table['order']['orderSuburb']['display']       = 'Y';
	$table['order']['orderSuburb']['required']      = 'Y';
	$table['order']['orderState']['display']        = 'Y';
	$table['order']['orderState']['required']       = 'Y';
	$table['order']['orderPostcode']['display']     = 'Y';
	$table['order']['orderPostcode']['required']    = 'Y';
	$table['order']['orderComments']['display']     = 'Y';
	$table['order']['orderPaymentType']['display']  = 'Y';
	$table['order']['orderPaymentType']['required'] = 'Y';
#	$table['order']['orderPrice']['display']        = 'Y';
#	$table['order']['orderFreight']['display']      = 'Y';
#	$table['order']['orderTotal']['display']        = 'Y';
	$table['order']['orderDateCreate']['insert']    = 'NOW';
	$table['order']['orderDateUpdate']['insert']    = 'NOW';
	$table['order']['orderUserUpdate']['insert']    = $_SESSION['member']['memberId'];
	$table['order']['orderStatus']['insert']        = 'N';
#	$table['order']['buttonInsert']['display']      = 'Y';

	$layout = $table['order'];

	echo '<div id="form">';
	include 'include/cart.tpl.php';
	echo '</div>';
?>