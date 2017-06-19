<?php
	switch (strtolower($expand[0])) {
	# [panorama|id]
		case "panorama":
			if ($expand['1'] == "Large" || $expand['1'] == "large") {
				$result .= "<div style='width: 728px; margin: 0 0 0 -10px'>";
				$result .= "<div id='flashcontent'>";
				$result .= "<p>To view virtual tour properly, Flash Player 9.0.28 or later version is needed. Please download the latest version of <a href='http://www.adobe.com/go/getflashplayer' target='_blank'>Flash Player</a> and install it on your computer.</p>";
				$result .= "</div>";
				$result .= '<script type="text/javascript"> 
var so = new SWFObject("'.$site['url']['full'].'images/tours/PWViewer.swf","'.$site['url']['full'].'images/tours/large/pano1","706","500","9.0.28","#FFFFFF");so.addVariable("base", "large/pano1/");so.addVariable("lwImg", "resources/waiting.gif");so.addVariable("lwBgColor","204,255,255,255");so.addVariable("iniFile", "config_pano1.bin");so.addVariable("progressType", "1"); so.addVariable("swfFile", ""); so.addParam("allowNetworking", "all");
so.addParam("allowScriptAccess", "always");
so.addParam("allowFullScreen", "true");
so.addParam("scale", "noscale"); 
so.write("flashcontent"); 
</script>';
				$result .= "</div>";
			} else {
				$result .= "<div style='float: right; width: 424px; margin: 0px 0px 5px 5px;'>";
				$result .= "<div id='flashcontent'>";
				$result .= "<p>To view virtual tour properly, Flash Player 9.0.28 or later version is needed. Please download the latest version of <a href='http://www.adobe.com/go/getflashplayer' target='_blank'>Flash Player</a> and install it on your computer.</p>";
				$result .= "</div>";
				$result .= '<script type="text/javascript"> 
var so = new SWFObject("'.$site['url']['full'].'images/tours/PWViewer.swf","'.$site['url']['full'].'images/tours/pano'.$expand['1'].'","400","300","9.0.28","#FFFFFF");so.addVariable("base", "pano'.$expand['1'].'/");so.addVariable("lwImg", "resources/waiting.gif");so.addVariable("lwBgColor","204,255,255,255");so.addVariable("iniFile", "config_pano1.bin");so.addVariable("progressType", "1"); so.addVariable("swfFile", ""); so.addParam("allowNetworking", "all");
so.addParam("allowScriptAccess", "always");
so.addParam("allowFullScreen", "true");
so.addParam("scale", "noscale"); 
so.write("flashcontent"); 
</script>';
				$result .= "</div>";
			}
			break;
	}
?>
