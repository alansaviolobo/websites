<?php
require_once('includes/config.php');
require_once('includes/classes/class.template_engine.php');
require_once('includes/functions/func.global.php');
session_start();
$config['lang'] = check_user_lang($config);
require_once('includes/lang/lang_'.$config['lang'].'.php');

db_connect($config);

check_cookie($_SESSION,$config);

// Update Last Active Time
update_lastactive($config);

$query = "SELECT * FROM `".$config['db']['pre']."projects` WHERE project_id='" . validate_input($_GET['id']) . "' LIMIT 1";
$query_result = mysql_query ($query) OR error(mysql_error());
$row_num = mysql_num_rows($query_result);

if($row_num == 0)
{ 
	error($lang['PROJECT_EXIST_ERROR'], __LINE__, __FILE__, 1,$lang,$config); 
}

$project_custom = array();

while ($info = mysql_fetch_array($query_result))
{
	$project_title = stripslashes($info['project_title']);
	$project_desc = nl2br(strip_tags(stripslashes($info['project_desc'])));
	$project_types = explode(',', $info['project_types']);
	$project_types2 = implode('\' OR cat_id=\'', $project_types);
	
	$project_db = stripslashes($info['project_db']);
	$project_os = stripslashes($info['project_os']);
	$project_budget_min = $info['project_budget_min'];
	$project_budget_max = $info['project_budget_max'];
	
	$project_start_date = date("n/j/Y", $info['project_start']);
	$project_start_time = date("G:i", $info['project_start']);
	$project_start_timezone = date("T", $info['project_start']);
	
	$project_end_date = date("n/j/Y", $info['project_end']);
	$project_end_time = date("G:i", $info['project_end']);
	$project_end_timezone = date("T", $info['project_end']);
	
	$project_featured = $info['project_featured'];
	$project_fileid = $info['project_fileid'];
	$project_status = $info['project_status'];
	$project_creator_id = $info['buyer_id'];

	$project_avgbid = $info['project_avgbid'];
	
	$custom_fields = explode(',',$info['project_custom_fields']);
	$custom_data = explode(',',$info['project_custom_values']);
}

$count = 2;

foreach($custom_fields as $key=>$value)
{
	if($value != '')
	{
		$project_custom[$count]['title'] = stripslashes($value);
		$project_custom[$count]['value'] = stripslashes($custom_data[$key]);
		if($project_fileid)
		{
			$project_custom[$count]['count'] = $count+1;
		}
		else
		{
			$project_custom[$count]['count'] = $count;
		}
		
		$count++;
	}
}

$query = "SELECT 1 FROM `".$config['db']['pre']."messages` WHERE project_id='" . validate_input($_GET['id']) . "'";
$query_result = mysql_query ($query) OR error(mysql_error());
$messages = mysql_num_rows($query_result);

$count = 0;
$project_types3 = array();

$query = "SELECT cat_name,cat_id FROM `".$config['db']['pre']."categories` WHERE cat_id='" . $project_types2 . "' ORDER BY cat_name LIMIT " . count($project_types);
$query_result = mysql_query ($query) OR error(mysql_error());
while ($info = mysql_fetch_array($query_result))
{
	$count++;
	
	$project_types3[$count]['id'] = $info['cat_id'];
	$project_types3[$count]['name'] = $info['cat_name'];
}

$count = 0;
$bids = array();
$edit_bid = 0;

$query = "SELECT user_id,bid_days,bid_amount,bid_desc,bid_time,file_id FROM `".$config['db']['pre']."bids` WHERE project_id='" . validate_input($_GET['id']) . "' ORDER BY bid_days,bid_amount,bid_id ASC";
$query_result = mysql_query ($query) OR error(mysql_error());
while ($info = mysql_fetch_array($query_result))
{
	$query2 = "SELECT provider_username FROM `".$config['db']['pre']."providers` WHERE provider_id='" . $info['user_id'] . "' LIMIT 1";
	$query_result2 = mysql_query ($query2) OR error(mysql_error());
	while ($info2 = mysql_fetch_array($query_result2))
	{
		$bid_username = $info2['provider_username'];
	}

	$count++;
	
	$bids[$count]['user_id'] = $info['user_id'];
	$bids[$count]['username'] = $bid_username;
	$bids[$count]['bid_days'] = $info['bid_days'];
	$bids[$count]['bid_amount'] = $info['bid_amount'];
	$bids[$count]['bid_desc'] = strip_tags(stripslashes(substr($info['bid_desc'],0,2000)));
	$bids[$count]['file_id'] = $info['file_id'];
	
	$bids[$count]['bid_date'] = date("n/j/Y", $info['bid_time']);
	$bids[$count]['bid_time'] = date("G:i", $info['bid_time']);
	$bids[$count]['bid_timezone'] = date("T", $info['bid_time']);
	
	$feedback_info = get_feedback($info['user_id'],'provider',$config);
	
	$bids[$count]['bid_reviews'] = $feedback_info['reviews'];
	$bids[$count]['bid_rating'] = $feedback_info['rating'];
		
	if($config['mod_rewrite'] == 0)
	{
		$bids[$count]['link'] = $config['site_url'].'profile.php?type=provider&id='.$info['user_id'];
	}
	else
	{
		$bids[$count]['link'] = $config['site_url'].'providers/'.$info['user_id'].'/'.$bid_username.'/';
	}
	
	if(isset($_SESSION['user']['id']))
	{
		if($info['user_id'] == $_SESSION['user']['id'])
		{
			if($_SESSION['user']['type'] == 'provider')
			{
				$edit_bid = 1;
			}
		}
	}
	
	$buyer_view_bid = 0;
	
	if(isset($_SESSION['user']['id']))
	{
		if($_SESSION['user']['type'] == 'buyer')
		{
			if($_SESSION['user']['id'] == $project_creator_id)
			{
				$buyer_view_bid = 1;
			}
		}
	}
	
	$bids[$count]['buyer_view_bid'] = $buyer_view_bid;
}

