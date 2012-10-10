<?php
if(!isset($_POST['address']))
{
	$page = new HtmlTemplate ("includes/payments/cheque/withdraw.html");
	$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang));
	$page->SetParameter ('PAYMENT_ID', $_POST['payment_id']);
	$page->SetParameter ('AMOUNT', $_POST['amount']);
	$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
	$page->CreatePageEcho($lang,$config);
}
else
{
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
	
	$withdraw_set['name'] = $_POST['name'];
	$withdraw_set['address'] = $_POST['address'];
	
	$withdraw_settings = serialize($withdraw_set);

	mysql_query("INSERT INTO `".$config['db']['pre']."transactions` ( `transaction_id` , `transaction_status` , `transaction_type` , `transaction_method` , `buyer_id` , `provider_id` , `transaction_ip` , `transaction_time` , `transaction_amount` , `transaction_description` , `transaction_proc` , `transaction_settings` ) VALUES ('', '1', '" . $type . "', 'withdraw', '" . $buyer_id . "', '" . $provider_id . "', '" . encode_ip($_SERVER,$_ENV) . "', '" . time() . "', '" . validate_input($_POST['amount']) . "', 'Cheque Withdrawal', '" . validate_input($_POST['payment_id']) . "', '" . $withdraw_settings . "');");

	if( ($settings['withdraw_percentage'] != '') AND ($settings['withdraw_percentage'] != '0') )
	{
		$_POST['amount'] = ($_POST['amount']+(($_POST['amount'] / 100) * $settings['withdraw_percentage']));
	}
	
	if( ($settings['withdraw_cost'] != '') AND ($settings['withdraw_cost'] != '0') )
	{
		$_POST['amount'] = $_POST['amount'] + $settings['withdraw_cost'];
	}

	$query = "SELECT balance_amount FROM `".$config['db']['pre'].$_SESSION['user']['type']."s_balance` WHERE ".$_SESSION['user']['type']."_id='" . $_SESSION['user']['id'] . "' LIMIT 1";
	$query_result = @mysql_query ($query) OR error(mysql_error());
	while ($info = @mysql_fetch_array($query_result))
	{
		$balance = $info['balance_amount'];
	}
	
	$payment_amount=$balance-$_POST['amount'];
	mysql_query("UPDATE `".$config['db']['pre'].$_SESSION['user']['type']."s_balance` SET `balance_amount` = '" . $payment_amount . "' WHERE `".$_SESSION['user']['type']."_id` = '" . $_SESSION['user']['id']. "' LIMIT 1");
				
	message('Thank you for requesting to withdraw money, your request will be processed within 48 hours',$config,$lang);

	exit;
}
?>