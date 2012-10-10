<?php
require_once('includes/config.php');
require_once('includes/classes/class.template_engine.php');
require_once('includes/classes/class.phpmailer.php');
require_once('includes/functions/func.global.php');
require_once('includes/functions/func.users.php');
session_start();
$config['lang'] = check_user_lang($config);
require_once('includes/lang/lang_'.$config['lang'].'.php');

db_connect($config);

check_cookie($_SESSION,$config);

if(!isset($_GET['page']))
{
	$_GET['page'] = 1;
}
if(!isset($_GET['cmd']))
{
	$_GET['cmd'] = 'inbox';
}

if(!$config['mailbox_en'])
{
	message($lang['MESSDIS'], $config, $lang,'',false);
}

if(checkloggedin())
{
	switch ($_GET['cmd']) 
	{
		case 'delete':	
			if(isset($_POST['id']))
			{
				$_GET['id'] = $_POST['id'];
			}
		
			if(is_array($_GET['id']))
			{
				foreach ($_GET['id'] as $mkey => $mid)
				{
					if($_SESSION['user']['type'] == 'buyer')
					{
						$message_id = mysql_num_rows(mysql_query("SELECT 1 FROM `".$config['db']['pre']."inbox` WHERE `message_id` = '".validate_input($mid)."' AND `message_to` = '".$_SESSION['user']['id']."' AND `message_totype` = '0' LIMIT 1"));
		
						if($message_id)
						{
							mysql_query("DELETE FROM `".$config['db']['pre']."inbox` WHERE `message_id` = '".validate_input($mid)."' AND `message_to` = '".$_SESSION['user']['id']."' AND `message_totype` = '0' LIMIT 1");
						}
					}
					else
					{
						$message_id = mysql_num_rows(mysql_query("SELECT 1 FROM `".$config['db']['pre']."inbox` WHERE `message_id` = '".validate_input($mid)."' AND `message_to` = '".$_SESSION['user']['id']."' AND `message_totype` = '1' LIMIT 1"));
					
						if($message_id)
						{
							mysql_query("DELETE FROM `".$config['db']['pre']."inbox` WHERE `message_id` = '".validate_input($mid)."' AND `message_to` = '".$_SESSION['user']['id']."' AND `message_totype` = '1' LIMIT 1");
						}
					}
				}
			}
			else
			{		
				if($_SESSION['user']['type'] == 'buyer')
				{
					$message_id = mysql_num_rows(mysql_query("SELECT 1 FROM `".$config['db']['pre']."inbox` WHERE `message_id` = '".validate_input($_GET['id'])."' AND `message_to` = '".$_SESSION['user']['id']."' AND `message_totype` = '0' LIMIT 1"));
	
					if($message_id)
					{
						mysql_query("DELETE FROM `".$config['db']['pre']."inbox` WHERE `message_id` = '".validate_input($_GET['id'])."' AND `message_to` = '".$_SESSION['user']['id']."' AND `message_totype` = '0' LIMIT 1");
					}
				}
				else
				{
					$message_id = mysql_num_rows(mysql_query("SELECT 1 FROM `".$config['db']['pre']."inbox` WHERE `message_id` = '".validate_input($_GET['id'])."' AND `message_to` = '".$_SESSION['user']['id']."' AND `message_totype` = '1' LIMIT 1"));
				
					if($message_id)
					{
						mysql_query("DELETE FROM `".$config['db']['pre']."inbox` WHERE `message_id` = '".validate_input($_GET['id'])."' AND `message_to` = '".$_SESSION['user']['id']."' AND `message_totype` = '1' LIMIT 1");
					}
				}
			}
			
			header("Location: private.php");
			exit;
			break;
		case 'inbox':
			$messages = array();
			$buyers = array();
			$providers = array();
			$pro_where = '';
			$buy_where = '';
			
			if($_SESSION['user']['type'] == 'buyer')
			{
				$query = "SELECT message_id,message_subject,message_date,message_read,message_from,message_fromtype FROM `".$config['db']['pre']."inbox` WHERE message_to='".$_SESSION['user']['id']."' AND message_totype='0' ORDER BY message_id DESC LIMIT ".validate_input(($_GET['page']-1)*15).",15";
			}
			else
			{
				$query = "SELECT message_id,message_subject,message_date,message_read,message_from,message_fromtype FROM `".$config['db']['pre']."inbox` WHERE message_to='".$_SESSION['user']['id']."' AND message_totype='1' ORDER BY message_id DESC LIMIT ".validate_input(($_GET['page']-1)*15).",15";
			}
			$query_result = @mysql_query ($query) OR error(mysql_error());
			while ($info = @mysql_fetch_array($query_result))
			{
				$messages[$info['message_id']]['id'] = $info['message_id'];
				$messages[$info['message_id']]['subject'] = $info['message_subject'];
				$messages[$info['message_id']]['date'] = date("Y-m-d H:i:s",$info['message_date']);
				$messages[$info['message_id']]['read'] = $info['message_read'];
				$messages[$info['message_id']]['from_id'] = $info['message_from'];
				
				if($info['message_fromtype'] == '0')
				{
					if($buy_where == '')
					{
						$buy_where = "buyer_id='".$info['message_from']."'";
					}
					else
					{
						$buy_where.= " OR buyer_id='".$info['message_from']."'";
					}
					
					$messages[$info['message_id']]['from_type'] = 'buyer';
				}
				else
				{
					if($pro_where == '')
					{
						$pro_where = "provider_id='".$info['message_from']."'";
					}
					else
					{
						$pro_where.= " OR provider_id='".$info['message_from']."'";
					}
					
					$messages[$info['message_id']]['from_type'] = 'provider';
				}
			}
			
			if($buy_where != '')
			{
				$query = "SELECT buyer_id,buyer_username FROM `".$config['db']['pre']."buyers` WHERE ".$buy_where." LIMIT 15";
				$query_result = @mysql_query ($query) OR error(mysql_error());
				while ($info = @mysql_fetch_array($query_result))
				{
					$buyers[$info['buyer_id']] = $info['buyer_username'];
				}
			}
			if($pro_where != '')
			{
				$query = "SELECT provider_id,provider_username FROM `".$config['db']['pre']."providers` WHERE ".$pro_where." LIMIT 15";
				$query_result = @mysql_query ($query) OR error(mysql_error());
				while ($info = @mysql_fetch_array($query_result))
				{
					$providers[$info['provider_id']] = $info['provider_username'];
				}
			}
			
			foreach ($messages as $key => $value)
			{
				if($value['from_type'] == 'buyer')
				{
					if(isset($buyers[$value['from_id']]))
					{
						$messages[$key]['from_username'] = $buyers[$value['from_id']];
					}
					else
					{
						$messages[$key]['from_username'] = '';
					}
				}
				else
				{
					if(isset($providers[$value['from_id']]))
					{
						$messages[$key]['from_username'] = $providers[$value['from_id']];
					}
					else
					{
						$messages[$key]['from_username'] = '';
					}
				}
			}
		
			$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/private_inbox.html");
			$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['INBOX']));
			$page->SetLoop ('MESSAGES', $messages);
			$page->SetParameter ('MESSAGE_COUNT', count($messages));
			$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
			$page->CreatePageEcho($lang,$config);
			break;
		case 'send':
			$message_subject = '';
			$message_body = '';
			$errors = 0;
			
			if(isset($_POST['mid']))
			{
				$_GET['mid'] = $_POST['mid'];
			}
			if(isset($_POST['toid']))
			{
				$_GET['toid'] = $_POST['toid'];
			}
			if(isset($_POST['totype']))
			{
				$_GET['totype'] = $_POST['totype'];
			}
			
			if(isset($_GET['mid']))
			{
				if($_SESSION['user']['type'] == 'buyer')
				{
					$mess_details = mysql_fetch_row(mysql_query("SELECT message_from,message_fromtype,message_subject,message_body,message_date FROM `".$config['db']['pre']."inbox` WHERE message_to='".$_SESSION['user']['id']."' AND message_totype='0' AND message_id='".validate_input($_GET['mid'])."' LIMIT 1"));
				}
				elseif($_SESSION['user']['type'] == 'provider')
				{
					$mess_details = mysql_fetch_row(mysql_query("SELECT message_from,message_fromtype,message_subject,message_body,message_date FROM `".$config['db']['pre']."inbox` WHERE message_to='".$_SESSION['user']['id']."' AND message_totype='1' AND message_id='".validate_input($_GET['mid'])."' LIMIT 1"));
				}
				
				if(isset($mess_details[1]))
				{
					$_GET['toid'] = $mess_details[0];
					
					if($mess_details[1] == 0)
					{
						$_GET['totype'] = 'buyer';
					}
					elseif($mess_details[1] == 1)
					{
						$_GET['totype'] = 'provider';
					}
				}
			}
			
			if($_GET['toid'] == $_SESSION['user']['id'])
			{
				if($_SESSION['user']['type'] == $_GET['totype'])
				{
					message($lang['MESSSELF'], $config, $lang,'',false);
				}
			}
			
			if(!isset($_GET['toid']))
			{
				message($lang['MESSVAL'], $config, $lang,'',false);
			}
			elseif(!isset($_GET['totype']))
			{
				message($lang['MESSVAL'], $config, $lang,'',false);
			}
			
			if($_GET['totype'] == 'buyer')
			{
				$user_details = mysql_fetch_row(mysql_query("SELECT buyer_username,buyer_email FROM `".$config['db']['pre']."buyers` WHERE buyer_id='".validate_input($_GET['toid'])."' LIMIT 1"));
			}
			elseif($_GET['totype'] == 'provider')
			{
				$user_details = mysql_fetch_row(mysql_query("SELECT provider_username,provider_email FROM `".$config['db']['pre']."providers` WHERE provider_id='".validate_input($_GET['toid'])."' LIMIT 1"));
			}
			
			if(!$user_details[0])
			{
				message($lang['VALIDUSER'], $config, $lang,'',false);
			}
			
			if(isset($_POST['body']))
			{
				// Remove HTML from subject and body
				$_POST['body'] = strip_tags($_POST['body']);
				$_POST['subject'] = strip_tags($_POST['subject']);
				// Limit the length of both fields
				$_POST['body'] = substr($_POST['body'],0,2000);
				$_POST['subject'] = substr($_POST['subject'],0,50);
				
				if(trim($_POST['subject']) == '')
				{
					$errors++;
				}
				if(trim($_POST['body']) == '')
				{
					$errors++;
				}
				
				if($errors == 0)
				{
					$fromtype = 0;
				
					if($_SESSION['user']['type'] == 'buyer')
					{
						$fromtype = 0;
					}
					else
					{
						$fromtype = 1;
					}
				
					if($_GET['totype'] == 'buyer')
					{
						mysql_query("INSERT INTO `".$config['db']['pre']."inbox` ( `message_from` , `message_fromtype` , `message_to` , `message_totype` , `message_date` , `message_subject` , `message_body` , `message_read` ) VALUES ('".$_SESSION['user']['id']."', '".$fromtype."', '".validate_input($_GET['toid'])."', '0', '".time()."', '".validate_input($_POST['subject'])."', '".validate_input($_POST['body'])."', '0');");
						
						$page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/email_pm.html');
						$page->SetParameter ('SITE_TITLE', $config['site_title']);
						$page->SetParameter ('SITE_URL', $config['site_url']);
						$page->SetParameter ('USER', $_SESSION['user']['name']);
						$email_body = $page->CreatePageReturn($lang,$config);
					
						if(isset($user_details[1]))
						{
							if($user_details[1])
							{
								email($user_details[1],$config['site_title'].' - '.$lang['PRIVMSG'],$email_body,$config);
							}
						}
					}
					else
					{
						mysql_query("INSERT INTO `".$config['db']['pre']."inbox` ( `message_from` , `message_fromtype` , `message_to` , `message_totype` , `message_date` , `message_subject` , `message_body` , `message_read` ) VALUES ('".$_SESSION['user']['id']."', '".$fromtype."', '".validate_input($_GET['toid'])."', '1', '".time()."', '".validate_input($_POST['subject'])."', '".validate_input($_POST['body'])."', '0');");
						
						$page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/email_pm.html');
						$page->SetParameter ('SITE_TITLE', $config['site_title']);
						$page->SetParameter ('SITE_URL', $config['site_url']);
						$page->SetParameter ('USER', $_SESSION['user']['name']);
						$email_body = $page->CreatePageReturn($lang,$config);
					
						if(isset($user_details[1]))
						{
							if($user_details[1])
							{
								email($user_details[1],$config['site_title'].' - '.$lang['PRIVMSG'],$email_body,$config);
							}
						}
					}
				
					message($lang['MESSSENT'], $config, $lang,'private.php');
				}
			}
			
			if($errors > 0)
			{
				$message_subject = stripslashes($_POST['subject']);
				$message_body = stripslashes($_POST['body']);
			}
			else
			{
				if(isset($mess_details[1]))
				{
					if(eregi('Re:',$mess_details[2]))
					{
						$message_subject = stripslashes($mess_details[2]);
					}
					else
					{
						$message_subject = 'Re: '.stripslashes($mess_details[2]);
					}
					
					$message_body = "\r\n\r\n\r\n\r\n".$lang['ORIGMESS']."\r\nFrom: ".$user_details[0]."\r\nSent: ".date("Y-m-d H:i:s",$mess_details[4])."\r\n-------------------------------------\r\n\r\n".stripslashes($mess_details[3]);
				}
			}
		
			$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/private_send.html");
			$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['INBOX']));
			$page->SetParameter ('MESSAGE_SUBJECT', $message_subject);
			$page->SetParameter ('MESSAGE_BODY', $message_body);
			$page->SetParameter ('TO_USERNAME', $user_details[0]);
			$page->SetParameter ('TO_ID', $_GET['toid']);
			$page->SetParameter ('TO_TYPE', $_GET['totype']);
			if($_GET['totype'] == 'buyer')
			{
				$page->SetParameter ('TO_TYPE_DISP', $lang['BUYERU']);
			}
			else
			{
				$page->SetParameter ('TO_TYPE_DISP', $lang['PROVIDERU']);
			}
			$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
			$page->CreatePageEcho($lang,$config);
			break;
		case 'read':
			$message_info = mysql_fetch_array(mysql_query("SELECT * FROM `".$config['db']['pre']."inbox` WHERE message_id='".validate_input($_GET['id'])."' LIMIT 1"));
		
			if(!isset($message_info['message_id']))
			{
				exit;
			}
			else
			{
				if(!$message_info['message_id'])
				{
					exit;
				}
			}
		
			if($message_info['message_read'] == 0)
			{
				mysql_query("UPDATE `".$config['db']['pre']."inbox` SET `message_read` = '1' WHERE `message_id` = '".validate_input($_GET['id'])."' LIMIT 1 ;");
			}
			
			if($message_info['message_fromtype'] == 0)
			{
				$user_info = mysql_fetch_row(mysql_query("SELECT buyer_username FROM `".$config['db']['pre']."buyers` WHERE buyer_id='".validate_input($message_info['message_from'])."' LIMIT 1"));
			}
			else
			{
				$user_info = mysql_fetch_row(mysql_query("SELECT provider_username FROM `".$config['db']['pre']."providers` WHERE provider_id='".validate_input($message_info['message_from'])."' LIMIT 1"));
			}
		
			$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/private_read.html");
			$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['INBOX']));
			$page->SetParameter ('SUBJECT', stripslashes($message_info['message_subject']));
			$page->SetParameter ('DATE', date("Y-m-d H:i:s",$message_info['message_date']));
			$page->SetParameter ('BODY', nl2br(stripslashes($message_info['message_body'])));
			$page->SetParameter ('MESSAGE_ID', $message_info['message_id']);
			$page->SetParameter ('USERNAME', $user_info[0]);
			$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
			$page->CreatePageEcho($lang,$config);
			break;
	}
}
else
{
	header("Location: login.php");
	exit;
}
?>