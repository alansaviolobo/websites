<?php
require_once('includes/config.php');
require_once('includes/functions/func.global.php');

// Start session
session_start();

// Get 5 random characters
$captchastr = getrandnum(4);

// Create the image
$captcha = imagecreate(200,50);
// Set background color
$backcolor = imagecolorallocate($captcha, 224, 224, 224);
// Set Text color
$txtcolor = imagecolorallocate($captcha, 86, 86, 86);

$gd_support = gd_info();

// Run through the 5 characters and add them to the image
for($i=1;$i<=5;$i++)
{
	$rotdirection = rand(1,2);
	
	if ($rotdirection == 1)
	{
		$angle = rand(0,20);
	}
	
	if ($rotdirection == 2)
	{
		$angle = rand(345,360);
	}
	
	if($gd_support['FreeType Support'])
	{
		imagettftext($captcha,rand(16,22),$angle,($i*30),30,$txtcolor,"includes/fonts/zeroes_3.ttf",substr($captchastr,($i-1),1));
	}
	else
	{
		imagestring($captcha, 5, ($i*30), 20, substr($captchastr,($i-1),1), $txtcolor);
	}
}
// Create 10 lines with the background color
for($i=1; $i<=10;$i++)
{
	imageline ($captcha, rand(1,200), 0,rand(50,100),50, $backcolor);
}
// Create 3 lines with the text color
for($i=1; $i<=3;$i++)
{
	imageline ($captcha, rand(1,200), 0,rand(50,100),50, $txtcolor);
}

// Set the string to session
$_SESSION['seccode'] = $captchastr;

//Send the png header
header('Content-type: image/png');

//Output the image as a PNG
imagepng($captcha);

//Delete the image from memory
imagedestroy($captcha);
?>