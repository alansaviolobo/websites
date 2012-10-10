<?php
if(!isset($_POST['pay_from_email']))
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

$payment_status = $_POST['status'];
$payment_amount = $_POST['mb_amount'];
$payment_currency = $_POST['mb_currency'];
$txn_id = $_POST['mb_transaction_id'];
$receiver_email = $_POST['pay_to_email'];
$payer_email = $_POST['pay_from_email'];
$custom = $_POST['transaction_id'];

if( ($settings_array['deposit_cost'] != '') AND ($settings_array['deposit_cost'] != '0') )
{
	$payment_amount = $payment_amount - $settings_array['deposit_cost'];
}

if( ($settings_array['deposit_percentage'] != '') AND ($settings_array['deposit_percentage'] != '0') )
{
	$payment_amount = ($payment_amount / 100) * (100 - $settings_array['deposit_percentage']);
}

if( ($settings_array['MD5_Secret'] != ''))
{

  $tok=md5($_POST['merchant_id'].$custom. strtoupper($settings_array['MD5_Secret']).$payment_amount.$payment_currency.$payment_status);
  if ($tok !=  $_POST['md5sig']){
    mail($config['admin_email'],'Moneybookers error in '.$config['site_title'],'Moneybookers error in '.$config['site_title'].', the md5 received differs from the  calculated please check the transaction number '.$txn_id);
    exit('Please contact admin');
  }
}


if($receiver_email != $settings_array['moneybookers_address'])
{
	mail($config['admin_email'],'Moneybookers error in '.$config['site_title'],'Moneybookers error in '.$config['site_title'].', address that the money was sent to does not match the settings');
	exit('Error with address');
}

if ($payment_status == 2) 
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
else 
{
//     mysql_query("UPDATE `".$config['db']['pre']."transactions` SET `transaction_status` = '".($payment_status -1)."' WHERE `transaction_id` = '" . $custom . "' LIMIT 1");
// 	mail($config['admin_email'],'Moneybookers error in '.$config['site_title'],'Moneybookers error in '.$config['site_title'].', status from moneybookers');
// 	exit('Invalid transaction');
}
?>