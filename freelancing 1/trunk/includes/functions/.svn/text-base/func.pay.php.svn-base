<?php
// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';

foreach ($_POST as $key => $value) 
{
	$value = urlencode(stripslashes($value));
	$req .= "&$key=$value";
}

// post back to PayPal system to validate
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
$fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);

// assign posted variables to local variables
$item_name = $_POST['item_name'];
$item_number = $_POST['item_number'];
$payment_status = $_POST['payment_status'];
$payment_amount = $_POST['mc_gross'];
$payment_currency = $_POST['mc_currency'];
$txn_id = $_POST['txn_id'];
$receiver_email = $_POST['receiver_email'];
$payer_email = $_POST['payer_email'];
$custom = $_POST['custom'];

if (!$fp) 
{
	// HTTP ERROR
}
else
{
	fputs ($fp, $header . $req);
	while (!feof($fp)) 
	{
		$res = fgets ($fp, 1024);
		if (strcmp ($res, "VERIFIED") == 0) 
		{
			$project_info = mysql_fetch_row(mysql_query("SELECT project_id,buyer_id,provider_id FROM `".$config['db']['pre']."projects` WHERE project_id='".validate_input($custom)."' LIMIT 1"));
		
			if($project_info[0])
			{
				mysql_query("UPDATE `".$config['db']['pre']."projects` SET `project_paid` = '1' WHERE `project_id` ='".validate_input($project_info[0])."' LIMIT 1;");
				mysql_query("INSERT INTO `".$config['db']['pre']."transactions` ( `transaction_id` , `transaction_status` , `transaction_type` , `transaction_method` , `buyer_id` , `provider_id` , `transaction_ip` , `transaction_time` , `transaction_amount` , `transaction_description` , `transaction_proc` , `transaction_settings` ) VALUES ('', '0', 'buy', 'deposit', '" . $project_info[1] . "', '" . $project_info[2] . "', '" . validate_inputencode_ip($_SERVER,$_ENV)) . "', '" . time() . "', '" . validate_input($payment_amount) . "', 'Project Payment', 'project_pay', '');");
			}
		}
		else if (strcmp ($res, "INVALID") == 0) 
		{

		}
	}
	fclose ($fp);
}
?>