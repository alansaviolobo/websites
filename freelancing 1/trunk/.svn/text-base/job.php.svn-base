<?php
require_once('includes/config.php');
require_once('includes/classes/class.template_engine.php');
require_once('includes/functions/func.global.php');
session_start();
$config['lang'] = check_user_lang($config);
require_once('includes/lang/lang_'.$config['lang'].'.php');

// Connect to database
db_connect($config);

check_cookie($_SESSION,$config);

// Update Last Active Time
update_lastactive($config);

$query = "SELECT * FROM `".$config['db']['pre']."jobs` WHERE job_id='" . validate_input($_GET['id']) . "' LIMIT 1";
$query_result = mysql_query ($query) OR error(mysql_error());
$row_num = mysql_num_rows($query_result);

if($row_num == 0)
{
	error($lang['JOBNOTEXIST'], __LINE__, __FILE__, 1,$lang,$config); 
}

while ($info = mysql_fetch_array($query_result))
{
	$job_cat = '';

	$query2 = "SELECT cat_title FROM `".$config['db']['pre']."jobs_categories` WHERE cat_id='" . $info['job_category'] . "' LIMIT 1";
	$query_result2 = mysql_query ($query2) OR error(mysql_error());
	while ($info2 = mysql_fetch_array($query_result2))
	{
		$job_cat = stripslashes($info2['cat_title']);
	}
	
	$query2 = "SELECT type_title FROM `".$config['db']['pre']."jobs_types` WHERE type_id='" . $info['job_type'] . "' LIMIT 1";
	$query_result2 = mysql_query ($query2) OR error(mysql_error());
	while ($info2 = mysql_fetch_array($query_result2))
	{
		$job_type = stripslashes($info2['type_title']);
	}

	$job_title = stripslashes($info['job_title']);
	$job_desc = strip_tags(stripslashes($info['job_desc']));
	$job_company = stripslashes($info['job_company']);
	$job_salary = stripslashes($info['job_salary']);
	$job_location = stripslashes($info['job_location']);
	$job_country = stripslashes($info['job_country']);
	$job_contact = stripslashes($info['job_contact']);

	$job_creator_id = $info['buyer_id'];
}

$query = "SELECT buyer_username FROM `".$config['db']['pre']."buyers` WHERE buyer_id='" . $job_creator_id . "' LIMIT 1";
$query_result = mysql_query ($query) OR error(mysql_error());
while ($info = mysql_fetch_array($query_result))
{
	$creator = $info['buyer_username'];
}

if($config['mod_rewrite'] == 0)
{
	$job_creator_link = $config['site_url'].'profile.php?type=buyer&id='.$job_creator_id;
}
else
{
	$job_creator_link = $config['site_url'].'buyers/'.$job_creator_id.'/'.$creator.'/';
}

$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/job.html");
$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$job_title));

$page->SetParameter ('JOB_ID', $_GET['id']);
$page->SetParameter ('JOB_TYPE', $job_type);
$page->SetParameter ('JOB_TITLE', $job_title);
$page->SetParameter ('JOB_DESC', nl2br($job_desc));
$page->SetParameter ('JOB_COMPANY', $job_company);
$page->SetParameter ('JOB_CREATOR_ID', $job_creator_id);
$page->SetParameter ('JOB_CREATOR_LINK', $job_creator_link);
$page->SetParameter ('JOB_CREATOR', $creator);
$page->SetParameter ('JOB_CAT', $job_cat);
$page->SetParameter ('JOB_SALARY', $job_salary);
$page->SetParameter ('JOB_LOCATION', $job_location);
$page->SetParameter ('JOB_COUNTRY', $job_country);
$page->SetParameter ('JOB_CONTACT', $job_contact);

$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
$page->CreatePageEcho($lang,$config);
?>