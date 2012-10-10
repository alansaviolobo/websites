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

if(!isset($_GET['id']))
{
	$_GET['id'] = '';
}

if(checkloggedin())
{
	if($_SESSION['user']['type'] == 'provider')
	{
		$errors = 0;
		$paypal_error = '';
	
		if(isset($_POST['paypal']))
		{
			$id = '';
			$errors = 0;
			
			if(isset($_POST['id']))
			{
				if($_POST['id'])
				{
					$id = $_POST['id'];
				}
			}
			
			if($_POST['paypal'] == '')
			{
				$errors++;
				$paypal_error = $lang['INVALIDPAYPAL'];
			}
			elseif(!eregi("^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$", $_POST['paypal'])) 
			{
				$errors++;
				$paypal_error = $lang['INVALIDPAYPAL'];
			}
			else
			{
				$paypal_ban_check = mysql_num_rows(mysql_query("SELECT 1 FROM ".$config['db']['pre']."bans WHERE ban_value='" . validate_input($_POST['paypal']) . "' AND ban_type='PAYPAL' LIMIT 1"));
				
				if($paypal_ban_check)
				{
					$errors++;
					$paypal_error = $lang['PAYPALBAN'];
				}
			}
			
			if($errors == 0)
			{
				mysql_query("UPDATE `".$config['db']['pre']."providers` SET `provider_paypal` = '" . validate_input($_POST['paypal']) . "' WHERE `provider_id` = '" . $_SESSION['user']['id'] . "' LIMIT 1 ;");
				
				if($id)
				{
					header("Location: bid.php?id=".$id);
				}
				else
				{
					header("Location: index.php");
				}
				
				exit;
			}
		}
	
		$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/paypal_add.html");
		$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['ADDPAYPAL']));
		$page->SetParameter ('PROJECT_ID', $_GET['id']);
		$page->SetParameter ('PAYPAL_ERROR', $paypal_error);
		$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
		$page->CreatePageEcho($lang,$config);
	}
	else
	{
		header("Location: login.php");
		exit;
	}
}
else
{
	header("Location: login.php");
	exit;
}
?>