if($project_fileid != 0)
{
	$query = "SELECT file_name FROM `".$config['db']['pre']."attachments` WHERE file_id='" . $project_fileid . "' LIMIT 1";
	$query_result = mysql_query ($query) OR error(mysql_error());
	while ($info = mysql_fetch_array($query_result))
	{
		$file_name = $info['file_name'];
	}
}

$query = "SELECT buyer_username FROM `".$config['db']['pre']."buyers` WHERE buyer_id='" . $project_creator_id . "' LIMIT 1";
$query_result = mysql_query ($query) OR error(mysql_error());
while ($info = mysql_fetch_array($query_result))
{
	$project_creator_username = $info['buyer_username'];
}

if($config['mod_rewrite'] == 0)
{
	$project_creator_link = $config['site_url'].'profile.php?type=buyer&id='.$project_creator_id;
}
else
{
	$project_creator_link = $config['site_url'].'buyers/'.$project_creator_id.'/'.$project_creator_username.'/';
}

if( ($project_budget_max != 0) OR ($project_budget_min) )
{
	$budget = $config['currency_sign'] . $project_budget_min . "-" . $project_budget_max;
}
else
{
	$budget = 'N/A';
}

if($project_status == 0)
{
	$project_status = $lang['OPEN'];
	$project_status2 = 'open';
}
elseif ($project_status == 2)
{
	$project_status = $lang['CLOSED'];
	$project_status2 = 'closed';
}
else
{
	$project_status = $lang['FROZEN'];
	$project_status2 = 'frozen';
}

if($edit_bid)
{
	$project_status2 = 'edit';
}

$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/project.html");

$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$project_title));
$page->SetParameter ('PROJECT_ID', $_GET['id']);
$page->SetParameter ('PROJECT_TITLE', $project_title);
$page->SetParameter ('PROJECT_DESC', $project_desc);
$page->SetParameter ('PROJECT_DB', $project_db);
$page->SetParameter ('PROJECT_OS', $project_os);
$page->SetParameter ('PROJECT_AVGBID', $project_avgbid);

if($project_fileid == 0)
{
	$page->SetParameter ('ATTACHMENT', 0);
}
else
{
	$page->SetParameter ('ATTACHMENT', 1);
	$page->SetParameter ('FILE_NAME', $file_name);
	$page->SetParameter ('FILE_ID', $project_fileid);
}

$feedback_info = get_feedback($project_creator_id,'buyer',$config);

$page->SetParameter ('REVIEWS', $feedback_info['reviews']);
$page->SetParameter ('RATING', $feedback_info['rating']);

$page->SetParameter ('PROJECT_START_DATE', $project_start_date);
$page->SetParameter ('PROJECT_START_TIME', $project_start_time);
$page->SetParameter ('PROJECT_START_TIMEZONE', $project_start_timezone);

$page->SetParameter ('PROJECT_END_DATE', $project_end_date);
$page->SetParameter ('PROJECT_END_TIME', $project_end_time);
$page->SetParameter ('PROJECT_END_TIMEZONE', $project_end_timezone);

$page->SetParameter ('PROJECT_BUDGET', $budget);
$page->SetParameter ('PROJECT_STATUS', $project_status);
$page->SetParameter ('PROJECT_STATUS2', $project_status2);
$page->SetParameter ('PROJECT_CREATOR_ID', $project_creator_id);
$page->SetParameter ('PROJECT_CREATOR_USERNAME', $project_creator_username);
$page->SetParameter ('PROJECT_CREATOR_LINK', $project_creator_link);

$page->SetParameter ('MESSAGES', $messages);
$page->SetParameter ('EDIT_BID', $edit_bid);

$page->SetLoop ('PROJECT_TYPES', $project_types3);
$page->SetLoop ('PROJECT_CUSTOM', $project_custom);
$page->SetLoop ('BIDS', $bids);

$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
$page->CreatePageEcho($lang,$config);
?>