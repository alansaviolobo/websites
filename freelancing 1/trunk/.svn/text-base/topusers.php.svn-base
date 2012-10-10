<?php
require_once('includes/config.php');
require_once('includes/classes/class.template_engine.php');
require_once('includes/functions/func.global.php');
session_start();
$config['lang'] = check_user_lang($config);
require_once('includes/lang/lang_'.$config['lang'].'.php');

db_connect($config);

check_cookie($_SESSION,$config);

$users = array();
$user_type = '';

if(!isset($_GET['type']))
{
	$_GET['type'] = 'buyer';
}

if(!isset($_GET['page']))
{
	$_GET['page'] = 1;
}

if(!isset($_GET['sort']))
{
	$_GET['sort'] = 'rating';
}

$count = (($_GET['page']-1)*20);

if($_GET['type'] == 'buyer')
{
	$total = mysql_num_rows(mysql_query("SELECT 1 FROM `".$config['db']['pre']."buyers`"));

	if($_GET['sort'] == 'reviews')
	{
		$query = "SELECT buyer_id,buyer_username,buyer_rating,buyer_reviews FROM `".$config['db']['pre']."buyers` ORDER BY buyer_reviews DESC,buyer_rating DESC LIMIT ".validate_input(($_GET['page']-1)*20).",20";
	}
	elseif($_GET['sort'] == 'username')
	{
		$query = "SELECT buyer_id,buyer_username,buyer_rating,buyer_reviews FROM `".$config['db']['pre']."buyers` ORDER BY buyer_username ASC,buyer_rating DESC LIMIT ".validate_input(($_GET['page']-1)*20).",20";
	}
	else
	{
		$query = "SELECT buyer_id,buyer_username,buyer_rating,buyer_reviews FROM `".$config['db']['pre']."buyers` ORDER BY buyer_rating DESC,buyer_reviews DESC LIMIT ".validate_input(($_GET['page']-1)*20).",20";
	}	
	$query_result = @mysql_query ($query) OR error(mysql_error());
	while ($info = @mysql_fetch_array($query_result))
	{
		$users[$info['buyer_id']]['count'] = ($count+1);
		$users[$info['buyer_id']]['id'] = $info['buyer_id'];
		$users[$info['buyer_id']]['username'] = $info['buyer_username'];
		$users[$info['buyer_id']]['rating'] = $info['buyer_rating'];
		$users[$info['buyer_id']]['reviews'] = $info['buyer_reviews'];
		$users[$info['buyer_id']]['type'] = 'buyer';
		$count++;
	}
	
	$user_type = $lang['BUYERU'];
}
else
{
	$total = mysql_num_rows(mysql_query("SELECT 1 FROM `".$config['db']['pre']."providers`"));

	if($_GET['sort'] == 'reviews')
	{
		$query = "SELECT provider_id,provider_username,provider_rating,provider_reviews FROM `".$config['db']['pre']."providers` ORDER BY provider_reviews DESC,provider_rating DESC LIMIT ".validate_input(($_GET['page']-1)*20).",20";
	}
	elseif($_GET['sort'] == 'username')
	{
		$query = "SELECT provider_id,provider_username,provider_rating,provider_reviews FROM `".$config['db']['pre']."providers` ORDER BY provider_username ASC,provider_rating DESC LIMIT ".validate_input(($_GET['page']-1)*20).",20";
	}
	else
	{
		$query = "SELECT provider_id,provider_username,provider_rating,provider_reviews FROM `".$config['db']['pre']."providers` ORDER BY provider_rating DESC,provider_reviews DESC LIMIT ".validate_input(($_GET['page']-1)*20).",20";
	}
	$query_result = @mysql_query ($query) OR error(mysql_error());
	while ($info = @mysql_fetch_array($query_result))
	{
		$users[$info['provider_id']]['count'] = ($count+1);
		$users[$info['provider_id']]['id'] = $info['provider_id'];
		$users[$info['provider_id']]['username'] = $info['provider_username'];
		$users[$info['provider_id']]['rating'] = $info['provider_rating'];
		$users[$info['provider_id']]['reviews'] = $info['provider_reviews'];
		$users[$info['provider_id']]['type'] = 'provider';
		$count++;
	}
	
	$user_type = $lang['PROVIDERU'];
}

$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/topusers.html");
$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['TOP'].' '.$user_type.'s'));
$page->SetLoop ('USERS', $users);
$page->SetLoop ('PAGES', pagenav($total,$_GET['page'],20,$config['site_url'].'topusers.php?type='.$_GET['type'].'&sort='.$_GET['sort'],1));
$page->SetParameter ('USER_TYPE', $user_type);
$page->SetParameter ('UTYPE', $_GET['type']);
$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
$page->CreatePageEcho($lang,$config);
?>