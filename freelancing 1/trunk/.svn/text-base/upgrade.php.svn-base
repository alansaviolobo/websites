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
	$check_upgrade = mysql_num_rows(mysql_query("SELECT * FROM ".$config['db']['pre']."upgrades WHERE user_id='".validate_input($_SESSION['user']['id'])."' AND user_type='".validate_input($_SESSION['user']['type'])."' LIMIT 1"));

	if($check_upgrade)
	{
		$upgrades = array();
	
		$query = "SELECT * FROM `".$config['db']['pre']."upgrades` WHERE user_id='".validate_input($_SESSION['user']['id'])."' AND user_type='".validate_input($_SESSION['user']['type'])."'";
		$query_result = @mysql_query ($query) OR error(mysql_error());
		while ($info = @mysql_fetch_array($query_result))
		{
			$sub_info = mysql_fetch_array(mysql_query("SELECT * FROM `".$config['db']['pre']."subscriptions` WHERE sub_id='".validate_input($info['sub_id'])."' LIMIT 1"));
		
			$upgrades[$info['upgrade_id']]['id'] = $info['upgrade_id'];
			$upgrades[$info['upgrade_id']]['title'] = $sub_info['sub_title'];
			$upgrades[$info['upgrade_id']]['cost'] = $sub_info['sub_amount'];
			
			if($sub_info['sub_term'] == 'DAILY')
			{
				$upgrades[$info['upgrade_id']]['term'] = 'Daily';
			}
			elseif($sub_info['sub_term'] == 'MONTHLY')
			{
				$upgrades[$info['upgrade_id']]['term'] = 'Monthly';
			}
			elseif($sub_info['sub_term'] == 'YEARLY')
			{
				$upgrades[$info['upgrade_id']]['term'] = 'Yearly';
			}
		}
	
		$page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/subscriptions.html');
		$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['UPGRADES']));
		$page->SetLoop ('UPGRADES', $upgrades);
		$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
		$page->CreatePageEcho($lang,$config);
		exit;
	}

	if(isset($_POST['upgrade']))
	{		
		if(isset($_POST['Submit']))
		{
			$query = "SELECT payment_title,payment_cost,payment_settings,payment_minimum_deposit,payment_folder FROM `".$config['db']['pre']."payments` WHERE payment_id='" . validate_input($_POST['payment_id']) . "' AND payment_subscription='1' LIMIT 1";
			$query_result = @mysql_query ($query) OR error(mysql_error());
			while ($info = @mysql_fetch_array($query_result))
			{
				$settings = unserialize($info['payment_settings']);
				$folder = $info['payment_folder'];
			}
			
			if($_SESSION['user']['type'] == 'buyer')
			{
				$query = "SELECT * FROM `".$config['db']['pre']."subscriptions` WHERE sub_type='buy' AND sub_id='".validate_input($_POST['upgrade'])."' LIMIT 1";
			}
			else
			{
				$query = "SELECT * FROM `".$config['db']['pre']."subscriptions` WHERE sub_type='pro' AND sub_id='".validate_input($_POST['upgrade'])."' LIMIT 1";
			}
			$query_result = @mysql_query ($query) OR error(mysql_error());
			while ($info = @mysql_fetch_array($query_result))
			{
				$title = $info['sub_title'];
				$amount = $info['sub_amount'];
				$term = $info['sub_term'];
			}
	
			require_once('includes/payments/' . $folder . '/subscription.php');
		}
		else
		{		
			$payment_types = array();
		
			$query = "SELECT payment_id,payment_title,payment_cost,payment_desc_subscription FROM `".$config['db']['pre']."payments` WHERE payment_subscription='1' ORDER BY  payment_title";
			$query_result = @mysql_query ($query) OR error(mysql_error());
			while ($info = @mysql_fetch_array($query_result))
			{
				$payment_types[$info['payment_id']]['id'] = $info['payment_id'];
				$payment_types[$info['payment_id']]['title'] = $info['payment_title'];
				$payment_types[$info['payment_id']]['cost'] = $info['payment_cost'];
				$payment_types[$info['payment_id']]['desc'] = $info['payment_desc_subscription'];
			}
			
			$page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/subscription_options.html');
			$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['UPGRADES']));
			$page->SetLoop ('PAYMENT_TYPES', $payment_types);
			$page->SetParameter ('UPGRADE', $_POST['upgrade']);
			$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
			$page->CreatePageEcho($lang,$config);
		}
	}
	else
	{
		$sub_types = array();

		if($_SESSION['user']['type'] == 'buyer')
		{
			$query = "SELECT * FROM `".$config['db']['pre']."subscriptions` WHERE sub_type='buy'";
		}
		else
		{
			$query = "SELECT * FROM `".$config['db']['pre']."subscriptions` WHERE sub_type='pro'";
		}
		$query_result = @mysql_query ($query) OR error(mysql_error());
		while ($info = @mysql_fetch_array($query_result))
		{
			$sub_types[$info['sub_id']]['id'] = $info['sub_id'];
			$sub_types[$info['sub_id']]['title'] = $info['sub_title'];
			$sub_types[$info['sub_id']]['cost'] = $info['sub_amount'];
			$sub_types[$info['sub_id']]['desc'] = $info['sub_desc'];
		}
				
		$page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/subscription.html');
		$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['UPGRADES']));
		$page->SetLoop ('SUB_TYPES', $sub_types);
		$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
		$page->CreatePageEcho($lang,$config);
	}
}
else
{
	header('Location: login.php');
	exit;
}
?>