<?php
#################################################################################################
# Head                                                                                          #
#################################################################################################

	echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>";
	echo "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>";
		echo "<head>";
			echo "<title>".$page['pageMetaTitle']."</title>";
			echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
			if ($page['pageMetaKeywords']) {
				echo "<meta content='".$page['pageMetaKeywords'].", ".$site['company']['name']."' name='keywords' />";
			}
			if ($page['pageMetaDescription']) {
				echo "<meta content='".$page['pageMetaDescription']."' name='description' />";
			}
			echo "<meta content='".$static['author']['name']."' name='author' />";
			echo "<meta content='".$site['company']['name']." - UBC Copyright &copy; ".$site['copyright']."' name='copyright' />";
			echo "<meta name='robots' content='index,follow' />";
			echo "<link rel='shortcut icon' href='".$site['url']['full']."images/favicon.ico' />";
			echo "<link rel='stylesheet' href='".$site['url']['full']."include/main.css' />";
			echo "<link rel='stylesheet' href='".$site['url']['full']."include/new.css' />";
			echo "<link rel='stylesheet' href='".$site['url']['full']."include/lightbox.css' type='text/css' media='screen' />";
			echo "<!--[if lte IE 7]>";
				echo "<link rel='stylesheet' type='text/css' href='".$site['url']['full']."include/iemain.css' />";
			echo "<![endif]-->";
			echo "<!--[if lte IE 6]>";
			echo "<link rel='stylesheet' type='text/css' href='".$site['url']['full']."include/ie6main.css' />";
#			echo "<script type='text/javascript' src='".$static['server']['inc']."TransparentPng.js'></script>";
			echo "<![endif]-->";
			echo "<script type='text/javascript' src='".$site['url']['full']."include/jquery-3.2.0.min.js'></script>";
			// if (($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] != 'on')) {

				echo "<script type='text/javascript' src='".$static['server']['inc']."acewebengine-v1.js'></script>";

			// }
			echo "<script type='text/javascript' src='".$site['url']['full']."images/tours/swfObject.js'></script>";
			// echo "<script type='text/javascript' src='".$site['url']['full']."include/prototype.js'></script>";
			// echo "<script type='text/javascript' src='".$site['url']['full']."include/scriptaculous/scriptaculous.js'></script>";
			echo "<script type='text/javascript' src='".$site['url']['full']."include/lightbox.js'></script>";
			echo "<script type='text/javascript' src='".$site['url']['full']."include/jquery.script.js'></script>";
			echo "<script type='text/javascript' src='".$site['url']['full']."include/new.js'></script>";
		echo "</head>";

	ob_flush();
	flush();

#################################################################################################
# Body                                                                                          #
#################################################################################################
	echo "<body>";
		echo "<div id='container'>";
			echo "<div id='header-container' class='bgblue'>";
				echo "<div id='header'>";
				if (isset($_SESSION['membership'])){

					echo "<div id='loginblock'><a class='hvr-underline-from-left' href='/order_cart'><div id='shopping-bag' class='floatleft'></div>My Shopping Cart</a>|<a class='hvr-underline-from-left' href='javascript:void(0);' onclick='formLogout();'>Log Out</a></div>";

				} else {
					echo "<div id='loginblock'><a class='hvr-underline-from-left' href='/become-a-member'>Register</a>|<a class='hvr-underline-from-left' href='http://www.newglobalmel.com.au/login'>Login</a></div>";
				}
					echo "<a href='/'><img id='header-logo'src='".$site['url']['full']."images/new/ngt.png'/></a>";
					// echo "<img usemap='#logomap' src='".$site['url']['full']."images/".$site['template']['header']['image']."' width='960' height='120' alt='' title='' border='0' />";
					echo "<map name='logomap' id='logomap'>";
						echo "<area shape='rect' coords='5, 29, 235, 100' href='".$site['url']['full']."' alt='".$site['company']['name']."' title='".$site['company']['name']."' />";
					echo "</map>";
					echo "<div id='menuh'><p>".$site['menu']['h']."</p></div><!-- menuh -->";
					echo '<div id="our-product"><a href="/product"><span class="hvr-underline-from-left-blue blue">OUR PRODUCT</span></a></div>';
					echo '<div id="after-out-product"></div>';
					echo '<img id="search-icon" onclick="moveSearchDiv();" src="/images/new/search.png">';
					echo '<form action="/searchresult" method="post" id="search-div"><input type="text" name="query" placeholder="Type your search here..."/></form>';
				echo "</div><!-- header -->";
				echo '	<img id="hand" src="/images/new/landing-hand.png" alt=""/>
						<img id="cloud-header-1" class="cloud-big" src="/images/new/landing-cloud.png" alt=""/>
						<img id="cloud-header-2" class="cloud-mid" src="/images/new/landing-cloud.png" alt=""/>
						<img id="cloud-header-3" class="cloud-sml" src="/images/new/landing-cloud.png" alt=""/>
						<img id="window" src="/images/new/landing-window.png" alt=""/>';
			echo "</div><!-- header -->";
		echo "<div id='wrapper' class='mainbody'>";

