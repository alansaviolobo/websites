<?php
require_once('includes/config.php');
require_once('includes/classes/class.template_engine.php');
require_once("includes/classes/class.phpmailer.php");
require_once('includes/functions/func.global.php');
session_start();
$config['lang'] = check_user_lang($config);
require_once('includes/lang/lang_'.$config['lang'].'.php');
require_once('includes/functions/func.users.php');

db_connect($config);


check_cookie($_SESSION,$config);

if(checkloggedin())
{
	if(isset($_POST['Submit']))
	{
		$result=mysql_query("SELECT provider_id,buyer_id,project_title FROM `".$config['db']['pre']."projects` WHERE `project_id` = '" . validate_input($_POST['ID']) . "' AND checkstamp='".validate_input($_POST['checkstamp'])."' AND project_status='1' AND provider_id='".$_SESSION['user']['id']."' LIMIT 1 ;");
		if(mysql_num_rows($result)>0)
		{
			while($row=mysql_fetch_array($result))
			{
				$provider_id = $row['provider_id'];
				$buyer_id = $row['buyer_id'];
				$project_title = $row['project_title'];
			}
			
			$result=mysql_query("SELECT provider_username,provider_email FROM `".$config['db']['pre']."providers` WHERE `provider_id` = '" .$provider_id . "' LIMIT 1 ;");
			while($row=mysql_fetch_array($result))
			{
				$provider_username=$row['provider_username'];
				$provider_email=$row['provider_email'];
			}
			
			$result=mysql_query("SELECT buyer_username,buyer_email FROM `".$config['db']['pre']."buyers` WHERE `buyer_id` = '" .$buyer_id . "' LIMIT 1 ;");
			while($row=mysql_fetch_array($result))
			{
				$buyer_username=$row['buyer_username'];
				$buyer_email=$row['buyer_email'];
			}

			if($_POST['accept'] == 1)
			{
				mysql_query("UPDATE `".$config['db']['pre']."projects` SET `project_status` = '2' WHERE `project_id` = '" . validate_input($_POST['ID']) . "' AND checkstamp='".validate_input($_POST['checkstamp'])."' AND provider_id='".$_SESSION['user']['id']."' LIMIT 1 ;");
							
				$bid = mysql_fetch_row(mysql_query("SELECT bid_amount FROM `".$config['db']['pre']."bids` WHERE project_id='".validate_input($_POST['ID'])."' AND user_id='".validate_input($provider_id)."' LIMIT 1"));
				
				charge_comission($config,$bid[0],$buyer_id,$provider_id);
							
				$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/email_project_accepted_buy.html");
				$page->SetParameter ('PROVIDER_USERNAME', $provider_username);
				$page->SetParameter ('PROVIDER_EMAIL', $provider_email);
				$page->SetParameter ('SITE_TITLE', $config['site_title']);
				$page->SetParameter ('SITE_URL', $config['site_url']);
				$page->SetParameter ('PROJECT_TITLE', $project_title);
				$email_body = $page->CreatePageReturn($lang,$config);
	
				email($buyer_email,$lang['PROJECTSTART_EMAILSUBJECT'],$email_body,$config);
	
				$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/email_project_accepted_pro.html");
				$page->SetParameter ('BUYER_USERNAME', $buyer_username);
				$page->SetParameter ('BUYER_EMAIL', $buyer_email);
				$page->SetParameter ('SITE_TITLE', $config['site_title']);
				$page->SetParameter ('SITE_URL', $config['site_url']);
				$page->SetParameter ('PROJECT_TITLE', $project_title);
				$email_body = $page->CreatePageReturn($lang,$config);
				
				email($provider_email,$lang['PROJECTSTART_EMAILSUBJECT'],$email_body,$config);
				
				message($lang['ACCEPTMESSAGE'],$config,$lang,'manage.php');
			}
			else
			{
				$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/email_project_denied_buy.html");
				$page->SetParameter ('PROVIDER_USERNAME', $buyer_username);
				$page->SetParameter ('SITE_TITLE', $config['site_title']);
				$page->SetParameter ('SITE_URL', $config['site_url']);
				$page->SetParameter ('PROJECT_TITLE', $project_title);
				$email_body = $page->CreatePageReturn($lang,$config);
	
				email($buyer_email,$lang['PROJECTNOT'],$email_body,$config);
			
				mysql_query("UPDATE `".$config['db']['pre']."projects` SET `project_status` = '0',`provider_id` = '0' WHERE `project_id` = '" . validate_input($_POST['ID']) . "' AND checkstamp='".validate_input($_POST['checkstamp'])."' AND provider_id='".$_SESSION['user']['id']."' LIMIT 1 ;");
				message($lang['DECLINEMESSAGE'],$config,$lang,'manage.php');
			}
		}
		else
		{
				message($lang['ACCEPT_NOTPERMITTED_MESSAGE'],$config,$lang);
		}
	}
	else
	{
		$result=mysql_query("SELECT 1 FROM `".$config['db']['pre']."projects` WHERE `project_id` = '" . validate_input($_GET['id']) . "' AND `checkstamp`='".validate_input($_GET['check'])."' AND project_status='1' AND provider_id='".$_SESSION['user']['id']."' LIMIT 1 ;");
		if(mysql_num_rows($result)>0)
		{
			$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/accept.html");
			$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['ACCPROJECT']));
			$page->SetParameter ('ID', $_GET['id']);
			$page->SetParameter ('CHECKSTAMP', $_GET['check']);
			$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
			$page->CreatePageEcho($lang,$config);
		}
		else
		{
			message($lang['ACCEPT_NOTPERMITTED_MESSAGE'],$config,$lang);
		}
	}
}
else
{
	header("Location: login.php");
	exit;
}
?>