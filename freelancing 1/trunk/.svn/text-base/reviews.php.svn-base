<?php
require_once('includes/config.php');
require_once('includes/classes/class.template_engine.php');
require_once('includes/functions/func.global.php');
$config['lang'] = check_user_lang($config);
require_once('includes/lang/lang_'.$config['lang'].'.php');


db_connect($config);

session_start();
check_cookie($_SESSION,$config);

$reviews = array();

if($_GET['type'] == 'provider')
{
	$query = "SELECT provider_username FROM `".$config['db']['pre']."providers` WHERE provider_id='" . validate_input($_GET['id']) . "' LIMIT 1";
	$query_result = @mysql_query ($query) OR error(mysql_error());
	while ($info = @mysql_fetch_array($query_result))
	{
		$username = $info['provider_username'];
	}

	$reviews = array();

	$query = "SELECT review_id,review_comment,review_rating,buyer_id,project_id FROM `".$config['db']['pre']."reviews` WHERE provider_id='" . validate_input($_GET['id']) . "' AND review_type='0'";
	$query_result = @mysql_query ($query) OR error(mysql_error());
	while ($info = @mysql_fetch_array($query_result))
	{
		$reviews[$info['review_id']]['date'] = '';
		$reviews[$info['review_id']]['time'] = '';
		$reviews[$info['review_id']]['timezone'] = '';
		$reviews[$info['review_id']]['project_title'] = '';
		$reviews[$info['review_id']]['username'] = '';
		$reviews[$info['review_id']]['link'] = '';
	
		$query2 = "SELECT project_title,project_end FROM `".$config['db']['pre']."projects` WHERE project_id='" . $info['project_id'] . "' LIMIT 1";
		$query_result2 = @mysql_query ($query2) OR error(mysql_error());
		while ($info2 = @mysql_fetch_array($query_result2))
		{
			$reviews[$info['review_id']]['date'] = date("n/j/Y", $info2['project_end']);
			$reviews[$info['review_id']]['time'] = date("G:i", $info2['project_end']);
			$reviews[$info['review_id']]['timezone'] = date("T", $info2['project_end']);
			
			$reviews[$info['review_id']]['project_title'] = strip_tags(stripslashes(substr($info2['project_title'],0,25)));
			
			if($config['mod_rewrite'] == 0)
			{
				$reviews[$info['review_id']]['link'] = 'project.php?id=' . $info['project_id'];
			}
			else
			{
				$reviews[$info['review_id']]['link'] = 'projects/' . $info['project_id'] . '/'.convert2link(stripslashes($info2['project_title'])).'/';
			}
		}
	
		$query2 = "SELECT buyer_username FROM `".$config['db']['pre']."buyers` WHERE buyer_id='" . $info['buyer_id'] . "' LIMIT 1";
		$query_result2 = @mysql_query ($query2) OR error(mysql_error());
		while ($info2 = @mysql_fetch_array($query_result2))
		{
			$reviews[$info['review_id']]['username'] = $info2['buyer_username'];
		}
		
		$reviews[$info['review_id']]['user_id'] = $info['buyer_id'];
		$reviews[$info['review_id']]['comment'] = $info['review_comment'];
		$reviews[$info['review_id']]['project_id'] = $info['project_id'];
		$reviews[$info['review_id']]['rating'] = $info['review_rating'];
	}
	
	$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/reviews_provider.html");
	$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['REVIEWS']));
	$page->SetParameter ('USERNAME', $username);
	$page->SetLoop ('REVIEWS', $reviews);
	$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
	$page->CreatePageEcho($lang,$config);
}
else
{
	$query = "SELECT buyer_username FROM `".$config['db']['pre']."buyers` WHERE buyer_id='" . validate_input($_GET['id']) . "' LIMIT 1";
	$query_result = @mysql_query ($query) OR error(mysql_error());
	while ($info = @mysql_fetch_array($query_result))
	{
		$username = $info['buyer_username'];
	}

	$reviews = array();
	
	$query = "SELECT review_id,review_comment,review_rating,project_id,provider_id FROM `".$config['db']['pre']."reviews` WHERE buyer_id='" . validate_input($_GET['id']) . "' AND review_type='1'";
	$query_result = @mysql_query ($query) OR error(mysql_error());
	while ($info = @mysql_fetch_array($query_result))
	{
		$reviews[$info['review_id']]['date'] = '';
		$reviews[$info['review_id']]['time'] = '';
		$reviews[$info['review_id']]['timezone'] = '';
		$reviews[$info['review_id']]['project_title'] = '';
		$reviews[$info['review_id']]['username'] = '';
		$reviews[$info['review_id']]['link'] = '';
	
		$query2 = "SELECT project_title,project_end FROM `".$config['db']['pre']."projects` WHERE project_id='" . $info['project_id'] . "' LIMIT 1";
		$query_result2 = @mysql_query ($query2) OR error(mysql_error());
		while ($info2 = @mysql_fetch_array($query_result2))
		{
			$reviews[$info['review_id']]['date'] = date("n/j/Y", $info2['project_end']);
			$reviews[$info['review_id']]['time'] = date("G:i", $info2['project_end']);
			$reviews[$info['review_id']]['timezone'] = date("T", $info2['project_end']);
			
			$reviews[$info['review_id']]['project_title'] = strip_tags(stripslashes(substr($info2['project_title'],0,25)));
			
			if($config['mod_rewrite'] == 0)
			{
				$reviews[$info['review_id']]['link'] = 'project.php?id=' . $info['project_id'];
			}
			else
			{
				$reviews[$info['review_id']]['link'] = 'projects/' . $info['project_id'] . '/'.convert2link(stripslashes($info2['project_title'])).'/';
			}
		}
		
		$query2 = "SELECT provider_username FROM `".$config['db']['pre']."providers` WHERE provider_id='" . $info['provider_id'] . "' LIMIT 1";
		$query_result2 = @mysql_query ($query2) OR error(mysql_error());
		while ($info2 = @mysql_fetch_array($query_result2))
		{
			$reviews[$info['review_id']]['username'] = $info2['provider_username'];
		}
		
		$reviews[$info['review_id']]['user_id'] = $info['provider_id'];
		$reviews[$info['review_id']]['comment'] = $info['review_comment'];
		$reviews[$info['review_id']]['project_id'] = $info['project_id'];
		$reviews[$info['review_id']]['rating'] = $info['review_rating'];
	}

	$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/reviews_buyer.html");
	$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['REVIEWS']));
	$page->SetParameter ('USERNAME', $username);
	$page->SetLoop ('REVIEWS', $reviews);
	$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
	$page->CreatePageEcho($lang,$config);
}
?>