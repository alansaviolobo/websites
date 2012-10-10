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

if(!isset($_POST['Submit']))
{
	$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/contact.html");
	$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['CONTACT']));
	if (isset($_SESSION['user']['name']))
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
	$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
	$page->CreatePageEcho($lang,$config);
}
else
{
	$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/email_contact.html");
	$page->SetParameter ('SITE_TITLE', $config['site_title']);
	$page->SetParameter ('EMAIL', $_POST['email']);
	$page->SetParameter ('TYPE', $_POST['type']);
	$page->SetParameter ('SUBJECT', $_POST['subject']);
	$page->SetParameter ('MESSAGE', $_POST['message']);
	$page->SetParameter ('NAME', $_POST['name']);
	$page->SetParameter ('USERNAME', $_POST['username']);
	$email_body = $page->CreatePageReturn($lang,$config);
		
	email($_POST['email'],$lang['CONTACT_SUBJECT_START'] . $_POST['subject'],$email_body,$config);
	email($config['admin_email'],$lang['CONTACT_SUBJECT_START'] . $_POST['subject'],$email_body,$config);
	
	message($lang['CONTACTTHANKS'],$config,$lang);
}
?>