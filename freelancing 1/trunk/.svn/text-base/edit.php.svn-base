<?php
require_once('includes/config.php');
require_once('includes/classes/class.template_engine.php');
require_once('includes/functions/func.global.php');
require_once('includes/functions/func.users.php');
session_start();
$config['lang'] = check_user_lang($config);
require_once('includes/lang/lang_'.$config['lang'].'.php');

db_connect($config);

check_cookie($_SESSION,$config);

if(isset($_GET['type']))
{
	if($_GET['type'] != 'job')
	{
		$_GET['type'] = 'project';
	}
}
else
{
	$_GET['type'] = 'project';
}
				
if(checkloggedin())
{
	if(check_account_frozen($_SESSION['user']['id'], $_SESSION['user']['type'],$config))
	{
		message($lang['ACCOUNTFROZEN'],$config,$lang);
	}
	else
	{
		if($_SESSION['user']['type'] == 'buyer')
		{
			if(isset($_POST['id']))
			{
				if($_POST['type'] == 'project')
				{
					$query = "SELECT project_desc FROM `".$config['db']['pre'] . "projects` WHERE project_id='" . validate_input($_POST['id']) . "' AND buyer_id='" . validate_input($_SESSION['user']['id']) . "' LIMIT 1";
				}
				elseif($_POST['type'] == 'job')
				{
					$query = "SELECT job_desc FROM `".$config['db']['pre'] . "jobs` WHERE job_id='" . validate_input($_POST['id']) . "' AND buyer_id='" . validate_input($_SESSION['user']['id']) . "' LIMIT 1";
				}
			
				$query_result = @mysql_query ($query) OR error(mysql_error());
				while ($info = @mysql_fetch_array($query_result))
				{
					if($_GET['type'] != 'job')
					{
						$desc = $info['project_desc'];
					}
					else
					{
						$desc = $info['job_desc'];
					}
				}
			
				$vars['DATE'] = date("n/j/Y");
				$vars['TIME'] = date("G:i");
				$vars['TIMEZONE'] = date("T");
				
				foreach ($vars as $key => $value) 
				{
					$tpl_config['additional'] = str_replace('{' . $key . '}', $value, $tpl_config['additional']);
				}
			
				$edit = $desc . "\r\n\r\n" . $tpl_config['additional'] . "\r\n\r\n" . $_POST['edit'];
	
				if($_POST['type'] == 'project')
				{
					mysql_query("UPDATE `".$config['db']['pre'] . "projects` SET `" . "project_desc` = '" . validate_input($edit) . "' WHERE `project_id` = '" . validate_input($_POST['id']) . "' AND buyer_id='".$_SESSION['user']['id']."' LIMIT 1 ;");
				}
				elseif($_POST['type'] == 'job')
				{
					mysql_query("UPDATE `".$config['db']['pre'] . "jobs` SET `job_desc` = '" . validate_input($edit) . "' WHERE `job_id` = '" . validate_input($_POST['id']) . "' AND buyer_id='".$_SESSION['user']['id']."' LIMIT 1 ;");
				}
	
				header("Location: manage.php");
				exit;
			}
			else
			{
				$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/edit.html");
				$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['EDITPROJ']));
				$page->SetParameter ('TYPE', $_GET['type']);				
				$page->SetParameter ('ID', $_GET['id']);
				$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
				$page->CreatePageEcho($lang,$config);
			}
		}
	}
}
?>