<?php
require_once('includes/config.php');
require_once('includes/classes/class.template_engine.php');
require_once('includes/functions/func.global.php');
require_once('includes/functions/func.users.php');
session_start();
$config['lang'] = check_user_lang($config);
require_once('includes/lang/lang_'.$config['lang'].'.php');

db_connect($config);

check_cookie($_SESSION,$config);

if(checkloggedin())
{
	if(isset($_GET['delete']))
	{
		if($_SESSION['user']['type'] == 'buyer')
		{
			mysql_query("DELETE FROM `".$config['db']['pre']."block` WHERE `user_id` = '".$_SESSION['user']['id']."' AND `user_id2` = '".validate_input($_GET['delete'])."' AND `block_type` = 'buy' LIMIT 1");
		}
		else
		{
			mysql_query("DELETE FROM `".$config['db']['pre']."block` WHERE `user_id` = '".$_SESSION['user']['id']."' AND `user_id2` = '".validate_input($_GET['delete'])."' AND `block_type` = 'pro' LIMIT 1");
		}
	
		header("Location: blocked.php");
		exit;
	}
	
	if(isset($_GET['add']))
	{
		if($_SESSION['user']['type'] == 'buyer')
		{
			$user_check = mysql_num_rows(mysql_query("SELECT 1 FROM ".$config['db']['pre']."providers WHERE provider_id='".validate_input($_GET['add'])."' LIMIT 1"));
		
			if($user_check)
			{
				mysql_query("INSERT INTO `".$config['db']['pre']."block` (`block_type` ,`user_id` ,`user_id2`) VALUES ('buy', '".$_SESSION['user']['id']."', '".validate_input($_GET['add'])."');");
			}
		}
		else
		{
			$user_check = mysql_num_rows(mysql_query("SELECT 1 FROM ".$config['db']['pre']."buyers WHERE buyer_id='".validate_input($_GET['add'])."' LIMIT 1"));
		
			if($user_check)
			{
				mysql_query("INSERT INTO `".$config['db']['pre']."block` (`block_type` ,`user_id` ,`user_id2`) VALUES ('pro', '".$_SESSION['user']['id']."', '".validate_input($_GET['add'])."');");
			}
		}
	
		header("Location: blocked.php");
		exit;
	}

	$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/blocked.html");
	$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['BLOCKLIST']));
	
	$blocked = array();
	$count = 0;
	
	if($_SESSION['user']['type'] == 'buyer')
	{
		$query = "SELECT block_id,user_id2 FROM ".$config['db']['pre']."block WHERE user_id='".$_SESSION['user']['id']."' AND block_type='buy'";
	}
	else
	{
		$query = "SELECT block_id,user_id2 FROM ".$config['db']['pre']."block WHERE user_id='".$_SESSION['user']['id']."' AND block_type='pro'";
	}
	$query_result = mysql_query($query);
	while ($info = mysql_fetch_array($query_result))
	{
		if($_SESSION['user']['type'] == 'buyer')
		{
			$user_info = mysql_fetch_row(mysql_query("SELECT provider_username FROM `".$config['db']['pre']."providers` WHERE provider_id='" . $info['user_id2'] . "' LIMIT 1"));
		}
		else
		{
			$user_info = mysql_fetch_row(mysql_query("SELECT buyer_username FROM `".$config['db']['pre']."buyers` WHERE buyer_id='" . $info['user_id2'] . "' LIMIT 1"));
		}
	
		$blocked[$count]['user_id'] = $info['user_id2'];
		
		if(isset($user_info[0]))
		{
			$blocked[$count]['username'] = $user_info[0];
		}
		else
		{
			$blocked[$count]['username'] = '';
		}
		
		$count++;
	}
	
	$page->SetLoop ('BLOCKED', $blocked);
	
	$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
	$page->CreatePageEcho($lang,$config);
}
else
{
	header("Location: login.php");
	exit;
}
?>