#################################################################################################
# Left Container and Vertical Menu                                                              #
#################################################################################################
		echo "<div id='leftcontainer-bg'>";
			echo "<div id='leftcontainer'>";
				echo "<ul id='left-menu-top'>";
					echo "<li class='";
					if (strstr($_SERVER['REQUEST_URI'], "coming-soon")) {
						echo "left-menu-selected'><a href='/product/coming-soon'><span class='hvr-underline-from-left'>New Arrival</span></a></li>";
					} else {
						echo "'><a href='/product/coming-soon'><span class='hvr-underline-from-left-orange'>New Arrival</span></a></li>";
					}
					echo "<li class='";
					if (strstr($_SERVER['REQUEST_URI'], "specials")) {
						echo " left-menu-selected'><a href='/product/specials'><span class='hvr-underline-from-left'>Special Offer</span></a></li>";
					} else {
						echo "'><a href='/product/specials'><span class='hvr-underline-from-left-orange'>Special Offer</span></a></li>";
					}
				echo "</ul>";
				echo "<div id='left-menu-line'></div>";

				echo "".$site['menu']['v']."";
				//echo "<div id='facebook'><a href='http://www.facebook.com/NewGlobalTradingMelbourne' target='_blank'><img src='".$site['url']['full']."images/facebook.jpg' alt='Find us on Facebook link' title='Click to visit New Global Trading on Facebook' width='159' height='36' /></a></div>";
			echo "</div> <!-- leftcontainer -->";
			echo "<div style='width:10px;height:190px'></div>";
		echo "</div>";

#################################################################################################
# Middle Container                                                                              #
#################################################################################################
			echo "<div id='middle'>";;
	if ($page['pageId'] != '1' && !$page['pageAdmin']) {
					echo "<div id='pagetree'><p>".$page['tree']."</p></div>";
	} else {
					echo "<div id='pagetree'><p>&nbsp;</p></div>";
	}
	if ($page['pageId'] != '1')  {
					echo "<div id='pageheading'><h1>".$page['pageMenu']."</h1></div>";
	}
	if(isset($page['pageFile'])) {
		include $page['pageFile'];
		if ($page['pageId'] != '1' && $page['pageFile'] != 'admin.php' && isset($page['subpage'])) {
			echo $page['subpage'];
		}
	}

	// if (isset($page['contactMessage'])) { 
		// echo $page['contactMessage']; 
	// }

			// echo "<div style='clear: both;'></div>";
			echo "</div> <!-- middle -->";
		echo "</div> <!-- wrapper -->";

#################################################################################################
# Footer                                                                                        #
#################################################################################################
			echo "<div id='footer'>";
			echo "<img id='left-cart' src='/images/new/left-cart.png'/>";
				echo "<div id='footercont'>";
				echo "<div id='footet-contact'>
						<div><img src='/images/new/footer-tel.png'/><span>(03) 9563 2655</span></div>
						<div><img src='/images/new/footer-fax.png'/><span>(03) 9563 2656</span></div>
						<div><img src='/images/new/footer-location.png'/><span>4 Croft St, Oakleigh, VIC 3166</span></div>
						<div><img src='/images/new/footer-email.png'/><span id='footet-contact-last'>sales@newglobalmel.com.au</span></div>
					</div>";
					echo "<div id='footright'>";
						echo "<img class='footer-ads' src='".$site['url']['full']."images/logo-umbrella.png' width='47' height='50' alt='' title='' border='0' />";
						echo "<img class='footer-ads' src='".$site['url']['full']."images/logo-globalstar.png' width='110' height='50' alt='' title='' border='0' />";
						echo "<img src='".$site['url']['full']."images/new/ngt-logo.png' width='130' height='107' alt='' title='' border='0' />";
						echo "<img class='footer-ads' src='".$site['url']['full']."images/logo-revell.png' width='105' height='50' alt='' title='' border='0' />";
						echo "<img class='footer-ads' src='".$site['url']['full']."images/logo-kingston.png' width='45' height='50' alt='' title='' border='0' />";
					echo "</div>";
					echo "<div id='footleft'>".$site['pageFooter']."</div>";

				echo "</div>";
			echo "</div> <!-- footer -->";
		echo "</div>"; ## Site Container ##
		if($_SESSION['cart']==NULL){
			$num = 0;
		}
		else{
			$num = count($_SESSION['cart']);
		}
		echo "<script> var number = $num;document.getElementById('shopping-bag').innerHTML = number</script>";
	echo "</body>";
	echo "</html>";

?>
