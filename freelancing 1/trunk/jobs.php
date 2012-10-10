<?php
require_once('includes/config.php');
require_once('includes/classes/class.template_engine.php');
require_once('includes/functions/func.global.php');
session_start();
$config['lang'] = check_user_lang($config);
require_once('includes/lang/lang_'.$config['lang'].'.php');

db_connect($config);

check_cookie($_SESSION,$config);

$count = 0;
$jobs = array();

$query = "SELECT cat_id,cat_title FROM `".$config['db']['pre']."jobs_categories`";
$query_result = mysql_query ($query) OR error(mysql_error());
while ($info = mysql_fetch_array($query_result))
{
	$job_cats[$info['cat_id']] = stripslashes($info['cat_title']);
}

$query = "SELECT job_id,job_title,job_company,job_category FROM `".$config['db']['pre']."jobs` WHERE job_status='0' ORDER BY job_id DESC LIMIT " . $config['job_listings_limit'];
$query_result = mysql_query ($query) OR error(mysql_error());
while ($info = mysql_fetch_array($query_result))
{
	$jobs[$count]['title'] = stripslashes($info['job_title']);
	$jobs[$count]['id'] = $info['job_id'];
	$jobs[$count]['category'] = $job_cats[$info['job_category']];
	$jobs[$count]['company'] = stripslashes($info['job_company']);
	
	if($config['mod_rewrite'] == 0)
	{
		$jobs[$count]['link'] = 'job.php?id=' . $info['job_id'];
	}
	else
	{
		$jobs[$count]['link'] = 'jobs/' . $info['job_id'] . '/'.convert2link(stripslashes($info['job_title'])).'/';
	}
	
	$count++;
}


$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/jobs.html");
$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['JOBS']));
$page->SetLoop ('JOB_LISTINGS', $jobs);
$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
$page->CreatePageEcho($lang,$config);
?>