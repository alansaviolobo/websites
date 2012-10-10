<?php
require_once('includes/config.php');
require_once("includes/classes/class.phpmailer.php");
require_once('includes/classes/class.template_engine.php');
require_once('includes/functions/func.global.php');
session_start();
$config['lang'] = check_user_lang($config);
require_once('includes/lang/lang_'.$config['lang'].'.php');

db_connect($config);

check_cookie($_SESSION,$config);

$errors = 0;
$name_error = '';
$email_error = '';
$viol_error = '';

if(!isset($_POST['Submit']))
{
	$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/report.html");
	$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['REPORTVIO']));
	$page->SetParameter ('USERNAME2', '');
	$page->SetParameter ('DETAILS', '');
	$page->SetParameter ('EMAIL_ERROR', '');
	$page->SetParameter ('NAME_ERROR', '');
	$page->SetParameter ('VIOL_ERROR', '');
	
	if(isset($_SESSION['user']['name']))
	{
		$page->SetParameter ('USERNAME', $_SESSION['user']['name']);
		$page->SetParameter ('NAME', $_SESSION['user']['comp']);
		$page->SetParameter ('TYPE', $_SESSION['user']['type']);	
		$page->SetParameter ('EMAIL', $_SESSION['user']['email']);	
	}
	else
	{
		$page->SetParameter ('USERNAME', '');
		$page->SetParameter ('NAME', '');
		$page->SetParameter ('TYPE', '');	
		$page->SetParameter ('EMAIL', '');	
	}
	
	if ( (eregi('board.php', $_SERVER['HTTP_REFERER'])) OR (eregi('project.php', $_SERVER['HTTP_REFERER'])) )
	{
		$page->SetParameter ('REDIRECT_URL', $_SERVER['HTTP_REFERER']);
	}
	else
	{
		$page->SetParameter ('REDIRECT_URL', '');
	}
	
	$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
	$page->CreatePageEcho($lang,$config);
}
else
{
	if(trim($_POST['email']) == '')
	{
		$errors++;
		$email_error = $lang['ENTEREMAIL'];
	}
	elseif(!eregi("^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$", $_POST['email'])) 
	{
		$errors++;
		$email_error = $lang['EMAILINV'];
	}
	
	if(trim($_POST['name']) == '')
	{
		$errors++;
		$name_error = $lang['ENTERNAME'];
	}
	
	if(trim($_POST['details']) == '')
	{
		$errors++;
		$viol_error = $lang['ENTERVIOL'];
	}
	
	if($errors)
	{
		$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/report.html");
		$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['REPORTVIO']));

		$page->SetParameter ('USERNAME', $_POST['username']);
		$page->SetParameter ('USERNAME2', $_POST['username2']);
		$page->SetParameter ('NAME', $_POST['name']);
		$page->SetParameter ('TYPE', $_POST['type']);	
		$page->SetParameter ('EMAIL', $_POST['email']);
		$page->SetParameter ('DETAILS', $_POST['details']);
		
		$page->SetParameter ('EMAIL_ERROR', $email_error);
		$page->SetParameter ('NAME_ERROR', $name_error);
		$page->SetParameter ('VIOL_ERROR', $viol_error);
		
		if ( (eregi('board.php', $_SERVER['HTTP_REFERER'])) OR (eregi('project.php', $_SERVER['HTTP_REFERER'])) )
		{
			$page->SetParameter ('REDIRECT_URL', $_SERVER['HTTP_REFERER']);
		}
		else
		{
			$page->SetParameter ('REDIRECT_URL', '');
		}
		
		$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
		$page->CreatePageEcho($lang,$config);
	}
	else
	{
		$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/email_report.html");
		$page->SetParameter ('SITE_TITLE', $config['site_title']);
		$page->SetParameter ('EMAIL', $_POST['email']);
		$page->SetParameter ('NAME', $_POST['name']);
		$page->SetParameter ('USERNAME', $_POST['username']);
		$page->SetParameter ('USERNAME2', $_POST['username2']);
		$page->SetParameter ('VIOLATION', $_POST['violation']);
		$page->SetParameter ('URL', $_POST['url']);
		$page->SetParameter ('DETAILS', $_POST['details']);
		$email_body = $page->CreatePageReturn($lang,$config);
			
		email($config['admin_email'],$lang['REPORTVIO'],$email_body,$config);
	
		message($lang['REPORT_THANKS'],$config,$lang);
	}
}
?>