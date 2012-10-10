<?php

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

mysql_query("INSERT INTO `".$config['db']['pre']."transactions` ( `transaction_id` , `transaction_status` , `transaction_type` , `transaction_method` , `buyer_id` , `provider_id` , `transaction_ip` , `transaction_time` , `transaction_amount` , `transaction_description` , `transaction_proc` , `transaction_settings` ) VALUES ('', '1', '" . $type . "', 'deposit', '" . $buyer_id . "', '" . $provider_id . "', '" . encode_ip($_SERVER,$_ENV) . "', '" . time() . "', '" . $_POST['amount'] . "', 'moneybookers Deposit', '" . $_POST['payment_id'] . "', '');");

if( ($settings['deposit_cost'] != '') AND ($settings['deposit_cost'] != '0') )
{
	$_POST['amount'] = $_POST['amount'] + $settings['deposit_cost'];
}

if( ($settings['deposit_percentage'] != '') AND ($settings['deposit_percentage'] != '0') )
{
	$_POST['amount'] = ($_POST['amount'] / 100) * (100 + $settings['deposit_percentage']);
}

$_POST['amount'] = round($_POST['amount'], 2);

?>
<style type="text/css">
<!--
.style1 {
	font-size: 14px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
-->
</style>

<body onload="javascript:document.moneybookers.submit();">
<form name=moneybookers action="https://www.moneybookers.com/app/payment.pl" method="post" target="_blank">
<input type="hidden" name="pay_to_email" value="<?php echo $settings['moneybookers_address'];?>">
<input type="hidden" name="status_url" value="<?php echo $config['site_url'] . 'ipn.php?i=moneybookers'; ?>">
<input type="hidden" name="amount" value="<?php echo $_POST['amount']; ?>">
<input type="hidden" name="currency" value="<?php echo $config['currency_code']; ?>">
<input type="hidden" name="detail1_description" value="Description:">
<input type="hidden" name="detail1_text" value="Deposit into <?php echo $config['site_title']; ?>">
<input type="hidden" name="return_url" value="<?php echo $config['site_url'] . 'manage.php'; ?>">
<input type="hidden" name="transaction_id" value="<?php echo mysql_insert_id() ?>">
<input type="submit" value="Pay!">
</form>

    
<div align="center" class="style1">Transfering you to the moneybookers.com Secure payment system, if you are not forwarded <a href="javascript:document.moneybookers.submit();">click here</a></div>
</body>