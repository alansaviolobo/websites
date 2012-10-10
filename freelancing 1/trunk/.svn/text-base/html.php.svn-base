<?php
require_once('includes/config.php');
require_once('includes/classes/class.template_engine.php');
require_once('includes/functions/func.global.php');
session_start();
$config['lang'] = check_user_lang($config);
require_once('includes/lang/lang_'.$config['lang'].'.php');

db_connect($config);

check_cookie($_SESSION,$config);

$query = "SELECT html_title,html_content,html_type FROM `".$config['db']['pre']."html` WHERE html_id='" . validate_input($_GET['id']) . "' LIMIT 1";
$query_result = @mysql_query ($query) OR error(mysql_error());
while ($info = @mysql_fetch_array($query_result))
{
	$html = stripslashes($info['html_content']);
	$title = stripslashes($info['html_title']);
	$type = $info['html_type'];
}

if(!isset($title))
{
	message($lang['PAGENOTEXIST'], $config,$lang);
}

if($type == 1)
{
	if(!isset($_SESSION['user']['id']))
	{
		message($lang['MUSTLOGINVIEWPAGE'], $config,$lang);
	}
}

if(isset($_GET['basic']))
{
	$page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/html_content_no.html');
}
else
{
	$page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/html_content.html');
}
$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$title));
$page->SetParameter ('SITE_TITLE', $config['site_title']);
$page->SetParameter ('TITLE', $title);
$page->SetParameter ('HTML', $html);
$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
$page->CreatePageEcho($lang,$config);
?>