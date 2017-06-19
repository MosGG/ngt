<?php
@session_start();

function generateCode($length) {
        $chars = "abcdfgijkmnpqrstuvwxyz23456789";
        $code = "";
        $clen = strlen($chars) - 1;  //a variable with the fixed length of chars correct for the fence post issue
        while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0,$clen)];  //mt_rand's range is inclusive - this is why we need 0 to n-1
        }
        return $code;
}

function colorcalc($colorval) {
	$colour['r'] = hexdec(substr($colorval,1,2));
	$colour['g'] = hexdec(substr($colorval,3,2));
	$colour['b'] = hexdec(substr($colorval,5,2));
return $colour;
}

if ($_GET['clear']=='code') {
	$_SESSION['captcha'] = '';
}

if (!$_SESSION['captcha']) {
	$_SESSION['captcha'] = generateCode(5);
}

$text = $_SESSION['captcha'];

$char1 = substr($text, -5, 1);
$char2 = substr($text, -4, 1);
$char3 = substr($text, -3, 1);
$char4 = substr($text, -2, 1);
$char5 = substr($text, -1, 1);

$height = 43;
$width = 160;
$font = '/home/hosting/template-v0d/captcha.ttf';

if ($_SESSION['captchacolour']['forground']) {
	$colour_fg = $_SESSION['captchacolour']['forground'];
} else {
	$colour_fg = '#000000';
}
if ($_SESSION['captchacolour']['background']) {
	$colour_bg = $_SESSION['captchacolour']['background'];
} else {
	$colour_bg = '#ffffff';
}

$fg         = colorcalc($colour_fg);
$bg         = colorcalc($colour_bg);
$image_p    = imagecreate($width, $height);
$background = imagecolorallocate($image_p, $bg['r'], $bg['g'], $bg['b']);
$forground  = imagecolorallocate($image_p, $fg['r'], $fg['g'], $fg['b']); 

imagettftext($image_p, 22, rand(-50,50), 10, 30, $forground, $font, $char1);
imagettftext($image_p, 22, rand(-50,50), 40, 30, $forground, $font, $char2);
imagettftext($image_p, 22, rand(-50,50), 70, 30, $forground, $font, $char3);
imagettftext($image_p, 22, rand(-50,50), 100, 30, $forground, $font, $char4);
imagettftext($image_p, 22, rand(-50,50), 130, 30, $forground, $font, $char5);

imageline($image_p, 0, 0, 159, 0, $forground);
imageline($image_p, 0, 42, 159, 42, $forground);
imageline($image_p, 0, 0, 0, 42, $forground);
imageline($image_p, 159, 0, 159, 42, $forground);

$i = 7;
while ($i <= 43) {
	imageline($image_p, 1, $i, 170, $i, $forground);
	$i = $i + 7;
}

$j = 7;
while ($j <= 160) {
	imageline($image_p, $j, 1, $j, 43, $forground);
	$j = $j + 7;
}

imagejpeg($image_p, null, 80);
?>