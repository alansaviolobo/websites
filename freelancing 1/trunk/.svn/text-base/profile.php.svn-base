<?php
require_once('includes/config.php');
require_once('includes/classes/class.template_engine.php');
require_once('includes/functions/func.global.php');
session_start();
$config['lang'] = check_user_lang($config);
require_once('includes/lang/lang_'.$config['lang'].'.php');

db_connect($config);

check_cookie($_SESSION,$config);

if(!isset($_GET['page']))
{
	$_GET['page'] = 1;
}

$profile_custom = array();

if($_GET['type'] == 'provider')
{
	$types = array();

	$query2 = "SELECT provider_username,provider_joined,provider_name,provider_price,provider_profile,provider_types,provider_pictype,provider_paypal,provider_custom_fields,provider_custom_values FROM `".$config['db']['pre']."providers` WHERE provider_id='" . validate_input($_GET['id']) . "' LIMIT 1";
	$query_result2 = @mysql_query ($query2) OR error(mysql_error());
	while ($info2 = @mysql_fetch_array($query_result2))
	{
		$username = $info2['provider_username'];
		$joined = $info2['provider_joined'];
		$name = strip_tags(stripslashes(substr($info2['provider_name'],0,200)));
		$price = $info2['provider_price'];
		$profile = strip_tags(stripslashes(substr($info2['provider_profile'],0,2000)));
		$types2 = explode(',', $info2['provider_types']);
		$pictype = $info2['provider_pictype'];
		
		$custom_fields = explode(',',$info2['provider_custom_fields']);
		$custom_data = explode(',',$info2['provider_custom_values']);
	}
	
	$count = 2;
	
	foreach($custom_fields as $key=>$value)
	{
		if($value != '')
		{
			$profile_custom[$count]['title'] = stripslashes($value);
			$profile_custom[$count]['value'] = stripslashes($custom_data[$key]);
			$profile_custom[$count]['count'] = $count;
			
			$count++;
		}
	}
	
	if(!isset($username))
	{
		exit($lang['SORRYUSER']);
	}
	
	$reviews = array();

	$query = "SELECT review_id,review_comment,review_rating,buyer_id,project_id FROM `".$config['db']['pre']."reviews` WHERE provider_id='" . validate_input($_GET['id']) . "' AND review_type='0' LIMIT ".(($_GET['page']-1)*10).",10";
	$query_result = @mysql_query ($query) OR error(mysql_error());
	while ($info = @mysql_fetch_array($query_result))
	{	
		$reviews[$info['review_id']]['project_title'] = 'Project Removed';
		$reviews[$info['review_id']]['date'] = '';
		$reviews[$info['review_id']]['time'] = '';
		$reviews[$info['review_id']]['timezone'] = '';
		$reviews[$info['review_id']]['user_link'] = '';
	
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
			
			if($config['mod_rewrite'] == 0)
			{
				$reviews[$info['review_id']]['user_link'] = $config['site_url'].'profile.php?type=buyer&id='.$info['buyer_id'];
			}
			else
			{
				$reviews[$info['review_id']]['user_link'] = $config['site_url'].'buyers/'.$info['buyer_id'].'/'.$info2['buyer_username'].'/';
			}
		}
		
		$reviews[$info['review_id']]['user_id'] = $info['buyer_id'];
		$reviews[$info['review_id']]['comment'] = $info['review_comment'];
		$reviews[$info['review_id']]['project_id'] = $info['project_id'];
		$reviews[$info['review_id']]['rating'] = $info['review_rating'];
	}

	if(count($types2) > 0)
	{
		$query3 = "SELECT cat_id,cat_name FROM `".$config['db']['pre']."categories`";
		$query_result3 = @mysql_query ($query3) OR error(mysql_error());
		while ($info3 = @mysql_fetch_array($query_result3))
		{
			$cats[$info3['cat_id']] = $info3['cat_name'];
		}
	}

	$count = 0;

	foreach ($types2 as $value) 
	{
		$count++;
		
		$types[$count]['title'] = $cats[$value];
	}
	
	$feedback_info = get_feedback($_GET['id'],'provider',$config);

	$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/profile_provider.html");
	$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$username));
	$page->SetParameter ('ID', $_GET['id']);
	if($pictype == '')
	{
		$page->SetParameter ('IMAGE', 0);
	}
	else
	{
		$page->SetParameter ('IMAGE', 1);
	}
	if($config['mailbox_en'])
	{
		$page->SetParameter('MAILBOX_SHOW',1);
	}
	else
	{
		$page->SetParameter('MAILBOX_SHOW',0);
	}
	if(isset($_SESSION['user']['id']))
	{
		if($_SESSION['user']['type'] == 'buyer')
		{
			$block_check = mysql_num_rows(mysql_query("SELECT 1 FROM `".$config['db']['pre']."block` WHERE `user_id` = '".$_SESSION['user']['id']."' AND `user_id2` = '".validate_input($_GET['id'])."' AND `block_type` = 'buy' LIMIT 1"));
		
			if($block_check)
			{
				$page->SetParameter('SHOW_BLOCK',0);
				$page->SetParameter('SHOW_UNBLOCK',1);
			}
			else
			{
				$page->SetParameter('SHOW_BLOCK',1);
				$page->SetParameter('SHOW_UNBLOCK',0);
			}
		}
		else
		{
			$page->SetParameter('SHOW_BLOCK',0);
			$page->SetParameter('SHOW_UNBLOCK',0);
		}
	}
	else
	{
		$page->SetParameter('SHOW_BLOCK',0);
		$page->SetParameter('SHOW_UNBLOCK',0);
	}
	$page->SetParameter ('USERNAME', $username);
	$page->SetParameter ('NAME', $name);
	$page->SetParameter ('JOINED', date("n/j/Y", $joined));
	$page->SetParameter ('PRICING', $price);
	$page->SetParameter ('PROFILE', $profile);
	$page->SetParameter ('REVIEWS', $feedback_info['reviews']);
	$page->SetParameter ('RATING', $feedback_info['rating']);
	$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
	$page->SetLoop ('PROFILE_CUSTOM', $profile_custom);
	$page->SetLoop ('REVIEWS', $reviews);
	$page->SetLoop ('EXPERTISE', $types);
	$page->SetLoop ('PAGES', pagenav($feedback_info['reviews'],$_GET['page'],10,$config['site_url'].'profile.php?id='.$_GET['id'].'&type='.$_GET['type'],1));
	$page->SetParameter ('PAGE', $_GET['page']);
	$page->SetParameter ('PAGE_TOTAL', ceil($feedback_info['reviews']/10));
	$page->CreatePageEcho($lang,$config);
}
else
{
	$projects = array();

	$query2 = "SELECT buyer_username,buyer_name,buyer_joined,buyer_custom_fields,buyer_custom_values FROM `".$config['db']['pre']."buyers` WHERE buyer_id='" . validate_input($_GET['id']) . "' LIMIT 1";
	$query_result2 = @mysql_query ($query2) OR error(mysql_error());
	while ($info2 = @mysql_fetch_array($query_result2))
	{
		$username = $info2['buyer_username'];
		$name = strip_tags(stripslashes(substr($info2['buyer_name'],0,25)));
		$joined = $info2['buyer_joined'];
		
		$custom_fields = explode(',',$info2['buyer_custom_fields']);
		$custom_data = explode(',',$info2['buyer_custom_values']);
	}
	
	$count = 2;
	
	foreach($custom_fields as $key=>$value)
	{
		if($value != '')
		{
			$profile_custom[$count]['title'] = stripslashes($value);
			$profile_custom[$count]['value'] = stripslashes($custom_data[$key]);
			$profile_custom[$count]['count'] = $count;
			
			$count++;
		}
	}

	
	if(!isset($username))
	{
		exit($lang['SORRYUSER']);
	}
	
	$reviews = array();
	
	$query = "SELECT review_id,review_comment,review_rating,project_id,provider_id FROM `".$config['db']['pre']."reviews` WHERE buyer_id='" . validate_input($_GET['id']) . "' AND review_type='1' LIMIT ".(($_GET['page']-1)*10).",10";
	$query_result = @mysql_query ($query) OR error(mysql_error());
	while ($info = @mysql_fetch_array($query_result))
	{
		$reviews[$info['review_id']]['project_title'] = $lang['PROJREM'];
		$reviews[$info['review_id']]['date'] = '';
		$reviews[$info['review_id']]['time'] = '';
		$reviews[$info['review_id']]['timezone'] = '';
		$reviews[$info['review_id']]['user_link'] = '';
	
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
			
			if($config['mod_rewrite'] == 0)
			{
				$reviews[$info['review_id']]['user_link'] = $config['site_url'].'profile.php?type=provider&id='.$info['provider_id'];
			}
			else
			{
				$reviews[$info['review_id']]['user_link'] = $config['site_url'].'providers/'.$info['provider_id'].'/'.$info2['provider_username'].'/';
			}
		}
		
		$reviews[$info['review_id']]['user_id'] = $info['provider_id'];
		$reviews[$info['review_id']]['comment'] = $info['review_comment'];
		$reviews[$info['review_id']]['project_id'] = $info['project_id'];
		$reviews[$info['review_id']]['rating'] = $info['review_rating'];
	}
	
	$count = 0;
	
	$query2 = "SELECT project_id,project_title FROM `".$config['db']['pre']."projects` WHERE buyer_id='" . validate_input($_GET['id']) . "' AND project_status='0'";
	$query_result2 = @mysql_query ($query2) OR error(mysql_error());
	while ($info2 = @mysql_fetch_array($query_result2))
	{
		$projects[$count]['title'] = strip_tags(stripslashes($info2['project_title']));
		$projects[$count]['id'] = $info2['project_id'];
		
		if($config['mod_rewrite'] == 0)
		{
			$projects[$count]['link'] = $config['site_url'].'project.php?id=' . $info2['project_id'];
		}
		else
		{
			$projects[$count]['link'] = $config['site_url'].'projects/' . $info2['project_id'] . '/'.convert2link(stripslashes($info2['project_title'])).'/';
		}
		
		$count++;
	}

	$feedback_info = get_feedback($_GET['id'],'buyer',$config);

	$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/profile_buyer.html");
	$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$username));
	$page->SetParameter ('USERNAME', $username);
	$page->SetParameter ('NAME', $name);
	$page->SetParameter ('ID',$_GET['id']);
	if($config['mailbox_en'])
	{
		$page->SetParameter('MAILBOX_SHOW',1);
	}
	else
	{
		$page->SetParameter('MAILBOX_SHOW',0);
	}
	if(isset($_SESSION['user']['id']))
	{
		if($_SESSION['user']['type'] == 'provider')
		{
			$block_check = mysql_num_rows(mysql_query("SELECT 1 FROM `".$config['db']['pre']."block` WHERE `user_id` = '".$_SESSION['user']['id']."' AND `user_id2` = '".validate_input($_GET['id'])."' AND `block_type` = 'pro' LIMIT 1"));
		
			if($block_check)
			{
				$page->SetParameter('SHOW_BLOCK',0);
				$page->SetParameter('SHOW_UNBLOCK',1);
			}
			else
			{
				$page->SetParameter('SHOW_BLOCK',1);
				$page->SetParameter('SHOW_UNBLOCK',0);
			}
		}
		else
		{
			$page->SetParameter('SHOW_BLOCK',0);
			$page->SetParameter('SHOW_UNBLOCK',0);
		}
	}
	else
	{
		$page->SetParameter('SHOW_BLOCK',0);
		$page->SetParameter('SHOW_UNBLOCK',0);
	}
	$page->SetLoop ('PROFILE_CUSTOM', $profile_custom);
	$page->SetLoop ('PROJECTS', $projects);
	$page->SetLoop ('REVIEWS', $reviews);
	$page->SetLoop ('PAGES', pagenav($feedback_info['reviews'],$_GET['page'],10,$config['site_url'].'profile.php?id='.$_GET['id'].'&type='.$_GET['type'],1));
	$page->SetParameter ('PAGE', $_GET['page']);
	$page->SetParameter ('REVIEWS', $feedback_info['reviews']);
	$page->SetParameter ('RATING', $feedback_info['rating']);
	$page->SetParameter ('MEMBER_SINCE', date('n/j/Y', $joined));
	$page->SetParameter ('PAGE_TOTAL', ceil($feedback_info['reviews']/10));
	$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
	$page->CreatePageEcho($lang,$config);
}

?>