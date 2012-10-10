<?php
require_once('includes/config.php');
require_once('includes/classes/class.template_engine.php');
require_once('includes/functions/func.global.php');
$config['lang'] = check_user_lang($config);
require_once('includes/lang/lang_'.$config['lang'].'.php');

db_connect($config);
session_start();
check_cookie($_SESSION,$config);

check_negative_balance($config);

$messages = array();

$query = "SELECT message_id,from_id,from_type,to_id,message_content,message_date FROM `".$config['db']['pre']."messages` WHERE project_id='" . validate_input($_GET['i']) . "'";
$query_result = @mysql_query ($query) OR error(mysql_error());
while ($info = @mysql_fetch_array($query_result))
{
	$messages[$info['message_id']]['id'] = $info['message_id'];
	$messages[$info['message_id']]['date'] = date("n/j/Y \a\t G:i T", $info['message_date']);
	$messages[$info['message_id']]['from_id'] = $info['from_id'];
	$messages[$info['message_id']]['to_id'] = $info['to_id'];
	$messages[$info['message_id']]['details'] = substr(strip_tags(message_content($info['message_content'], $info['to_id'], $info['from_id'], $info['from_type'], $_SESSION,$lang)),0,2000);
	
	if($info['from_type'] == 0)
	{
		$messages[$info['message_id']]['from_type'] = $lang['BUYERU'];
		$messages[$info['message_id']]['from_username'] = 'Unknown';
		$messages[$info['message_id']]['to_username'] = 'Unknown';
		
		$query2 = "SELECT buyer_username FROM `".$config['db']['pre']."buyers` WHERE buyer_id='" . $info['from_id'] . "' LIMIT 1";
		$query_result2 = @mysql_query ($query2) OR error(mysql_error());
		while ($info2 = @mysql_fetch_array($query_result2))
		{
			$messages[$info['message_id']]['from_username'] = $info2['buyer_username'];
		}
		
		$query2 = "SELECT provider_username FROM `".$config['db']['pre']."providers` WHERE provider_id='" . $info['to_id'] . "' LIMIT 1";
		$query_result2 = @mysql_query ($query2) OR error(mysql_error());
		while ($info2 = @mysql_fetch_array($query_result2))
		{
			$messages[$info['message_id']]['to_username'] = $info2['provider_username'];
		}
	}
	else
	{
		$messages[$info['message_id']]['from_type'] = $lang['PROVIDERU'];
		$messages[$info['message_id']]['from_username'] = 'Unknown';
		$messages[$info['message_id']]['to_username'] = 'Unknown';
		
		$query2 = "SELECT buyer_username FROM `".$config['db']['pre']."buyers` WHERE buyer_id='" . $info['to_id'] . "' LIMIT 1";
		$query_result2 = @mysql_query ($query2) OR error(mysql_error());
		while ($info2 = @mysql_fetch_array($query_result2))
		{
			$messages[$info['message_id']]['to_username'] = $info2['buyer_username'];
		}
		
		$query2 = "SELECT provider_username FROM `".$config['db']['pre']."providers` WHERE provider_id='" . $info['from_id'] . "' LIMIT 1";
		$query_result2 = @mysql_query ($query2) OR error(mysql_error());
		while ($info2 = @mysql_fetch_array($query_result2))
		{
			$messages[$info['message_id']]['from_username'] = $info2['provider_username'];
		}
	}
}

$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/board.html");
$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang));
$page->SetLoop ('MESSAGES', $messages);
$page->SetParameter ('PROJECT_ID', $_GET['i']);
$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
$page->CreatePageEcho($lang,$config);
?>