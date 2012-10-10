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

mysql_query("INSERT INTO `".$config['db']['pre']."transactions` ( `transaction_id` , `transaction_status` , `transaction_type` , `transaction_method` , `buyer_id` , `provider_id` , `transaction_ip` , `transaction_time` , `transaction_amount` , `transaction_description` , `transaction_proc` , `transaction_settings` ) VALUES ('', '1', '" . $type . "', 'deposit', '" . $buyer_id . "', '" . $provider_id . "', '" . encode_ip($_SERVER,$_ENV) . "', '" . time() . "', '" . $_POST['amount'] . "', 'NoChex Deposit', '" . $_POST['payment_id'] . "', '');");

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
<body onload="javascript:document.nochex.submit();">
<form name="nochex" id="nochex" method="POST" action="https://secure.nochex.com/">
<input type="hidden" name="merchant_id" value="<?php echo $settings['merchant_id'];?>">
<input type="hidden" name="amount" value="<?php echo $_POST['amount']; ?>">
<input type="hidden" name="description" value="Deposit into <?php echo $config['site_title']; ?>">
<input type="hidden" name="order_id" value="GRU1625">
<input type="hidden" name="optional_1" value="<?php echo mysql_insert_id() ?>">
<input type="hidden" name="responder_url" value="<?php echo $config['site_url'] . 'ipn.php?i=nochex'; ?>">
</form>
<div align="center" class="style1">Transfering you to the NoChex Secure payment system, if you are not forwarded <a href="javascript:document.nochex.submit();">click here</a></div>
</body>