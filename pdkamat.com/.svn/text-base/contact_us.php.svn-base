<?php
$title = 'Contact Us';
$content = file_get_contents(basename(__FILE__, '.php') . '.html');
include 'template.html';

if (isset($_POST['submit']))
{
	$body = "Detailed Enquiry: \n\nFirst Name: {$_POST['fname']}\nLast Name: {$_POST['lname']}".
			"Email Id: {$_POST['email']}\nPostal Address: {$_POST['postaddress']}\n".
			"City: {$_POST['city']}\nTelephone: {$_POST['telephone']}\nMobile: {$_POST['mobile']}\n".
			"Fax: {$_POST['fax']}\nEnquiry: {$_POST['enquiry']}";
	include("class.phpmailer.php");
	$mail=new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPAuth   = true;
	$mail->SMTPSecure = "ssl";
	$mail->Host       = "smtp.gmail.com";
	$mail->Port       = 465; 
	$mail->Username   = "testing.sohum@gmail.com";
	$mail->Password   = "tastytesting";
	$mail->From       = $_POST['email'];
	$mail->FromName   = $_POST['fname'];
	$mail->Subject    =	"Enquiry From Customer {$_POST['fname']} {$_POST['fname']}";
	$mail->Body       = nl2br($body);
	$mail->AltBody    = $body;
	$mail->WordWrap   = 50;
	$mail->AddAddress("alansaviolobo@gmail.com","Enquiry");
	$mail->IsHTML(true);
	$mail->Send();
}
?>