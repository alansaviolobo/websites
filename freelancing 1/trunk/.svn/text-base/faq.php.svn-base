<?php
require_once('includes/config.php');
require_once('includes/classes/class.template_engine.php');
require_once('includes/functions/func.global.php');
$config['lang'] = check_user_lang($config);
require_once('includes/lang/lang_'.$config['lang'].'.php');


db_connect($config);

session_start();
check_cookie($_SESSION,$config);

$count = 0;
$faq = array();

$query2 = "SELECT faq_id,faq_title,faq_content FROM `".$config['db']['pre']."faq_entries` ORDER BY faq_id";
$query_result2 = @mysql_query ($query2) OR error(mysql_error());
while ($info2 = @mysql_fetch_array($query_result2))
{
	$count++;

	$faq[$count]['id'] = $info2['faq_id'];
	$faq[$count]['title'] = stripslashes($info2['faq_title']);
	$faq[$count]['content'] = stripslashes($info2['faq_content']);
}

$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/faq.html");
$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['FAQ']));
$page->SetLoop ('FAQ', $faq);
$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
$page->CreatePageEcho($lang,$config);
?>