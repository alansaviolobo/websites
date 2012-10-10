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
				$seconds = (86400*$_POST['days']);
			
				$query = "SELECT project_end FROM `".$config['db']['pre']."projects` WHERE project_id='" . validate_input($_POST['id']) . "' AND buyer_id='" . validate_input($_SESSION['user']['id']) . "' LIMIT 1";
				$query_result = @mysql_query ($query) OR error(mysql_error());
				while ($info = @mysql_fetch_array($query_result))
				{
					$end = $info['project_end'];
				}
				
				$end = $end+$seconds;
			
				mysql_query("UPDATE `".$config['db']['pre']."projects` SET `project_end` = '" . $end . "' WHERE `project_id` = '" . validate_input($_POST['id']) . "' AND buyer_id='" . validate_input($_SESSION['user']['id']) . "' LIMIT 1 ;");
			
				header("Location: manage.php");
			}
			else
			{
				$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/extend.html");
				$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['EXTENDPROJ']));
				$page->SetParameter ('ID', $_GET['id']);
				$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
				$page->CreatePageEcho($lang,$config);
			}
		}
	}
}
?>