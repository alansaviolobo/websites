<?php
$name = $_POST['name']; 
$email = $_POST['email']; 
$company = $_POST['company'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$fax = $_POST['fax'];
$requirement = $_POST['requirement'];

$todayis = date("d-m-y") ;

$message = " Client Request sent from Enquiry Page on $todayis  \n
From: $name \n
Company Name : $company \n 
Address : $address \n 
E-Mail : $email \n 
Phone : $phone \n 
Fax : $fax \n 
Requirement : $requirement \n 
";

$from = "support@sterlingdevelopersgoa.com";

mail("info@sterlingdevelopersgoa.com", "Enquiry by Visitor", $message, $from);

header( "Location: ./enquiry1.htm" );
?>
