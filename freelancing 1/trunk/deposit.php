<?php
require_once('includes/config.php');
require_once('includes/classes/class.template_engine.php');
require_once('includes/functions/func.global.php');
session_start();
$config['lang'] = check_user_lang($config);
require_once('includes/lang/lang_'.$config['lang'].'.php');
require_once('includes/functions/func.users.php');

db_connect($config);

check_cookie($_SESSION,$config);

if(checkloggedin())
{
	if(isset($_POST['Submit']))
	{
		$query = "SELECT payment_title,payment_cost,payment_settings,payment_minimum_deposit,payment_folder FROM `".$config['db']['pre']."payments` WHERE payment_id='" . validate_input($_POST['payment_id']) . "' AND payment_deposit='1' LIMIT 1";
		$query_result = @mysql_query ($query) OR error(mysql_error());
		while ($info = @mysql_fetch_array($query_result))
		{
			$title = $info['payment_title'];
			$cost = $info['payment_cost'];
			$settings = unserialize($info['payment_settings']);
			$min = $info['payment_minimum_deposit'];
			$folder = $info['payment_folder'];
		}
		
		if($_POST['amount'] < $min)
		{
			message($lang['DEPOSIT_TOO_LITTLE_ERROR'].' '.$config['currency_sign'] . $min,$config,$lang);
		}

		require_once('includes/payments/' . $folder . '/deposit.php');
	}
	else
	{
		$query = "SELECT payment_id,payment_title,payment_cost,payment_desc_deposit FROM `".$config['db']['pre']."payments` WHERE payment_deposit='1' ORDER BY  payment_title";
		$query_result = @mysql_query ($query) OR error(mysql_error());
		while ($info = @mysql_fetch_array($query_result))
		{
			$payment_types[$info['payment_id']]['id'] = $info['payment_id'];
			$payment_types[$info['payment_id']]['title'] = $info['payment_title'];
			$payment_types[$info['payment_id']]['cost'] = $info['payment_cost'];
			$payment_types[$info['payment_id']]['desc'] = $info['payment_desc_deposit'];
		}
		
		$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/deposit.html");
		$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['DEPOSIT']));
		$page->SetLoop ('PAYMENT_TYPES', $payment_types);
		if(isset($_GET['warning']))
		{
			$balance = mysql_fetch_row(mysql_query("SELECT balance_amount FROM `".$config['db']['pre'].$_SESSION['user']['type'] . "s_balance` WHERE " . $_SESSION['user']['type'] . "_id = '" . $_SESSION['user']['id'] . "' LIMIT 1"));
			$page->SetParameter ('WARNING', 1);
			$page->SetParameter ('BALANCE', str_replace('-','',$balance[0]));
		}
		else
		{
			$page->SetParameter ('WARNING', 0);
			$page->SetParameter ('BALANCE', '');
		}
		$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
		$page->CreatePageEcho($lang,$config);
	}
}
else
{
	header("Location: login.php");
	exit;
}
?>