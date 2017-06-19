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
			echo "<link rel='stylesheet' href='".$site['url']['full']."include/lightbox.css' type='text/css' media='screen' />";
			echo "<!--[if lte IE 7]>";
				echo "<link rel='stylesheet' type='text/css' href='".$site['url']['full']."include/iemain.css' />";
			echo "<![endif]-->";
			echo "<!--[if lte IE 6]>";
			echo "<link rel='stylesheet' type='text/css' href='".$site['url']['full']."include/ie6main.css' />";
#			echo "<script type='text/javascript' src='".$static['server']['inc']."TransparentPng.js'></script>";
			echo "<![endif]-->";
			if ($_SERVER['HTTPS'] != 'on') {
				echo "<script type='text/javascript' src='".$static['server']['inc']."acewebengine-v1.js'></script>";
			}
			echo "<script type='text/javascript' src='".$site['url']['full']."images/tours/swfObject.js'></script>";
			echo "<script type='text/javascript' src='".$site['url']['full']."include/lightbox.js'></script>";
			echo "<script type='text/javascript' src='".$site['url']['full']."include/jquery.min.js'></script>";
			echo "<script type='text/javascript' src='".$site['url']['full']."include/jquery.script.js'></script>";
		echo "</head>";

	ob_flush();
	flush();

#################################################################################################
# Body                                                                                          #
#################################################################################################

	echo "<body>";
		echo "<div id='container'>";
			echo "<div id='header'>";
				echo "<img usemap='#logomap' src='".$site['url']['full']."images/".$site['template']['header']['image']."' width='960' height='120' alt='' title='' border='0' />";
				echo "<map name='logomap' id='logomap'>";
					echo "<area shape='rect' coords='5, 29, 235, 100' href='".$site['url']['full']."' alt='".$site['company']['name']."' title='".$site['company']['name']."' />";
				echo "</map>";
				echo "<div id='menuh'><p>".$site['menu']['h']."</p></div><!-- menuh -->";
			echo "</div><!-- header -->";
		echo "<div id='wrapper' class='mainbody'>";
	
#################################################################################################
# Left Container and Vertical Menu                                                             #
#################################################################################################

			echo "<div id='leftcontainer'>";
				echo "<h3>Our Products</h3>";
				echo "".$site['menu']['v']."";
				echo "<div id='facebook'><a href='http://www.facebook.com/NewGlobalTradingMelbourne' target='_blank'><img src='".$site['url']['full']."images/facebook.jpg' alt='Find us on Facebook link' title='Click to visit New Global Trading on Facebook' width='159' height='36' /></a></div>";
			echo "</div> <!-- leftcontainer -->";
	
#################################################################################################
# Middle Container                                                                              #
#################################################################################################

			echo "<div id='middle'>";
	if ($page['pageId'] != '1' && !$page['pageAdmin']) {
					echo "<div id='pagetree'><p>".$page['tree']."</p></div>";
	} else {
					echo "<div id='pagetree'><p>&nbsp;</p></div>";
	}
	if ($page['pageId'] != '1')  {
					echo "<div id='pageheading'><h1>".$page['pageMenu']."</h1></div>";
	}
	if($page['pageFile']) {
		include $page['pageFile'];
		if ($page['pageId'] != '1' && $page['pageFile'] != 'admin.php') { 
			echo $page['subpage'];
		}
	}
	if ($page['contactMessage']) { 
		echo $page['contactMessage']; 
	}
							
			echo "<div style='clear: both;'></div>";
			echo "</div> <!-- middle -->";
		echo "</div> <!-- wrapper -->";
			echo "<div id='footer'>";
				echo "<div id='footercont'>";
					echo "<div id='footleft'>".$site['pageFooter']."</div>";
					echo "<div id='footright'>";
						echo "<img src='".$site['url']['full']."images/logo-revell.png' width='105' height='50' alt='' title='' border='0' />";
						echo "<img src='".$site['url']['full']."images/logo-umbrella.png' width='47' height='50' alt='' title='' border='0' />";
						echo "<img src='".$site['url']['full']."images/logo-kingston.png' width='45' height='50' alt='' title='' border='0' />";
						echo "<img src='".$site['url']['full']."images/logo-globalstar.png' width='110' height='50' alt='' title='' border='0' />";
					echo "</div>";
				echo "</div>";
			echo "</div> <!-- footer -->";
		echo "</div>"; ## Site Container ##
	echo "</body>";
	echo "</html>";
?>