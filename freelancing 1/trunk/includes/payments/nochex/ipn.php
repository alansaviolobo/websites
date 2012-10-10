<?php
if(!isset($_POST['to_email']))
{
	exit('error, no post');
}

$transaction_id = $_POST['transaction_id']; 
$payment_amount = $_POST['amount']; 
$order_id       = $_POST['order_id']; 
$from_email     = $_POST['from_email']; 
$to_email       = $_POST['to_email']; 
$status         = $_POST['status']; 
$custom			= $_POST['custom'];

if($to_email != $settings_array['nochex_address'])
{
	mail($config['admin_email'],'NoChex error in '.$config['site_title'],'NoChex error in '.$config['site_title'].', address that the money was sent to does not match the settings');
	exit('Error with address');
}

$req = ''; 
foreach ($_POST as $key => $value) 
{
	$value = urlencode(stripslashes($value)); 
	$req  .= "&$key=$value"; 
} 
$req = ltrim($req,'&'); 

$header  = "POST /nochex.dll/apc/apc HTTP/1.0\r\n"; 
$header .= "Content-Type: application/x-www-form-urlencoded\r\n"; 
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n"; 
$fp      = fsockopen('ssl://www.nochex.com', 443, $errno, $errstr, 10); 

if ($fp) 
{
	fputs($fp, $header . $req); 

	while (!feof($fp)) 
	{
    	$apc_status = fgets($fp, 1024); 

		if ($apc_status == 'AUTHORISED') 
		{

			switch ($payment_status)
			{
				case 'Completed':
					$result=mysql_query("SELECT * FROM `".$config['db']['pre']."transactions` WHERE `transaction_id` = '" . validate_input($custom) . "' LIMIT 1");
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
				break;
				case 'Pending':
					$result=mysql_query("SELECT * FROM `".$config['db']['pre']."transactions` WHERE `transaction_id` = '" . validate_input($custom) . "' LIMIT 1");
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
                break;
				case 'Failed':
					mail($config['admin_email'],'NoChex Payment Failed','Someone tried to deposit money into '.$config['site_title'].' but it failed');
				break;
			}
		} 
		else if ($apc_status == 'DECLINED') 
		{
			mail($config['admin_email'],'NoChex Payment Declined','Someone tried to deposit money into '.$config['site_title'].' but it was declined');
		} 
  } 

  fclose($fp); 
}
else 
{
	mail($config['admin_email'],'NoChex Error',$errstr.' ('.$errno.')');
} 
?>