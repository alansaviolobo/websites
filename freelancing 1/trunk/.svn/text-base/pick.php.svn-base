<?php
require_once('includes/config.php');
require_once('includes/classes/class.template_engine.php');
require_once("includes/classes/class.phpmailer.php");
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
			if(isset($_POST['bid']))
			{
				$query = "SELECT user_id,project_id FROM `".$config['db']['pre']."bids` WHERE bid_id='" . validate_input($_POST['bid_id']) . "' LIMIT 1";
				$query_result = @mysql_query ($query) OR error(mysql_error());
				while ($info = @mysql_fetch_array($query_result))
				{
					$user_id = $info['user_id'];
					$project_id = $info['project_id'];
				}
			
				$project_found = 0;
			
				$query = "SELECT project_title FROM `".$config['db']['pre']."projects` WHERE project_id='" . $project_id . "' AND buyer_id='" . validate_input($_SESSION['user']['id']) . "' LIMIT 1";
				$query_result = @mysql_query ($query) OR error(mysql_error());
				while ($info = @mysql_fetch_array($query_result))
				{
					$project_title = $info['project_title'];
					$project_found = 1;
				}

				if(!$project_found)
				{
					message($lang['PROJBELONG'], $config, $lang);
				}
				
				$query = "SELECT provider_email FROM `".$config['db']['pre']."providers` WHERE provider_id='" . $user_id . "' LIMIT 1";
				$query_result = @mysql_query ($query) OR error(mysql_error());
				while ($info = @mysql_fetch_array($query_result))
				{
					$email = $info['provider_email'];
				}
				$checkstamp=md5($user_id.":".$project_id.":".time());
				
				$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/email_project_pick.html");
				$page->SetParameter ('ID', $project_id);
				$page->SetParameter ('CHECK', $checkstamp);
				$page->SetParameter ('SITE_TITLE', $config['site_title']);
				$page->SetParameter ('SITE_URL', $config['site_url']);
				$page->SetParameter ('PROJECT_TITLE', $project_title);
				$email_body = $page->CreatePageReturn($lang,$config);

				email($email,$config['site_title'],$email_body,$config);
				
				mysql_query("UPDATE `".$config['db']['pre']."projects` SET `checkstamp` = '".$checkstamp."', `project_status` = '1',`provider_id` = '" . $user_id . "' WHERE `project_id` = '" . validate_input($_POST['id']) . "' AND buyer_id='" . validate_input($_SESSION['user']['id']) . "' LIMIT 1 ;");
			
				header("Location: manage.php");
			}
			else
			{
				$project_found = mysql_fetch_row(mysql_query("SELECT project_title FROM `".$config['db']['pre']."projects` WHERE project_id='" . validate_input($_GET['id']) . "' AND buyer_id='" . validate_input($_SESSION['user']['id']) . "' LIMIT 1"));
			
				if(!$project_found)
				{
					message('Project Does not belong to you', $config, $lang);
				}
			
				$bids = array();
				$count = 0;
				
				$query = "SELECT bid_id,user_id,bid_days,bid_amount,bid_desc,bid_time FROM `".$config['db']['pre']."bids` WHERE project_id='" . validate_input($_GET['id']) . "'";
				$query_result = @mysql_query ($query) OR error(mysql_error());
				while ($info = @mysql_fetch_array($query_result))
				{
					$query2 = "SELECT provider_username FROM `".$config['db']['pre']."providers` WHERE provider_id='" . $info['user_id'] . "' LIMIT 1";
					$query_result2 = @mysql_query ($query2) OR error(mysql_error());
					while ($info2 = @mysql_fetch_array($query_result2))
					{
						$bid_username = $info2['provider_username'];
					}
				
					$count++;
					
					$bids[$count]['user_id'] = $info['user_id'];
					$bids[$count]['username'] = $bid_username;
					$bids[$count]['bid_id'] = $info['bid_id'];
					$bids[$count]['bid_days'] = $info['bid_days'];
					$bids[$count]['bid_amount'] = $info['bid_amount'];
					$bids[$count]['bid_desc'] = $info['bid_desc'];
					
					$bids[$count]['bid_date'] = date("n/j/Y", $info['bid_time']);
					$bids[$count]['bid_time'] = date("G:i", $info['bid_time']);
					$bids[$count]['bid_timezone'] = date("T", $info['bid_time']);
				}
				
				$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/pick.html");
				$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang));
				$page->SetLoop ('BIDS', $bids);
				$page->SetParameter ('PROJECT_ID', $_GET['id']);
				$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
				$page->CreatePageEcho($lang,$config);
			}
		}
	}
}
?>