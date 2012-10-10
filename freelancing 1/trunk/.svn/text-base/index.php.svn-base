<?php
require_once('includes/config.php');
require_once('includes/classes/class.template_engine.php');
require_once('includes/functions/func.global.php');
session_start();
$config['lang'] = check_user_lang($config);
require_once('includes/lang/lang_'.$config['lang'].'.php');

checkinstall($config);
db_connect($config);

check_cookie($_SESSION,$config);

// Get Settings
$settings = get_settings('index.php',$config);

// Update Last Active Time
update_lastactive($config);

$count = 0;
$latest_projects = array();
$categories = get_categories($config);

$query = "SELECT project_id,project_title,project_start,project_end,project_bids,project_types,project_subtypes,project_avgbid,project_db,project_os,project_featured FROM `".$config['db']['pre']."projects` WHERE project_status='0' ORDER BY project_id DESC LIMIT " . $config['latest_project_limit'];
$query_result = mysql_query ($query) OR error(mysql_error());
while ($info = mysql_fetch_array($query_result))
{
	$types_temp = array();
	$types_temp2 = array();
	$types_temp3 = array();

	$types_temp = explode(',', $info['project_types']);
	foreach ($types_temp as $value) 
	{
		if(isset($categories[$value]['title']))
		{
			$types_temp2[] = $categories[$value]['title'];
		}
	}
	
	$types_temp3 = implode(', ', $types_temp2);

	$latest_projects[$count]['title'] = stripslashes($info['project_title']);
	$latest_projects[$count]['id'] = $info['project_id'];
	
	if(date('z', $info['project_start']) == date('z'))
	{
		$latest_projects[$count]['startdate'] = $lang['TODAY'];
	}
	else
	{
		$latest_projects[$count]['startdate'] = date($settings['index_project_start_date'], $info['project_start']);
	}
	
	if(($info['project_end'] - $info['project_start']) <= $settings['urgent_time'])
	{
		$latest_projects[$count]['urgent'] = 1;
	}
	else
	{
		$latest_projects[$count]['urgent'] = 0;
	}
	
	$latest_projects[$count]['enddate'] = date($settings['index_project_end_date'], $info['project_end']);
	$latest_projects[$count]['bids'] = $info['project_bids'];
	$latest_projects[$count]['types'] = $types_temp3;
	$latest_projects[$count]['avg_bid'] = $info['project_avgbid'];
	$latest_projects[$count]['db'] = stripslashes($info['project_db']);
	$latest_projects[$count]['os'] = stripslashes($info['project_os']);
	$latest_projects[$count]['featured'] = $info['project_featured'];

	if($config['mod_rewrite'] == 0)
	{
		$latest_projects[$count]['link'] = 'project.php?id=' . $info['project_id'];
	}
	else
	{
		$latest_projects[$count]['link'] = 'projects/' . $info['project_id'] . '/'.convert2link(stripslashes($info['project_title'])).'/';
	}
	
	$count++;
}

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
	if(isset($job_cats[$info['job_category']]))
	{
		$jobs[$count]['category'] = $job_cats[$info['job_category']];
	}
	else
	{
		$jobs[$count]['category'] = '';
	}
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

$count = 0;
$featured_projects = array();

$query = "SELECT project_id,project_title,project_start,project_end,project_bids,project_types,project_subtypes,project_avgbid,project_db,project_os,project_featured FROM `".$config['db']['pre']."projects` WHERE project_featured='1' AND  project_status='0' ORDER BY project_id DESC LIMIT " . $config['featured_project_limit'];
$query_result = mysql_query ($query) OR error(mysql_error());
while ($info = mysql_fetch_array($query_result))
{
	$types_temp = array();
	$types_temp2 = array();
	$types_temp3 = array();
	
	$types_temp = explode(',', $info['project_types']);
	foreach ($types_temp as $value) 
	{
		if(isset($categories[$value]['title']))
		{
			$types_temp2[] = $categories[$value]['title'];
		}
	}
	
	$types_temp3 = implode(', ', $types_temp2);

	$featured_projects[$count]['title'] = stripslashes($info['project_title']);
	$featured_projects[$count]['id'] = $info['project_id'];
	
	if(date('z', $info['project_start']) == date('z'))
	{
		$featured_projects[$count]['startdate'] = $lang['TODAY'];
	}
	else
	{
		$featured_projects[$count]['startdate'] = date($settings['index_project_start_date'], $info['project_start']);
	}
	
	if(($info['project_end'] - $info['project_start']) <= $settings['urgent_time'])
	{
		$featured_projects[$count]['urgent'] = 1;
	}
	else
	{
		$featured_projects[$count]['urgent'] = 0;
	}
	
	$featured_projects[$count]['enddate'] = date($settings['index_project_end_date'], $info['project_end']);
	$featured_projects[$count]['bids'] = $info['project_bids'];
	$featured_projects[$count]['types'] = $types_temp3;
	$featured_projects[$count]['avg_bid'] = $info['project_avgbid'];
	$featured_projects[$count]['db'] = stripslashes($info['project_db']);
	$featured_projects[$count]['os'] = stripslashes($info['project_os']);
	$featured_projects[$count]['featured'] = $info['project_featured'];

	if($config['mod_rewrite'] == 0)
	{
		$featured_projects[$count]['link'] = 'project.php?id=' . $info['project_id'];
	}
	else
	{
		$featured_projects[$count]['link'] = 'projects/' . $info['project_id'] . '/'.convert2link(stripslashes($info['project_title'])).'/';
	}
	
	$count++;
}

if($config['enable_quotes'] == 1)
{
	$quote = get_random_quote($config);
}
else
{
	$quote[0] = '';
	$quote[1] = '';
}

// Get stats
$buyer_count = mysql_num_rows(mysql_query("SELECT 1 FROM `".$config['db']['pre']."buyers`"));
$provider_count = mysql_num_rows(mysql_query("SELECT 1 FROM `".$config['db']['pre']."providers`"));
$member_count = ($buyer_count+$provider_count);
$open_project_count = mysql_num_rows(mysql_query("SELECT 1 FROM `".$config['db']['pre']."projects` WHERE project_status='0'"));

// Output to template
$page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/index.html');
$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['HOME']));
if((time()-300) > $settings['cron_time'])
{
	$page->SetParameter ('CRON', 1);
	// Output unix timestamp to stop image caching
	$page->SetParameter ('TIMESTAMP',time());
}
else
{
	$page->SetParameter ('CRON', 0);
}

$page->SetParameter ('SITE_TITLE', $config['site_title']);
$page->SetParameter ('USITE_TITLE', strtoupper($config['site_title']));
$page->SetParameter ('QUOTE', $quote[0]);
$page->SetParameter ('AUTHOR', $quote[1]);
$page->SetParameter ('ENABLE_QUOTES', $config['enable_quotes']);
$page->SetParameter ('STAT_MEMBERS', $member_count);
$page->SetParameter ('STAT_BUYERS', $buyer_count);
$page->SetParameter ('STAT_PROVIDERS', $provider_count);
$page->SetParameter ('STAT_PROJECTS', $open_project_count);
$page->SetLoop ('CATS', $categories);
$page->SetLoop ('LATEST_PROJECTS', $latest_projects);
$page->SetLoop ('FEATURED_PROJECTS', $featured_projects);
$page->SetLoop ('JOB_LISTINGS', $jobs);
$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
$page->CreatePageEcho($lang,$config);
?>