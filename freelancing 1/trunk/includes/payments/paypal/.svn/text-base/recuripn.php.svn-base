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
$txn_type = $_POST['txn_type'];

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
			$debug = var_export($_POST,true);
			
			if($txn_type == 'subscr_payment')
			{
				$custom_parts = explode('|',$custom);
			
				// Check that the payment is valid
				$subsc_details = mysql_fetch_array(mysql_query("SELECT * FROM ".$config['db']['pre']."subscriptions WHERE sub_id='".validate_input($custom_parts[2])."' LIMIT 1"));
				
				$term = 0;
				
				if($subsc_details['sub_term'] == 'DAILY')
				{
					$term = 86400;
				}
				elseif($subsc_details['sub_term'] == 'MONTHLY')
				{
					$term = 2678400;
				}
				elseif($subsc_details['sub_term'] == 'YEARLY')
				{
					$term = 31536000;
				}
								
				// Add time to their subscription
				$expires = (time()+$term);
				
				mysql_query("UPDATE `".$config['db']['pre']."upgrades` SET `upgrade_expires` = '".validate_input($expires)."' WHERE `user_id` = '".validate_input($custom_parts[0])."' AND `user_type` = '".validate_input($custom_parts[1])."' LIMIT 1 ;");
				
				if($expires > time())
				{
					if($custom_parts[1] == 'buyer')
					{
						mysql_query("UPDATE `".$config['db']['pre']."buyers` SET `group_id` = '".validate_input($custom_parts[2])."' WHERE `buyer_id` = '".validate_input($custom_parts[0])."' LIMIT 1 ;");
					}
					else
					{
						mysql_query("UPDATE `".$config['db']['pre']."providers` SET `group_id` = '".validate_input($custom_parts[2])."' WHERE `provider_id` = '".validate_input($custom_parts[0])."' LIMIT 1 ;");
					}
				}
				
				if($_SESSION['user']['type'] == 'buyer')
				{
					$type = 'buy';
					
					$provider_id = 0;
					$buyer_id = $_SESSION['user']['id'];
				}
				else
				{
					$type = 'pro';
					
					$provider_id = $_SESSION['user']['id'];
					$buyer_id = 0;
				}
				
				mysql_query("INSERT INTO `".$config['db']['pre']."transactions` ( `transaction_id` , `transaction_status` , `transaction_type` , `transaction_method` , `buyer_id` , `provider_id` , `transaction_ip` , `transaction_time` , `transaction_amount` , `transaction_description` , `transaction_proc` , `transaction_settings` ) VALUES ('', '1', '" . $type . "', 'subscr', '" . $buyer_id . "', '" . $provider_id . "', '" . encode_ip($_SERVER,$_ENV) . "', '" . time() . "', '" . validate_input($payment_amount) . "', 'Paypal Subscription', '" . validate_input($_GET['i']) . "', '');");
			}
			elseif($txn_type == 'subscr_signup')
			{
				// Add Subscription
				$custom_parts = explode('|',$custom);
				
				// Check valid user
				if($custom_parts[1] == 'buyer')
				{
					$user_check = mysql_num_rows(mysql_query("SELECT 1 FROM ".$config['db']['pre']."buyers WHERE buyer_id='".validate_input($custom_parts[0])."' LIMIT 1"));
				}
				else
				{
					$user_check = mysql_num_rows(mysql_query("SELECT 1 FROM ".$config['db']['pre']."providers WHERE provider_id='".validate_input($custom_parts[0])."' LIMIT 1"));
				}
				
				if(!$user_check)
				{
					exit('error, user does not exist');
				}
				
				mysql_query("INSERT INTO `".$config['db']['pre']."upgrades` (`sub_id` ,`user_type` ,`user_id` ,`upgrade_lasttime` ,`upgrade_expires`) VALUES ('".validate_input($custom_parts[2])."', '".validate_input($custom_parts[1])."', '".validate_input($custom_parts[0])."', '".time()."', '0');");
			}
			elseif($txn_type == 'subscr_cancel')
			{
				
			}
			else
			{
			
			}
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