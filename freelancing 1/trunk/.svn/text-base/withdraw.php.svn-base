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
		$query = "SELECT payment_title,payment_folder,payment_cost_withdraw,payment_settings,payment_minimum_withdraw FROM `".$config['db']['pre']."payments` WHERE payment_id='" . validate_input($_POST['payment_id']) . "' AND payment_withdraw='1' LIMIT 1";
		$query_result = @mysql_query ($query) OR error(mysql_error());
		while ($info = @mysql_fetch_array($query_result))
		{
			$title = $info['payment_title'];
			$cost = $info['payment_cost_withdraw'];
			$settings = unserialize($info['payment_settings']);
			$min = $info['payment_minimum_withdraw'];
			$folder = $info['payment_folder'];
		}

		if($_POST['amount'] <= $min)
		{
			message($lang['WITHDRAW_TOO_LITTLE_ERROR'].' $' . $min,$config,$lang,'');
		}
		
		
		$query = "SELECT balance_amount FROM `".$config['db']['pre']."".$_SESSION['user']['type']."s_balance` WHERE ".$_SESSION['user']['type']."_id='" . $_SESSION['user']['id'] . "' LIMIT 1";
		$query_result = @mysql_query ($query) OR error(mysql_error());
		while ($info = @mysql_fetch_array($query_result))
		{
			$balance = $info['balance_amount'];
		}
		
		if($_POST['amount'] > $balance)
		{
			message($lang['WITHDRAW_NO_MONEY'],$config,$lang,'');
		}

		require_once('includes/payments/' . strtolower($folder) . '/withdraw.php');
	}
	else
	{
		$query = "SELECT payment_id,payment_title,payment_cost_withdraw,payment_desc_withdraw FROM `".$config['db']['pre']."payments` WHERE payment_withdraw='1'";
		$query_result = @mysql_query ($query) OR error(mysql_error());
		while ($info = @mysql_fetch_array($query_result))
		{
			$payment_types[$info['payment_id']]['id'] = $info['payment_id'];
			$payment_types[$info['payment_id']]['title'] = $info['payment_title'];
			$payment_types[$info['payment_id']]['cost'] = $info['payment_cost_withdraw'];
			$payment_types[$info['payment_id']]['desc'] = $info['payment_desc_withdraw'];
		}
		
		$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/withdraw.html");
		$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['WITHTITLE']));
		$page->SetLoop ('PAYMENT_TYPES', $payment_types);
		$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
		$page->CreatePageEcho($lang,$config);
	}
}
ELSE
{
	header("Location: login.php");
	exit;
}
?>