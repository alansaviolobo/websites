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
$settings = get_settings('search.php',$config);

if(!isset($settings['search_mode']))
{
	$settings['search_mode'] = '1';
}

if(isset($_GET['keywords']))
{
	$_POST['keywords'] = $_GET['keywords'];
}

if(isset($_GET['status']))
{
	$_POST['status'] = $_GET['status'];
}

if(!isset($_POST['status']))
{
	$_POST['status'] = 0;
}

if(!isset($_POST['keywords']))
{
	$categories = get_categories($config);
	
	$page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/search.html');
	$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['SEARCH']));
	$page->SetLoop ('JOBTYPE', $categories);
	$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
	$page->CreatePageEcho($lang,$config);
}
else
{
	$count = 0;
	if(isset($_POST['jobtype']))
	{
		$categories = get_categories($config,$_POST['jobtype']);
	}
	else
	{
		$categories = get_categories($config);
	}
		
	$projects = array();

	$where = '';

	if(isset($_POST['jobtype']))
	{
		foreach ($_POST['jobtype'] as $key => $value) 
		{
			$key = validate_input($key);
		
			if($count == 0)
			{
				$where.= "(project_types LIKE '" . $key . ",%' OR project_types LIKE '%,". $key .",%' OR project_types LIKE '%," . $key . "' OR project_types='" . $key . "')";
			}
			else
			{
				$where.= " OR (project_types LIKE '" . $key . ",%' OR project_types LIKE '%,". $key .",%' OR project_types LIKE '%," . $key . "' OR project_types='" . $key . "')";
			}
			
			if($categories[$key]['ctype'] == 1)
			{
				foreach ($categories as $key2 => $value2) 
				{
					if($value2['parent_id'] == $key)
					{
						$where.= " OR (project_types LIKE '" . $key2 . ",%' OR project_types LIKE '%,". $key2 .",%' OR project_types LIKE '%," . $key2 . "' OR project_types='" . $key2 . "')";
					}
				}
			}
			
			$count++;
		}
	}
	
	if(isset($_POST['keywords']))
	{
		if($settings['search_mode'] == '1')
		{
			if($where == '')
			{
				$where.= "project_title LIKE '%" . validate_input($_POST['keywords']) . "%'";
			}
			else
			{
				$where.= "AND project_title LIKE '%" . validate_input($_POST['keywords']) . "%'";
			}
		}
	}

	$count = 0;
	
	if(trim($_POST['keywords']) == '')
	{
		$settings['search_mode'] = 1;
	}

	if($settings['search_mode'] == '2')
	{
		if($_POST['status'] == 3)
		{
			if($where == '')
			{
				$query = "SELECT project_id,project_title,project_bids,project_start,project_end,project_types,project_avgbid,project_featured,project_db,project_os, MATCH (project_title,project_desc) AGAINST ('".validate_input($_POST['keywords'])."' IN BOOLEAN MODE) AS Relevance FROM `".$config['db']['pre']."projects` WHERE MATCH (project_title,project_desc) AGAINST ('".validate_input($_POST['keywords'])."' IN BOOLEAN MODE) HAVING Relevance > 0.2 ORDER BY Relevance DESC";
			}
			else
			{
				$query = "SELECT project_id,project_title,project_bids,project_start,project_end,project_types,project_avgbid,project_featured,project_db,project_os, MATCH (project_title,project_desc) AGAINST ('".validate_input($_POST['keywords'])."' IN BOOLEAN MODE) AS Relevance FROM `".$config['db']['pre']."projects` WHERE ".$where." AND MATCH (project_title,project_desc) AGAINST ('".validate_input($_POST['keywords'])."' IN BOOLEAN MODE) HAVING Relevance > 0.2 ORDER BY Relevance DESC";
			}
		}
		else
		{
			if($where == '')
			{
				$query = "SELECT project_id,project_title,project_bids,project_start,project_end,project_types,project_avgbid,project_featured,project_db,project_os, MATCH (project_title,project_desc) AGAINST ('".validate_input($_POST['keywords'])."' IN BOOLEAN MODE) AS Relevance FROM `".$config['db']['pre']."projects` WHERE project_status='" . validate_input($_POST['status']) . "' AND MATCH (project_title,project_desc) AGAINST ('".validate_input($_POST['keywords'])."' IN BOOLEAN MODE) HAVING Relevance > 0.2 ORDER BY Relevance DESC";
			}
			else
			{
				$query = "SELECT project_id,project_title,project_bids,project_start,project_end,project_types,project_avgbid,project_featured,project_db,project_os, MATCH (project_title,project_desc) AGAINST ('".validate_input($_POST['keywords'])."' IN BOOLEAN MODE) AS Relevance FROM `".$config['db']['pre']."projects` WHERE ".$where." AND project_status='" . validate_input($_POST['status']) . "' AND MATCH (project_title,project_desc) AGAINST ('".validate_input($_POST['keywords'])."' IN BOOLEAN MODE) HAVING Relevance > 0.2 ORDER BY Relevance DESC";
			}
		}
	}
	else
	{
		if($_POST['status'] == 3)
		{
			if($where == '')
			{
				$query = "SELECT project_id,project_title,project_bids,project_start,project_end,project_types,project_avgbid,project_featured,project_db,project_os FROM `".$config['db']['pre']."projects` ORDER BY project_id DESC";
			}
			else
			{
				$query = "SELECT project_id,project_title,project_bids,project_start,project_end,project_types,project_avgbid,project_featured,project_db,project_os FROM `".$config['db']['pre']."projects` WHERE " . $where . " ORDER BY project_id DESC";
			}
		}
		else
		{
			if($where == '')
			{
				$query = "SELECT project_id,project_title,project_bids,project_start,project_end,project_types,project_avgbid,project_featured,project_db,project_os FROM `".$config['db']['pre']."projects` WHERE project_status='" . validate_input($_POST['status']) . "' ORDER BY project_id DESC";
			}
			else
			{
				$query = "SELECT project_id,project_title,project_bids,project_start,project_end,project_types,project_avgbid,project_featured,project_db,project_os FROM `".$config['db']['pre']."projects` WHERE project_status='" . validate_input($_POST['status']) . "' AND " . $where . " ORDER BY project_id DESC";
			}
		}
	}
	
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
		
		$projects[$count]['title'] = strip_tags(stripslashes(substr($info['project_title'],0,30)));
		$projects[$count]['id'] = $info['project_id'];
		$projects[$count]['bids'] = $info['project_bids'];
		
		if(date('z', $info['project_start']) == date('z'))
		{
			$projects[$count]['startdate'] = 'Today';
		}
		else
		{
			$projects[$count]['startdate'] = date("n/j/Y", $info['project_start']);
		}
		
		if(($info['project_start'] - $info['project_end']) > $settings['urgent_time'])
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

	$page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/search_results.html');
	$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['SEARCH']));
	$page->SetLoop ('PROJECTS', $projects);
	$page->SetParameter ('RESULTS', mysql_num_rows($query_result));
	$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
	$page->CreatePageEcho($lang,$config);
}
?>