<?php
require_once('includes/config.php');
require_once('includes/classes/class.template_engine.php');
require_once('includes/functions/func.global.php');
session_start();
$config['lang'] = check_user_lang($config);
require_once('includes/lang/lang_'.$config['lang'].'.php');

db_connect($config);

check_cookie($_SESSION,$config);

// Get Settings
$settings = get_settings('index.php',$config);

$count = 0;
$extra_where = '';

if(isset($_GET['cat']))
{
	$categories = get_categories($config,array($_GET['cat']),'selected');
	
	if(isset($categories[$_GET['cat']]))
	{
		if($categories[$_GET['cat']]['ctype'] == '1')
		{
			foreach($categories as $catvalue)
			{
				if($catvalue['parent_id'] == $_GET['cat'])
				{
					 $extra_where.= " OR project_types = '".validate_input($catvalue['id'])."' OR project_types LIKE '".validate_input($catvalue['id']).",%' OR project_types LIKE '%,".validate_input($catvalue['id'])."' OR project_types LIKE '%,".validate_input($catvalue['id']).",%'";
				}
			}
			
		}
	}
}
else
{
	$categories = get_categories($config);
}

$projects = array();

if(!isset($_GET['page']))
{
	$_GET['page'] = 1;
}

if(!isset($_GET['filter']))
{
	$_GET['filter'] = '';
}

if($_GET['filter'] == 'featured')
{
	$total[0] = mysql_num_rows(mysql_query("SELECT 1 FROM `".$config['db']['pre']."projects` WHERE project_status='0' AND project_featured='1'"));

	$query = "SELECT project_id,project_title,project_bids,project_start,project_end,project_types,project_subtypes,project_avgbid,project_featured,project_db,project_os FROM `".$config['db']['pre']."projects` WHERE project_status='0' AND project_featured='1' ORDER BY project_id DESC LIMIT ".validate_input(($_GET['page']-1)*20).",20";
}
elseif(isset($_GET['cat']))
{
	$total[0] = mysql_num_rows(mysql_query("SELECT 1 FROM `".$config['db']['pre']."projects` WHERE project_status='0' AND (project_types = '".validate_input($_GET['cat'])."' OR project_types LIKE '".validate_input($_GET['cat']).",%' OR project_types LIKE '%,".validate_input($_GET['cat'])."' OR project_types LIKE '%,".validate_input($_GET['cat']).",%' ".$extra_where.")"));

	$query = "SELECT project_id,project_title,project_bids,project_start,project_end,project_types,project_subtypes,project_avgbid,project_featured,project_db,project_os FROM `".$config['db']['pre']."projects` WHERE project_status='0' AND (project_types = '".validate_input($_GET['cat'])."' OR project_types LIKE '".validate_input($_GET['cat']).",%' OR project_types LIKE '%,".validate_input($_GET['cat'])."' OR project_types LIKE '%,".validate_input($_GET['cat']).",%' ".$extra_where.") ORDER BY project_id DESC LIMIT ".validate_input(($_GET['page']-1)*20).",20";
}
else
{
	$total[0] = mysql_num_rows(mysql_query("SELECT 1 FROM `".$config['db']['pre']."projects` WHERE project_status='0'"));

	$query = "SELECT project_id,project_title,project_bids,project_start,project_end,project_types,project_subtypes,project_avgbid,project_featured,project_db,project_os FROM `".$config['db']['pre']."projects` WHERE project_status='0' ORDER BY project_id DESC LIMIT ".validate_input(($_GET['page']-1)*20).",20";
}
$query_result = mysql_query ($query) OR error(mysql_error());
while ($info = mysql_fetch_array($query_result))
{
	$types_temp = array();
	$types_temp2 = array();
	$types_temp3 = array();
	unset($types);
	
	$display=false;
		
	if(!isset($_GET['cat']))
	{
		$display=true;
	}
	else
	{
		$types=explode(",",$info['project_types']);
		foreach($types as $typ)
		{
			if($_GET['cat']==$typ)
			{
				$display=true;
			}
		}
		
		if($display == false)
		{
			if(isset($categories[$_GET['cat']]))
			{
				if($categories[$_GET['cat']]['ctype'] == '1')
				{
					foreach($categories as $catvalue)
					{
						if($catvalue['parent_id'] == $_GET['cat'])
						{
							$display = true;
						}
					}
				}
			}
		}
	}

	if($display==true)
	{
		$types_temp = explode(',', $info['project_types']);
		foreach ($types_temp as $value) 
		{
			if(isset($categories[$value]['title']))
			{
				$types_temp2[] = $categories[$value]['title'];
			}
		}
		
		$types_temp3 = implode(', ', $types_temp2);
		$projects[$count]['title'] = strip_tags(stripslashes(substr($info['project_title'],0,25)));
		$projects[$count]['id'] = $info['project_id'];
		$projects[$count]['bids'] = $info['project_bids'];
		
		if(date('z', $info['project_start']) == date('z'))
		{
			$projects[$count]['startdate'] = $lang['TODAY'];
		}
		else
		{
			$projects[$count]['startdate'] = date("n/j/Y", $info['project_start']);
		}
		
		if(($info['project_end'] - $info['project_start']) <= $settings['urgent_time'])
		{
			$projects[$count]['urgent'] = 1;
		}
		else
		{
			$projects[$count]['urgent'] = 0;
		}
		
		$projects[$count]['enddate'] = date("n/j/Y", $info['project_end']);
		$projects[$count]['types'] = $types_temp3;
		$projects[$count]['avg_bid'] = $info['project_avgbid'];
		$projects[$count]['featured'] = $info['project_featured'];
		$projects[$count]['db'] = stripslashes($info['project_db']);
		$projects[$count]['os'] = stripslashes($info['project_os']);
		
		if($config['mod_rewrite'] == 0)
		{
			$projects[$count]['link'] = 'project.php?id=' . $info['project_id'];
		}
		else
		{
			$projects[$count]['link'] = 'projects/' . $info['project_id'] . '/'.convert2link(stripslashes($info['project_title'])).'/';
		}
		
		$count++;
	}
}

$count=0;

foreach(array_keys($categories) as $cat)
{
	$category[$count]['id']=$cat;
	$category[$count]['name']=$categories[$cat]['title'];
	$category[$count]['selected']=$categories[$cat]['selected'];
	$category[$count]['ctype']=$categories[$cat]['ctype'];
	$count++;
}

$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/projects.html");
$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['PROJECTS']));
$page->SetLoop ('PROJECTS', $projects);
$page->SetParameter ('SITEURL',$config['site_url']);
$page->SetLoop ('CATEGORIES', $category);
if(isset($_GET['cat']))
{
	$page->SetParameter('CURRENT_CAT', $_GET['cat']);
}
else
{
	$page->SetParameter('CURRENT_CAT', '');
}
if($_GET['filter'] == 'featured')
{
	$page->SetLoop ('PAGES', pagenav($total[0],$_GET['page'],20,$config['site_url'].'projects.php?filter=featured',1));
}
elseif(isset($_GET['cat']))
{
	$page->SetLoop ('PAGES', pagenav($total[0],$_GET['page'],20,$config['site_url'].'projects.php?cat='.$_GET['cat'],1));
}
else
{
	$page->SetLoop ('PAGES', pagenav($total[0],$_GET['page'],20,$config['site_url'].'projects.php',0));
}
$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
$page->CreatePageEcho($lang,$config);
?>