<?php
	if(!isset($_COOKIE["landingPageFlag"])){
		header('location: /landingPage');
	}

	$timerstart = time()+microtime();

	include 'include/common.inc.php';

	#######################################################################
	# Read Page Table (Layout)
	#######################################################################

	$fp = fopen("page_update.txt", "r");
	$page_update = fgets($fp);
	fclose($fp);

	if ($_SESSION['cache']['layout'] && $_SESSION['cache']['pageupdate'] == $page_update) {
		$site['layout'] = $_SESSION['cache']['layout'];
		$site['layoutParent'] = $_SESSION['cache']['layoutParent'];
	} else {
		get_page_structure('0');
		$_SESSION['cache']['pageupdate']   = $page_update;
		$_SESSION['cache']['layout']       = $site['layout'];
		$_SESSION['cache']['layoutParent'] = $site['layoutParent'];
	}

	$_SESSION['page'] = url_page($site['url']['decode']['path']);
	if (!$_SESSION['page']) {
		$_SESSION['page'] = "1";
	}
	$site['url']['actual'] = $site['url']['full'].url_path($_SESSION['page']);
	if ($site['layout'][$_SESSION['page']]['pageUrl']) {
		$site['url']['actual'] .= $site['layout'][$_SESSION['page']]['pageUrl']."/";
	}


	#######################################################################
	# Read Page Data ($page)
	#######################################################################

	$sql = "SELECT * FROM ".$site['database']['page']." WHERE `pageId` = '".$_SESSION['page']."'";
	$result = sql_exec($sql);
	$page = $result->fetch_assoc();
	if (!$page['pageTemplate']) { $page['pageTemplate'] = "default"; }
	if (!$page['pageFile']) { $page['pageFile'] = "page_v0c.php"; }
	$page['page_gallery']['display'] = $site['template']['page_gallery']['display'];

	#######################################################################
	# Create Page Tree ($page['tree'])
	#######################################################################

	$page['tree'] = url_tree($_SESSION['page'], '>');

	#######################################################################
	# Create Page Subpage ($page['subpage'])
	#######################################################################
	
	if (!$_SESSION['access']) { $_SESSION['access'] = "20"; }

	$path = url_path($_SESSION['page']);

	if (isset($site['layoutParent'][$_SESSION['page']])) {
		$page['subpage'] = "<table id='subpage-tb' border='0' cellpadding='0' cellspacing='12'>";
		$pointer = "0";
		foreach($site['layoutParent'][$_SESSION['page']] as $key=>$data) {
			$pointer ++;
			if ($pointer == "1") { $page['subpage'] .= "<tr>"; }
			if ($pointer == $site['template']['subpage']['columns']) { $page['subpage'] .= "</tr><tr>"; $pointer = "1"; }
			if ($_SESSION['access'] > $site['layout'][$data]['pageMenuHAccess'] || $_SESSION['access'] > $site['layout'][$data]['pageMenuVAccess'] ) {
				if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) {
					$url = "http://".$site['url']['url']."/".$path."".$page['pageUrl'];
				} else {
					$url = $site['url']['full'].$path."".$page['pageUrl'];
				}
				$site['layout'][$data]['pageMenu'] = strtolower($site['layout'][$data]['pageMenu']);
				if ($page['pageUrl']) {$url .= "/";}
				$url .= $key;
				$page['subpage'] .= "<td class='subpage'>";
				// $page['subpage'] .= "<table border='0' width='150'>";
				$page['subpage'] .= "<a href='".$url."'><div class='sub-div-img' style='background:url(".
					$site['url']['full'].image_display($site['path']['page']['thumb'], $site['layout'][$data]['pageId']).
					") no-repeat center;background-size:contain;'></div><div class='sub-div-title'>".$site['layout'][$data]['pageMenu']."</div></a>";
				// $page['subpage'] .= "</table>";
				$page['subpage'] .= "</td>";
			}
		}
		$page['subpage'] .= "</tr></table>";
	}

	#######################################################################
	# Break text into limited length lines and optional limited lines
	#######################################################################

	function breaktext($text, $charlimit, $linelimit='none') {
		$words = explode(" ", $text);

		$count = 1;
		foreach ($words as $word) {
			if ((strlen($line[$count])+strlen($word) <= $charlimit) && !$line[$count+1]) {
				if ($line[$count]) { $line[$count] .= " "; }
				$line[$count] .= $word;
			} else {
				$count ++;
				if ($line[$count]) { $line[$count] .= " "; }
				$line[$count] .= $word;
			}
		}

		foreach ($line as $lineid => $linedata) {
			if ($linelimit != none) {
				if ($linecheck < $linelimit) {
					if ($result[text]) { $result[text] .= "<br />"; }
					$result[text] .= $linedata;
					$linecheck ++;
				}
			} else {
				if ($result[text]) { $result[text] .= "<br />"; }
				$result[text] .= $linedata;
				$linecheck ++;
			}
		}
		$result[lines] = $linecheck;

	return $result;
	}


	#######################################################################
	# Create Horizontal & Verticle Menu
	#######################################################################

	$site['menu']['v'] = "<ul>\n";
	$site['menu']['h'] = "";
	$pointer = "0";
	foreach ($site['layout'] as $key=>$data) {
		if ($data['pageMenuH'] || $data['pageMenuV']) {
			$path = url_path($data['pageId']);
			if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) {
				$url = "http://".$site['url']['url']."/".$path."".$data['pageUrl'];
			} else {
				// if (!$data['pageLink']) {
					$url = $site['url']['full'].$path."".$data['pageUrl'];
					$target = "";
				// } else {
				// 	$url = "http://".$data['pageLink'];
				// 	$target = "target='_blank'";
				// }
			}
			if ($page['pageId'] == $key) {
				$class = "menuhselect";
			} else {
				$class = "menuhnotselect";
			}
			if ($data['pageMenuH'] && $_SESSION['access'] > $data['pageMenuHAccess']) {
				if ($pointer > "0") {
					$site['menu']['h'] .= "";
				}
				$site['menu']['h'] .= "<span class='".$class." hvr-underline-from-left'><a href='".$url."' title='".$data['pageMenu']."' $target>".$data['pageMenuH']."</span></a></span>";
				$pointer ++;
			}
			if ($page['pageId'] == $key) {
				$class = "menuvselect";
				$class2 = "hvr-underline-from-left";
			} else {
				$class = "menuvnotselect";
				$class2 = "hvr-underline-from-left-blue";
			}
			if ($data['pageMenuV'] && $_SESSION['access'] > $data['pageMenuVAccess']) {
				$site['menu']['v'] .= "<li class='".$class."'><a href='".$url."' title='".$data['pageMenu']."' $target><span class='".$class2."'>".strtolower($data['pageMenuV'])."</span></a></li>\n";
			}
		}
	}
	$site['menu']['v'] .= "</ul>\n";

	#######################################################################
	# Security Check (admin_fail.php)
	#######################################################################

	if ($_SESSION['access'] < $page['pageMenuHAccess'] && $_SESSION['access'] < $page['pageMenuHAccess']) {
		$page['pageFile'] = "admin_fail.php";
	}

	#######################################################################
	# Contact Message
	#######################################################################

	if ($page['pageAdmin']) {
		$contact = $static['contact'];
		$target   = "target='_blank'";
	} else {
		$contact = $site['contact'];
		$target   = "";
	}
	if ($page['pageFile'] != "recommend.php" && $page['pageFile'] != "contact.php" && $page['pageFile'] != "contact_v0c.php" && $page['pageId'] != "1") {
		$page['contactMessage'] = "<div id='contact'>".$contact['message']."<a href='".$contact['url']."' ".$target.">".$contact['link']."</a></div>\r";
	}

	#######################################################################
	# Recommend & Bookmark Site & Footer
	#######################################################################

	$site['pageRecommend'] = "<a href='".$site['url']['full']."recommend"."' title=\"Recommend ".$site['company']['name']." to a Friend\"><img src='".$site['url']['full']."images/friend.png' alt='Recommend ".$site['company']['name']." to a friend' border='0' /> Recommend this site</a>";
	$site['pageBookmark']  = "<a href=\"javascript:bookmarksite('".str_replace("'", "", $site['company']['name'])."', 'http://".$site['company']['web']."')\" title=' Add ".htmlspecialchars($site['company']['name'], ENT_QUOTES)." to your Favourites'><img src='".$site['url']['full']."images/fav.png' alt='Bookmark ".$site['company']['name']."' border='0' /> Bookmark this site!</a>";
	$site['pageFooter']    =  date_copyright()." &copy; Copyright ".$site['company']['footer']." - All Rights Reserved<br />Made with ❤ by <a href='http://www.cheee.com.au' target='_blank' title='Cheee Creative Studio'>Cheee</a>";

	#######################################################################
	# Include Template (*.tpl.php) & log.inc.php
	#######################################################################

	include 'include/'.$page['pageTemplate'].'.tpl.php';

	include 'include/log.inc.php';

	#######################################################################
	# Calculate Execution Time
	#######################################################################

	$timerstop = time()+microtime();
	$timer = round($timerstop-$timerstart,4);
	echo "<!-- ".$timer." -->";

	#######################################################################
	# Debug Mode (?debug=var ie. ?debug=_SESSION,page,_SERVER
	#######################################################################

	if ($_GET['debug'] == 'off') {
		unset($_SESSION['debug']);
		unset($_GET['debug']);
	}
	
	if ($_GET['debug']) {
		$debug  = explode (",", $_GET['debug']);
		foreach ($debug as $d) {
			if ($_SESSION['debug'][$d]) {
				unset($_SESSION['debug'][$d]);
			} else {
				$_SESSION['debug'][$d] = $d;
			}
		}
	}

	if ($_SESSION['debug']) {
		echo "<h2>Page creation time ".$timer." Seconds</h2>";
		echo "<div id='debug'><pre>";
		echo str_repeat("*", 70);
		foreach ($_SESSION['debug'] as $k=>$d) {
			echo "<h1>$".$k."</h1>";
			if ($k == "all" || $d == "debug")    { print_r($debug); }
			if ($k == "all" || $d == "static")   { print_r($static); }
			if ($k == "all" || $d == "edit")     { print_r($edit); }
			if ($k == "all" || $d == "site")     { print_r($site); }
			if ($k == "all" || $d == "page")     { print_r($page); }
			if ($k == "all" || $d == "setup")    { print_r($setup); }
			if ($k == "all" || $d == "_SESSION") { print_r($_SESSION); }
			if ($k == "all" || $d == "_SERVER")  { print_r($_SERVER); }
			if ($k == "all" || $d == "_POST")    { print_r($_POST); }
			if ($k == "all" || $d == "_FILES")   { print_r($_FILES); }
			echo str_repeat("*", 70);
		}
		echo "</pre></div>";
	}


?>