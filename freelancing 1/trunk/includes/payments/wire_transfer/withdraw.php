<?php
if(!isset($_POST['account_holders_name']))
{
	$page = new HtmlTemplate ("includes/payments/wire_transfer/withdraw.html");
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
		$type = 'pri';
	
		$provider_id = $_SESSION['user']['id'];
		$buyer_id = 0;
	}
	
	$withdraw_set = array();
    $withdraw_set['account_holders_name'] = $_POST['account_holders_name'];
    $withdraw_set['bank_name'] = $_POST['bank_name'];
    $withdraw_set['bank_street1'] = $_POST['bank_street1'];
    $withdraw_set['bank_street2'] = $_POST['bank_street2'];
    $withdraw_set['bank_city'] = $_POST['bank_city'];
    $withdraw_set['bank_state'] = $_POST['bank_state'];
    $withdraw_set['bank_country'] = $_POST['bank_country'];
    $withdraw_set['bank_zip'] = $_POST['bank_zip'];
    $withdraw_set['bank_number'] = $_POST['bank_number'];
    $withdraw_set['bank_swift'] = $_POST['bank_swift'];
    $withdraw_set['account_type'] = $_POST['account_type'];
    $withdraw_set['bank_adit'] = $_POST['bank_adit'];
	
	$withdraw_settings = serialize($withdraw_set);
	
	mysql_query("INSERT INTO `".$config['db']['pre']."transactions` ( `transaction_id` , `transaction_status` , `transaction_type` , `transaction_method` , `buyer_id` , `provider_id` , `transaction_ip` , `transaction_time` , `transaction_amount` , `transaction_description` , `transaction_proc` , `transaction_settings` ) VALUES ('', '1', '" . $type . "', 'withdraw', '" . $buyer_id . "', '" . $provider_id . "', '" . encode_ip($_SERVER,$_ENV) . "', '" . time() . "', '" . $_POST['amount'] . "', 'Wire Transfer Withdrawal', '" . $_POST['payment_id'] . "', '" . addslashes($withdraw_settings) . "');");

	message('Thank you for requesting to withdraw money, your request will be processed soon',$config,$lang);
}	
?>