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
?>
<style type="text/css">
<!--
.style1 {
	font-size: 14px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
-->
</style>
<body onLoad="javascript:document.paypal.submit();">
<form name="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_xclick-subscriptions">
<input type="hidden" name="business" value="<?php echo $settings['paypal_address'];?>">
<input type="hidden" name="item_name" value="<?php echo $title; ?>">
<input type="hidden" name="custom" value="<?=$_SESSION['user']['id'].'|'.$_SESSION['user']['type'].'|'.$_POST['upgrade'];?>">
<input type="hidden" name="return" value="<?php echo $config['site_url'] . 'manage.php'; ?>">
<input type="hidden" name="notify_url" value="<?php echo $config['site_url'] . 'recuripn.php?i=paypal'; ?>">
<input type="hidden" name="currency_code" value="<?php echo $config['currency_code']; ?>">
<input type="hidden" name="a3" value="<?php echo $amount; ?>">
<input type="hidden" name="p3" value="1">
<input type="hidden" name="t3" value="<?php if($term == 'YEARLY'){ echo 'Y'; }elseif($term == 'DAILY'){ echo 'D'; } else { echo 'M'; } ?>">
<input type="hidden" name="src" value="1">
<input type="hidden" name="sra" value="1">
<input type="hidden" name="no_shipping" value="1">
<input type="hidden" name="no_note" value="1">
</form>
<div align="center" class="style1">Transfering you to the Paypal.com Secure payment system, if you are not forwarded <a href="javascript:document.paypal.submit();">click here</a></div>
</body>