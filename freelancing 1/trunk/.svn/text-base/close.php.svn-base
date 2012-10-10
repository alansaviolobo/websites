<?php
require_once('includes/config.php');
require_once('includes/classes/class.template_engine.php');
require_once('includes/functions/func.global.php');
require_once('includes/functions/func.users.php');
$config['lang'] = check_user_lang($config);
require_once('includes/lang/lang_'.$config['lang'].'.php');

db_connect($config);

if(!isset($_GET['type']))
{
	$_GET['type'] = 'project';
}

if($_GET['type'] != 'job')
{
	$closenum='2';
	$_GET['type']='project';
}
else
{
	$closenum='1';
}

session_start();
check_cookie($_SESSION,$config);

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
					mysql_query("UPDATE `".$config['db']['pre']."projects` SET `project_status` = '".$closenum."' WHERE `project_id` = '" . validate_input($_POST['id']) . "' AND `buyer_id` = '" . validate_input($_SESSION['user']['id']) . "' LIMIT 1 ;");
				}
				elseif($_POST['type'] == 'job')
				{
					mysql_query("UPDATE `".$config['db']['pre']."jobs` SET `job_status` = '".$closenum."' WHERE `job_id` = '" . validate_input($_POST['id']) . "' AND `buyer_id` = '" . validate_input($_SESSION['user']['id']) . "' LIMIT 1 ;");
				}
			
				header("Location: manage.php");
			}
			else
			{
				$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/close.html");
				$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,'Close Project'));
				$page->SetParameter ('ID', $_GET['id']);
				$page->SetParameter ('TYPE', $_GET['type']);
				$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
				$page->CreatePageEcho($lang,$config);
			}
		}
	}
}
?>