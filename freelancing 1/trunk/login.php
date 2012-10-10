<?php
require_once('includes/config.php');
require_once('includes/classes/class.template_engine.php');
require_once('includes/functions/func.global.php');
require_once('includes/functions/func.users.php');
session_start();
$config['lang'] = check_user_lang($config);
require_once('includes/lang/lang_'.$config['lang'].'.php');

db_connect($config);


if(isset($_POST['forgot']))
{
	$_GET['forgot'] = $_POST['forgot'];
}
if(isset($_POST['r']))
{
	$_GET['r'] = $_POST['r'];
}
if(isset($_POST['e']))
{
	$_GET['e'] = $_POST['e'];
}
if(isset($_POST['t']))
{
	$_GET['t'] = $_POST['t'];
}
if(isset($_POST['type']))
{
	$_GET['type'] = $_POST['type'];
}

// Check if they are using a forgot password link
if(isset($_GET['forgot']))
{
	if(!isset($_GET['start']))
	{
		if($_GET['type'] == 'buyer')
		{
			$check_forgot = mysql_fetch_row(mysql_query("SELECT buyer_id,buyer_forgot,buyer_username FROM ".$config['db']['pre']."buyers WHERE buyer_email='".validate_input($_GET['e'])."' LIMIT 1"));
		}
		else
		{
			$check_forgot = mysql_fetch_row(mysql_query("SELECT provider_id,provider_forgot,provider_username FROM ".$config['db']['pre']."providers WHERE provider_email='".validate_input($_GET['e'])."' LIMIT 1"));
		}
	
		if($_GET['forgot'] == $check_forgot[1])
		{
			if($_GET['forgot'] == md5($_GET['t'].'_:_'.$_GET['r'].'_:_'.$_GET['e']))
			{
				// Check that the link hasn't timed out (30 minutes old)
				if($_GET['t'] > (time()-108000))
				{
					$forgot_error = '';
				
					if(isset($_POST['password']))
					{
						if( (strlen($_POST['password']) < 4) OR (strlen($_POST['password']) > 16) )
						{
							$forgot_error = $lang['PASSCHAR'];
						}
						else
						{
							if($_POST['password'] == $_POST['password2'])
							{
								if($_GET['type'] == 'buyer')
								{
									mysql_query("UPDATE `".$config['db']['pre']."buyers` SET `buyer_forgot` = '' WHERE `buyer_id` =".validate_input($check_forgot[0])." LIMIT 1 ;");
									mysql_query("UPDATE `".$config['db']['pre']."buyers` SET `buyer_password` = '".addslashes(md5($_POST['password']))."' WHERE `buyer_id` =".validate_input($check_forgot[0])." LIMIT 1 ;");
								}
								else
								{
									mysql_query("UPDATE `".$config['db']['pre']."providers` SET `provider_forgot` = '' WHERE `provider_id` =".validate_input($check_forgot[0])." LIMIT 1 ;");
									mysql_query("UPDATE `".$config['db']['pre']."providers` SET `provider_password` = '".addslashes(md5($_POST['password']))."' WHERE `provider_id` =".validate_input($check_forgot[0])." LIMIT 1 ;");
								}
								
								$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/message.html");
								
								$page->SetParameter ('SUBJECT',$lang['FORGOTPASS']);
								$page->SetParameter ('MESSAGE',$lang['PASSCHANGED']);
	
								if(isset($_SESSION['user']['id']))
								{
									$page->SetParameter ('LOGGEDIN', 1);
								}
								else
								{
									$page->SetParameter ('LOGGEDIN', 0);
								}
								$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['LOGIN']));
								$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
								$page->CreatePageEcho($lang,$config);
								
								exit;
							}
							else
							{
								$forgot_error = $lang['PASSNOTMATCH'];
							}
						}
					}
				
					$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/forgot.html");
					$page->SetParameter ('FIELD_FORGOT',$_GET['forgot']);
					$page->SetParameter ('FIELD_R',$_GET['r']);
					$page->SetParameter ('FIELD_E',$_GET['e']);
					$page->SetParameter ('FIELD_T',$_GET['t']);
					$page->SetParameter ('FIELD_TYPE',$_GET['type']);
					$page->SetParameter ('USERNAME',$check_forgot[2]);
					$page->SetParameter ('FORGOT_ERROR',$forgot_error);
					if(isset($_SESSION['user']['id']))
					{
						$page->SetParameter ('LOGGEDIN', 1);
					}
					else
					{
						$page->SetParameter ('LOGGEDIN', 0);
					}
					$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['LOGIN']));
					$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
					$page->CreatePageEcho($lang,$config);
					exit;
				}
				else
				{
					$login_error = $lang['FORGOTEXP'];
				}
			}
			else
			{
				$login_error = $lang['FORGOTINV'];
			}
		}
		else
		{
			$login_error = $lang['FORGOTINV'];
		}
	}
	
	$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/login.html");
	$page->SetParameter ('ERROR',$login_error);
	if(isset($_SESSION['user']['id']))
	{
		$page->SetParameter ('LOGGEDIN', 1);
	}
	else
	{
		$page->SetParameter ('LOGGEDIN', 0);
	}
	$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['LOGIN']));
	$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
	$page->CreatePageEcho($lang,$config);
	exit;
}

