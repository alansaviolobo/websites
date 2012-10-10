<?php
$name = $_POST['name']; 
$email = $_POST['email']; 
$comments = $_POST['comments'];

$todayis = date("d-m-y") ;

$message = " Client Request sent from ContactUs Page on $todayis  \n
From: $name \n
Email: $email \n
Comments: $comments \n 
";

$from = "support@sterlingdevelopersgoa.com";

mail("info@sterlingdevelopersgoa.com", "Enquiry by Visitor", $message, $from);

header( "Location: ./contactus1.htm" );
?>
