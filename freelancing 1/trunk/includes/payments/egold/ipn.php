<?php
// if(!isset($_POST['PAYER_ACCOUNT']))
// {
// 	exit('error, no post');
// }



$payment_amount = $_POST['PAYMENT_AMOUNT'];
$txn_id = $_POST['PAYMENT_BATCH_NUM'];
$receiver_account = $_POST['PAYEE_ACCOUNT'];
$payer_account = $_POST['PAYER_ACCOUNT'];
$custom = $_POST['PAYMENT_ID'];

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

  $tok=md5($_POST['PAYMENT_ID'].":".$_POST['PAYEE_ACCOUNT'].":".$_POST['PAYMENT_AMOUNT'].":".$_POST['PAYMENT_UNITS'].":".$_POST['PAYMENT_METAL_ID'].":".$_POST['PAYMENT_BATCH_NUM'].":".$_POST['PAYER_ACCOUNT'].":".$settings_array['MD5_Secret'].":".$_POST['ACTUAL_PAYMENT_OUNCES'].":".$_POST['USD_PER_OUNCE'].":".$_POST['FEEWEIGHT'].":".$_POST['TIMESTAMPGMT']);
  if ($tok !=  $_POST['V2_HASH']){
    mail($config['admin_email'],'E-Gold error in '.$config['site_title'],'E-Gold error in '.$config['site_title'].', the md5 received differs from the  calculated please check the transaction number '.$txn_id);
    exit('Please contact admin');
  }
}


if($receiver_account != $settings_array['egold_account'])
{
	mail($config['admin_email'],'E-Gold error in '.$config['site_title'],'E-Gold error in '.$config['site_title'].', address that the money was sent to does not match the settings');
	exit('Error with address');
}

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
?>