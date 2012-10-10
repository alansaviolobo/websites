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

mysql_query("INSERT INTO `".$config['db']['pre']."transactions` ( `transaction_id` , `transaction_status` , `transaction_type` , `transaction_method` , `buyer_id` , `provider_id` , `transaction_ip` , `transaction_time` , `transaction_amount` , `transaction_description` , `transaction_proc` , `transaction_settings` ) VALUES ('', '1', '" . $type . "', 'deposit', '" . $buyer_id . "', '" . $provider_id . "', '" . encode_ip($_SERVER,$_ENV) . "', '" . time() . "', '" . $_POST['amount'] . "', 'egold Deposit', '" . $_POST['payment_id'] . "', '');");

if( ($settings['deposit_cost'] != '') AND ($settings['deposit_cost'] != '0') )
{
	$_POST['amount'] = $_POST['amount'] + $settings['deposit_cost'];
}

if( ($settings['deposit_percentage'] != '') AND ($settings['deposit_percentage'] != '0') )
{
	$_POST['amount'] = ($_POST['amount'] / 100) * (100 + $settings['deposit_percentage']);
}

$_POST['amount'] = round($_POST['amount'], 2);

switch ($config['currency_code']) {
  case "USD": $currency=1;break;
  case "CAD": $currency=2;break;
  case "EUR": $currency=85;break;
  default: $currency=1;
}
?>
<style type="text/css">
<!--
.style1 {
	font-size: 14px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
-->
</style>

<body onLoad="javascript:document.egold.submit();">
<form name="egold" action="https://www.e-gold.com/sci_asp/payments.asp" method="POST">
    <div align="center">
        <input type="hidden" name="PAYEE_ACCOUNT" value="<?php echo $settings['egold_account'];?>">
        <input type="hidden" name="PAYEE_NAME" value="<?php echo $config['site_title']; ?>">
        <input type="hidden" name="PAYMENT_ID" value="<?php echo mysql_insert_id() ?>">
        <input type="hidden" name="PAYMENT_AMOUNT" value="<?php echo $_POST['amount']; ?>">
            <input type=hidden name="PAYMENT_UNITS" value="<?php echo $currency  ?>">
        <input type=hidden name="PAYMENT_METAL_ID" value=1>
        <input type="hidden" name="STATUS_URL" value="<?php echo $config['site_url'] . 'ipn.php?i=egold'; ?>">
        <input type="hidden" name="NOPAYMENT_URL" value="<?php echo $config['site_url'] . 'manage.php'; ?>">
        <input type="hidden" name="NOPAYMENT_URL_METHOD" value="POST">
        <input type="hidden" name="PAYMENT_URL"
            value="<?php echo $config['site_url'] . 'manage.php'; ?>">
        <input type="hidden" name="PAYMENT_URL_METHOD" value="POST">
        <input type="hidden" name="BAGGAGE_FIELDS" value="Description">
        <input type="hidden" name="Description" value="Deposit into <?php echo $config['site_title']; ?>">
        <input type="hidden" name="SUGGESTED_MEMO" value='Thanks for paying with e-gold!'>
        <br>
        <input type="submit" name="PAYMENT_METHOD" value="Pay!">
    </div>
</form>
    
<div align="center" class="style1">Transfering you to the egold.com Secure payment system, if you are not forwarded <a href="javascript:document.egold.submit();">click here</a></div>
</body>