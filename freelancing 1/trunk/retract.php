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
	if($_SESSION['user']['type'] == 'provider')
	{
		if(isset($_GET['id']))
		{
			$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/retract.html");
			$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang));
			$page->SetParameter ('ID', $_GET['id']);
			$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
			$page->CreatePageEcho($lang,$config);
		}
		else
		{
			$query = "SELECT project_id,user_id FROM `".$config['db']['pre']."bids` WHERE bid_id = '" . validate_input($_POST['id']) . "'LIMIT 1";
			$query_result = mysql_query($query);
			while ($info = @mysql_fetch_array($query_result))
			{
				$project_id = $info['project_id'];
				$user_id = $info['user_id'];
			}
			
			if($user_id == $_SESSION['user']['id'])
			{						
				mysql_query("DELETE FROM `".$config['db']['pre']."bids` WHERE `bid_id` = '" . validate_input($_POST['id']) . "' AND `user_id` = '" . $_SESSION['user']['id'] . "' LIMIT 1");
				
				if(mysql_affected_rows())
				{		
					mysql_query("UPDATE `".$config['db']['pre']."projects` SET `project_bids` = project_bids-1,`project_avgbid` = '" . validate_input(calculate_avg_bid($config,$_POST['id'])) . "' WHERE `project_id` = '" . validate_input($project_id) . "' LIMIT 1;");
				}
			}
			
			header("Location: manage.php");
		}
	}
	else
	{
		header("Location: manage.php");
	}
}
?>