// Check if they are trying to retrieve their email
if(isset($_POST['email']))
{
	if($_GET['type'] == 'buyer')
	{
		// Lookup the email address
		$email_info = mysql_fetch_row(mysql_query("SELECT buyer_id FROM ".$config['db']['pre']."buyers WHERE buyer_email='".validate_input($_POST['email'])."' LIMIT 1"));
		
		$_GET['type'] = 'buyer';
	}
	else
	{
		// Lookup the email address
		$email_info = mysql_fetch_row(mysql_query("SELECT provider_id FROM ".$config['db']['pre']."providers WHERE provider_email='".validate_input($_POST['email'])."' LIMIT 1"));
		
		$_GET['type'] = 'provider';
	}

	// Check if the email address exists
	if(isset($email_info[0]))
	{
		// Send the email
		send_forgot_email($_POST['email'],$_GET['type'],$email_info[0],$config,$lang);
		
		$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/forgot_form.html");
		$page->SetParameter ('LOGIN_ERROR',$lang['CHECKEMAILFORGOT']);
		if(isset($_SESSION['duser']['id']))
		{
			$page->SetParameter ('LOGGEDIN', 1);
		}
		else
		{
			$page->SetParameter ('LOGGEDIN', 0);
		}
		$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$cats,$lang['LOGIN']));
		$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
		$page->CreatePageEcho($lang,$config);
		exit;
	}
	else
	{
		$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/forgot_form.html");
		$page->SetParameter ('LOGIN_ERROR',$lang['EMAILNOTEXIST']);
		if(isset($_SESSION['user']['id']))
		{
			$page->SetParameter ('LOGGEDIN', 1);
		}
		else
		{
			$page->SetParameter ('LOGGEDIN', 0);
		}
		$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['LOGIN']));
		$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
		$page->CreatePageEcho($lang,$config);
		exit;
	}
}

if(isset($_GET['fstart']))
{
	$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/forgot_form.html");
	$page->SetParameter ('LOGIN_ERROR','');
	if(isset($_SESSION['user']['id']))
	{
		$page->SetParameter ('LOGGEDIN', 1);
	}
	else
	{
		$page->SetParameter ('LOGGEDIN', 0);
	}
	$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['LOGIN']));
	$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
	$page->CreatePageEcho($lang,$config);
	
	exit;
}

if(!isset($_POST['Submit']))
{
	if(!isset($_GET['ref']))
	{
		$_GET['ref'] = 'manage.php';
	}
	
	$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/login.html");
	$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['LOGIN']));
	$page->SetParameter ('REF', $_GET['ref']);
	$page->SetParameter ('ERROR', '');
	$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
	$page->CreatePageEcho($lang,$config);
}
else
{
	$loggedin = userlogin($config,$_POST['username'], $_POST['password'], $_POST['type']);
	
	if(!is_array($loggedin))
	{
		$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/login.html");
		$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['LOGIN']));
		$page->SetParameter ('ERROR', $lang['USERNOTFOUND']);
		$page->SetParameter ('REF', $_POST['ref']);
		$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
		$page->CreatePageEcho($lang,$config);
	}
	elseif($loggedin['status'] == 0)
	{
		$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/login.html");
		$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['LOGIN']));
		$page->SetParameter ('ERROR', $lang['ACCOUNTCONFIRM']);
		$page->SetParameter ('REF', $_POST['ref']);
		$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
		$page->CreatePageEcho($lang,$config);
	}
	elseif($loggedin['status'] == 2)
	{
		$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/login.html");
		$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['LOGIN']));
		$page->SetParameter ('ERROR', $lang['ACCOUNTBAN']);
		$page->SetParameter ('REF', $_POST['ref']);
		$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
		$page->CreatePageEcho($lang,$config);
	}
	else
	{
		$_SESSION['user']['name'] = $_POST['username'];
		$_SESSION['user']['comp'] = $loggedin['comp'];
		$_SESSION['user']['id'] = $loggedin['id'];
		$_SESSION['user']['email'] = $loggedin['email'];
		$_SESSION['user']['type'] = $loggedin['type'];
		$_SESSION['user']['lang'] = $loggedin['lang'];
		$_SESSION['user']['group'] = $loggedin['group'];
		
		update_lastactive($config);
		
		header("Location: " . $_POST['ref']);
	}
}
?>