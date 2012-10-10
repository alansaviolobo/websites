<?php
if(!isset($_POST['receiver_email']))
{
	exit('error, no post');
}

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

if( ($settings_array['deposit_cost'] != '') AND ($settings_array['deposit_cost'] != '0') )
{
	$payment_amount = $payment_amount - $settings_array['deposit_cost'];
}

if( ($settings_array['deposit_percentage'] != '') AND ($settings_array['deposit_percentage'] != '0') )
{
	$payment_amount = ($payment_amount / 100) * (100 - $settings_array['deposit_percentage']);
}

if($receiver_email != $settings_array['paypal_address'])
{
	mail($config['admin_email'],'Paypal error in '.$config['site_title'],'Paypal error in '.$config['site_title'].', address that the money was sent to does not match the settings');
	exit('Error with address');
}

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
			$result=mysql_query("SELECT * FROM `".$config['db']['pre']."transactions` WHERE `transaction_id` = '" . addslashes($custom) . "' LIMIT 1");
			while($row=mysql_fetch_array($result))
			{
				if($row['buyer_id']=='0')
				{
					$account_type='provider';
					$user_id=$row['provider_id'];
				}
				elseif($row['provider_id']=='0')
				{
					$account_type='buyer';			
					$user_id=$row['buyer_id'];
				}
			}
			
			$result=mysql_query("SELECT balance_amount FROM `".$config['db']['pre'].$account_type."s_balance` WHERE `".$account_type."_id` = '" . $user_id . "' LIMIT 1");
			while($row=mysql_fetch_array($result))
			{
				$current_amount=$row['balance_amount'];
			}
			$payment_amount=($payment_amount+$current_amount);
			mysql_query("UPDATE `".$config['db']['pre'].$account_type."s_balance` SET `balance_amount` = '" . $payment_amount . "' WHERE `".$account_type."_id` = '" . $user_id . "' LIMIT 1");
			mysql_query("UPDATE `".$config['db']['pre']."transactions` SET `transaction_status` = '0' WHERE `transaction_id` = '" . $custom . "' LIMIT 1");
		}
		else if (strcmp ($res, "INVALID") == 0) 
		{
			mail($config['admin_email'],'Paypal error in '.$config['site_title'],'Paypal error in '.$config['site_title'].', invalid response from paypal');
			exit('Invalid transaction');
		}
	}
	fclose ($fp);
}
?>