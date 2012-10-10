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

if(isset($_POST['id']))
{
	$_GET['id'] = $_POST['id'];
}

if(checkloggedin())
{
	check_negative_balance($config);

	if(check_account_frozen($_SESSION['user']['id'], $_SESSION['user']['type'],$config))
	{
		message($lang['ACCOUNTFROZEN'],$config,$lang);
	}
	else
	{
		if(!isset($_POST['message']))
		{
			$query = "SELECT project_title,buyer_id FROM `".$config['db']['pre']."projects` WHERE project_id='" . validate_input($_GET['id']) . "' LIMIT 1";
			$query_result = @mysql_query ($query) OR error(mysql_error());
			while ($info = @mysql_fetch_array($query_result))
			{
				$project_title = $info['project_title'];
				$buyer_id  = $info['buyer_id'];
			}
			
			if(($_SESSION['user']['type'] == 'buyer') and ($_SESSION['user']['id'] != $buyer_id))
			{
				message($lang['BUYERPOST'], $config,$lang);
			}
			
			$to = array();
			$to2 = array();
			$where = '';
			
			if($_SESSION['user']['type'] == 'provider')
			{
				$query = "SELECT buyer_username FROM ".$config['db']['pre']."buyers WHERE buyer_id='" . $buyer_id . "' LIMIT 1";
				$query_result = @mysql_query ($query) OR error(mysql_error());
				while ($info = @mysql_fetch_array($query_result))
				{
					$to[$buyer_id]['id'] = $buyer_id;
					$to[$buyer_id]['username'] = $info['buyer_username'];
				}
			}
			else
			{
				$count = 0;
				
				$query = "SELECT from_id FROM ".$config['db']['pre']."messages WHERE from_type='1' AND project_id='" . validate_input($_GET['id']) . "'";
				$query_result = @mysql_query ($query) OR error(mysql_error());
				while ($info = @mysql_fetch_array($query_result))
				{
					$to2[$info['from_id']] = $info['from_id'];
				}
				
				$query = "SELECT user_id FROM `".$config['db']['pre']."bids` WHERE project_id='" . validate_input($_GET['id']) . "'";
				$query_result = mysql_query ($query) OR error(mysql_error());
				while ($info = mysql_fetch_array($query_result))
				{
					$to2[$info['user_id']] = $info['user_id'];
				}
				
				foreach ($to2 as $key => $value) 
				{
				   	if($count == 0)
					{
						$where = "WHERE provider_id='" . $key . "'";
					}
					else
					{
						$where.= " OR provider_id='" . $key . "'";
					}

					$count++;
				}
				
				if($where != '')
				{
					$where.= " LIMIT " . count($to2);
				
					$query = "SELECT provider_id,provider_username FROM ".$config['db']['pre']."providers " . $where;
					$query_result = @mysql_query ($query) OR error(mysql_error());
					while ($info = @mysql_fetch_array($query_result))
					{
						$to[$info['provider_id']]['username'] = $info['provider_username'];
						$to[$info['provider_id']]['id'] = $info['provider_id'];
					}
				}
			}
		
			if(($config['provider_public_post']=='1') OR ($_SESSION['user']['type']=='buyer'))
			{
				$everyone='<option value="0" selected>'.$lang['EVERYONE'].'</option>';
			}
			else
			{
				$everyone='';
			}
			
			$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/board_post.html");
			$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang));
			$page->SetLoop ('ERRORS', array());
			$page->SetParameter ('USERNAME', $_SESSION['user']['name']);
			$page->SetParameter ('PROJECT_NAME', $project_title);
			$page->SetParameter ('MESSAGE', '');
			$page->SetParameter ('PRIVATE', '');
			$page->SetParameter ('EVERYONE', $everyone);
			$page->SetLoop ('TO', $to);
			$page->SetParameter ('ID', $_GET['id']);
			$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
			$page->CreatePageEcho($lang,$config);
		}
		else
		{	
			if($_SESSION['user']['type'] == 'buyer')
			{
				$type = 0;
			}
			else
			{
				$type = 1;
			}
			
			$errors = array();
			
			if(trim($_POST['message']) == '')
			{
				$errors[]['message'] = $lang['POSTERROR'];
			}
			
			$query = "SELECT rule_eregi,rule_msg FROM `".$config['db']['pre']."rules` WHERE page='board_post.php'";
			$query_result = @mysql_query ($query) OR error(mysql_error());
			while ($info = @mysql_fetch_array($query_result))
			{
				if($info['rule_eregi'] != '')
				{
					if(eregi($info['rule_eregi'], $_POST['message']))
					{
						$errors[]['message'] = $info['rule_msg'];
					}
				}
			}
			
			if(count($errors) > 0)
			{
				$query = "SELECT project_title,buyer_id FROM `".$config['db']['pre']."projects` WHERE project_id='" . validate_input($_GET['id']) . "' LIMIT 1";
				$query_result = @mysql_query ($query) OR error(mysql_error());
				while ($info = @mysql_fetch_array($query_result))
				{
					$project_title = $info['project_title'];
					$buyer_id  = $info['buyer_id'];
				}
				
				if(($_SESSION['user']['type'] == 'buyer') and ($_SESSION['user']['id'] != $buyer_id))
				{
					message($lang['BUYERPOST'], $config,$lang);
				}
				
				$to = array();
				$to2 = array();
				$where = '';
				
				if($_SESSION['user']['type'] == 'provider')
				{
					$query = "SELECT buyer_username FROM ".$config['db']['pre']."buyers WHERE buyer_id='" . $buyer_id . "' LIMIT 1";
					$query_result = @mysql_query ($query) OR error(mysql_error());
					while ($info = @mysql_fetch_array($query_result))
					{
						$to[$buyer_id]['id'] = $buyer_id;
						$to[$buyer_id]['username'] = $info['buyer_username'];
					}
				}
				else
				{
					$count = 0;
					
					$query = "SELECT from_id FROM ".$config['db']['pre']."messages WHERE from_type='1' AND project_id='" . validate_input($_GET['id']) . "'";
					$query_result = @mysql_query ($query) OR error(mysql_error());
					while ($info = @mysql_fetch_array($query_result))
					{
						$to2[$info['from_id']] = $info['from_id'];
					}
				
					$query = "SELECT user_id FROM `".$config['db']['pre']."bids` WHERE project_id='" . validate_input($_GET['id']) . "'";
					$query_result = mysql_query ($query) OR error(mysql_error());
					while ($info = mysql_fetch_array($query_result))
					{
						$to2[$info['user_id']] = $info['user_id'];
					}
					
					foreach ($to2 as $key => $value) 
					{
						if($count == 0)
						{
							$where = "WHERE provider_id='" . $key . "'";
						}
						else
						{
							$where.= " OR provider_id='" . $key . "'";
						}
						$count++;
					}
					
					if($where != '')
					{
						$where.= " LIMIT " . count($to2);
					
						$query = "SELECT provider_id,provider_username FROM ".$config['db']['pre']."providers " . $where;
						$query_result = @mysql_query ($query) OR error(mysql_error());
						while ($info = @mysql_fetch_array($query_result))
						{
							$to[$info['provider_id']]['username'] = $info['provider_username'];
							$to[$info['provider_id']]['id'] = $info['provider_id'];
						}
					}
				}
			
				if(($config['provider_public_post']=='1') OR ($_SESSION['user']['type']=='buyer'))
				{
					$everyone='<option value="0" selected>'.$lang['EVERYONE'].'</option>';
				}
				else
				{
					$everyone='';
				}
			
				$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/board_post.html");
				$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang));
				$page->SetLoop ('ERRORS', $errors);
				$page->SetParameter ('USERNAME', $_SESSION['user']['name']);
				$page->SetParameter ('PROJECT_NAME', $project_title);
				$page->SetParameter ('MESSAGE', $_POST['message']);
				$page->SetParameter ('PRIVATE', $_POST['private']);
				$page->SetParameter ('ID', $_POST['id']);
				$page->SetParameter ('EVERYONE', $everyone);
				$page->SetLoop ('TO', $to);
				$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
				$page->CreatePageEcho($lang,$config);
				exit;
			}
		
			mysql_query("INSERT INTO `".$config['db']['pre']."messages` ( `message_id` , `project_id` , `message_date` , `from_id` , `from_type` , `to_id` , `message_content` ) VALUES ('', '" . validate_input($_POST['id']) . "', '" . time() . "', '" . $_SESSION['user']['id'] . "', '" . $type . "', '" . validate_input($_POST['private']) . "', '" . validate_input($_POST['message']) . "');");
			
			$query = "SELECT project_title,buyer_id FROM `".$config['db']['pre']."projects` WHERE project_id='" . validate_input($_POST['id']) . "' LIMIT 1";
			$query_result = @mysql_query ($query) OR error(mysql_error());
			while ($info = @mysql_fetch_array($query_result))
			{
				$project_title = $info['project_title'];
				$buyer_id  = $info['buyer_id'];
			}
		
			if($_POST['private'])
			{
				if($_SESSION['user']['type'] == 'provider')
				{
					$buyer_email = mysql_fetch_row(mysql_query("SELECT buyer_email FROM ".$config['db']['pre']."buyers WHERE buyer_id='".validate_input($_POST['private'])."' LIMIT 1"));
	
					$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/email_board_post.html");
					$page->SetParameter ('SITE_TITLE', $config['site_title']);
					$page->SetParameter ('SITE_URL', $config['site_url']);
					$page->SetParameter ('PROJECT_TITLE', $project_title);
					$page->SetParameter ('PROJECT_ID', $_POST['id']);
					$page->SetParameter ('USER', $_SESSION['user']['name']);
					$email_body = $page->CreatePageReturn($lang,$config);
				
					email($buyer_email[0],$config['site_title'].' - '.$lang['MESSPOST'],$email_body,$config);
				}
				else
				{
					$provider_email = mysql_fetch_row(mysql_query("SELECT provider_email FROM ".$config['db']['pre']."providers WHERE provider_id='".validate_input($_POST['private'])."' LIMIT 1"));
	
					$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/email_board_post.html");
					$page->SetParameter ('SITE_TITLE', $config['site_title']);
					$page->SetParameter ('SITE_URL', $config['site_url']);
					$page->SetParameter ('PROJECT_TITLE', $project_title);
					$page->SetParameter ('PROJECT_ID', $_POST['id']);
					$page->SetParameter ('USER', $_SESSION['user']['name']);
					$email_body = $page->CreatePageReturn($lang,$config);
				
					email($provider_email[0],$config['site_title'].' - '.$lang['MESSPOST'],$email_body,$config);
				}
			}
		
			header("Location: board.php?i=" . $_POST['id']);
			exit;
		}
	}
}
else
{
	header("Location: login.php?ref=board.php%3Fi%3D" . $_GET['id']);
	exit;
}
?>