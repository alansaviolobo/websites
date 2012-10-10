<?php
require_once('includes/config.php');
require_once('includes/classes/class.template_engine.php');
require_once("includes/classes/class.phpmailer.php");
require_once('includes/functions/func.global.php');
require_once('includes/functions/func.users.php');
$config['lang'] = check_user_lang($config);
require_once('includes/lang/lang_'.$config['lang'].'.php');

db_connect($config);
session_start();
check_cookie($_SESSION,$config);

// Get Settings
$settings = get_settings('bid.php',$config);

if(checkloggedin())
{
	if($_SESSION['user']['type'] == 'provider')
	{
		if($config['pay_type'] == 1)
		{
			$paypal_check = mysql_fetch_row(mysql_query("SELECT provider_paypal FROM ".$config['db']['pre']."providers WHERE provider_id='".$_SESSION['user']['id']."' LIMIT 1"));
		
			if(trim($paypal_check[0]) == '')
			{
				header("Location: paypal_add.php?id=".$_GET['id']);
				exit;
			}
		}
	
		$query = "SELECT project_status,buyer_id,project_title FROM ".$config['db']['pre']."projects WHERE project_id='" . validate_input($_GET['id']) . "' LIMIT 1";
		$query_result = mysql_query($query);
		while ($info = @mysql_fetch_array($query_result))
		{
			$project_status = $info['project_status'];
			$buyer_id = $info['buyer_id'];
			$project_title = $info['project_title'];
		}
		
		$check_blocked = mysql_num_rows(mysql_query("SELECT 1 FROM ".$config['db']['pre']."block WHERE user_id='".validate_input($buyer_id)."' AND user_id2='".$_SESSION['user']['id']."' LIMIT 1"));
			
		if($check_blocked)
		{
			message($lang['BIDSORRYBLOCK'],$config,$lang);
		}
			
		if(isset($project_status))
		{
			if($project_status == 1)
			{
				message($lang['BIDFROZEN'],$config,$lang);
			}
			elseif($project_status == 2)
			{
				message($lang['BIDCLOSED'],$config,$lang);
			}
		}
	
		if(!isset($_POST['Submit']))
		{	
			$query = "SELECT bid_desc,bid_days,bid_amount FROM ".$config['db']['pre']."bids WHERE user_id='" . $_SESSION['user']['id'] . "' AND project_id='" . validate_input($_GET['id']) . "' LIMIT 1";
			$query_result = mysql_query($query);
			$num_rows = mysql_num_rows($query_result);
			
			if($num_rows == 0)
			{
				$amount = '';
				$days = 7;
				$details = '';
			}
			else
			{
				while ($info = @mysql_fetch_array($query_result))
				{
					$amount = $info['bid_amount'];
					$days = $info['bid_days'];
					$details = $info['bid_desc'];
				}
			}
		
			$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/bid.html");
			
			$page->SetLoop ('ERRORS', array());
						
			$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang));
			$page->SetParameter ('ID', $_GET['id']);
			$page->SetParameter ('AMOUNT', $amount);
			$page->SetParameter ('DAYS', $days);
			$page->SetParameter ('DETAILS', $details);
			$page->SetParameter ('USERNAME', $_SESSION['user']['name']);
			$page->SetParameter ('BID_ATTACH', $settings['bid_attach']);
			$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
			$page->CreatePageEcho($lang,$config);
		}
		else
		{		
			if(isset($config['bid_fee']))
			{
				if($config['bid_fee'] > 0)
				{
					check_negative_balance($config);
					
					if(!check_balance($_SESSION['user']['type'], $_SESSION['user']['id'],$config['bid_fee'],$config))
					{
						message($lang['NOTENOUGHMONEY'], $config,$lang);
						exit;
					}
				}
			}
		
			$query = "SELECT rule_eregi,rule_msg FROM `".$config['db']['pre']."rules` WHERE page='bid.php'";
			$query_result = @mysql_query ($query) OR error(mysql_error());
			while ($info = @mysql_fetch_array($query_result))
			{
				if(eregi($info['rule_eregi'], $_POST['details']))
				{
					message($info['rule_msg'],$config,$lang);
				}
			}
			
			// Check that bid amount is valid
			if(eregi("[^0-9]",$_POST['amount']))
			{
				message($lang['INVALIDBID'],$config,$lang);
			}
				
			$query = "SELECT 1 FROM ".$config['db']['pre']."bids WHERE user_id='" . $_SESSION['user']['id'] . "' AND project_id='" . validate_input($_GET['id']) . "' LIMIT 1";
			$query_result = mysql_query($query);
			$num_rows = mysql_num_rows($query_result);
			
			$file_id = 0;
			
			if($settings['bid_attach'])
			{
				if(isset($_FILES['attachment']))
				{
					if($_FILES['attachment']['error'] == 0)
					{
						$fileinfo=pathinfo($_FILES['attachment']['name']);

						$sql = "SELECT type_id,type_ext,type_content,max_size FROM ".$config['db']['pre']."attachments_types WHERE type_ext='".validate_input($fileinfo['extension'])."' AND type_content='".validate_input($_FILES['attachment']['type'])."' AND max_size>'".validate_input($_FILES['attachment']['size'])."' LIMIT 1";
						$query_result = mysql_query($sql);
						$num=mysql_num_rows($query_result);
						if($num>0)
						{
							$fp = fopen($_FILES['attachment']['tmp_name'], 'r');
							$content = fread($fp, $_FILES['attachment']['size']);
							$content = addslashes($content);
							fclose($fp);
						
							mysql_query("INSERT INTO `".$config['db']['pre']."attachments` ( `file_id` , `file_name` , `file_content` , `file_type` , `file_size` ) VALUES ('', '" . validate_input($_FILES['attachment']['name']) . "', '" . $content . "', '" . validate_input($_FILES['attachment']['type'])  . "', '" . validate_input($_FILES['attachment']['size'])  . "');");
						
							$file_id = mysql_insert_id();
						}
						else
						{
							message($lang['WRONGFILETYPE'],$config,$lang,'');
						}
					}
				}
			}
						
			if($num_rows == 0)
			{		
				mysql_query("UPDATE `".$config['db']['pre']."projects` SET `project_bids` = project_bids+1,`project_avgbid` = '" . validate_input(calculate_avg_bid($config,$_POST['id'],$_POST['amount'])) . "' WHERE `project_id` = '" . validate_input($_POST['id']) . "' LIMIT 1 ;");
				
				mysql_query("INSERT INTO `".$config['db']['pre']."bids` ( `bid_id` , `project_id` , `user_id` , `bid_days` , `bid_amount` , `bid_time` , `bid_desc` , `file_id` ) VALUES ('', '" . validate_input($_POST['id']) . "', '" . $_SESSION['user']['id'] . "', '" . validate_input($_POST['days']) . "', '" . validate_input($_POST['amount']) . "', '" . time() . "', '" . validate_input($_POST['details']) . "', '" . validate_input($file_id) . "');");

				if(isset($config['bid_fee']))
				{
					if($config['bid_fee'] > 0)
					{
						check_negative_balance($config);
						
						if(!check_balance($_SESSION['user']['type'], $_SESSION['user']['id'],$config['bid_fee'],$config))
						{
							message($lang['NOTENOUGHMONEY'], $config,$lang);
							exit;
						}
						
						minus_balance($_SESSION['user']['type'], $_SESSION['user']['id'],$config['bid_fee'],$config);
					}
				}
			}
			else
			{
				if($file_id)
				{
					mysql_query("UPDATE `".$config['db']['pre']."bids` SET `bid_days` = '" . validate_input($_POST['days']) . "',`bid_amount` = '" . validate_input($_POST['amount']) . "',`bid_time` = '" . time() . "',`bid_desc` = '" . validate_input($_POST['details']) . "',`file_id` = '" . validate_input($file_id) . "' WHERE `project_id` = '" . validate_input($_POST['id']) . "' AND `user_id` = '" . $_SESSION['user']['id'] . "' LIMIT 1 ;");
				}
				else
				{
					mysql_query("UPDATE `".$config['db']['pre']."bids` SET `bid_days` = '" . validate_input($_POST['days']) . "',`bid_amount` = '" . validate_input($_POST['amount']) . "',`bid_time` = '" . time() . "',`bid_desc` = '" . validate_input($_POST['details']) . "' WHERE `project_id` = '" . validate_input($_POST['id']) . "' AND `user_id` = '" . $_SESSION['user']['id'] . "' LIMIT 1 ;");
				}
				
				mysql_query("UPDATE `".$config['db']['pre']."projects` SET `project_avgbid` = '" . validate_input(calculate_avg_bid($config,$_POST['id'],$_POST['amount'])) . "' WHERE `project_id` = '" . validate_input($_POST['id']) . "' LIMIT 1 ;");
			}
			
			$buyer_email = mysql_fetch_row(mysql_query("SELECT buyer_email FROM ".$config['db']['pre']."buyers WHERE buyer_id='".$buyer_id."' LIMIT 1"));
			
			$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/email_project_bid.html");
			$page->SetParameter ('SITE_TITLE', $config['site_title']);
			$page->SetParameter ('SITE_URL', $config['site_url']);
			$page->SetParameter ('PROJECT_TITLE', $project_title);
			$page->SetParameter ('AMOUNT', $_POST['amount']);
			$page->SetParameter ('USER', $_SESSION['user']['name']);
			$email_body = $page->CreatePageReturn($lang,$config);
	
			email($buyer_email[0],$config['site_title'].$lang['NEWBID'],$email_body,$config);
			
			header("Location: project.php?id=" . $_POST['id']);
			exit;
		}
	}
	else
	{
		header("Location: login.php?ref=" . urlencode('bid.php?id=' . $_GET['id']));
		exit;
	}
}
else
{
	header("Location: login.php?ref=" . urlencode('bid.php?id=' . $_GET['id']));
	exit;
}
?>