<?php
require_once('includes/config.php');
require_once('includes/classes/class.template_engine.php');
require_once('includes/functions/func.global.php');
session_start();
$config['lang'] = check_user_lang($config);
require_once('includes/lang/lang_'.$config['lang'].'.php');

db_connect($config);

check_cookie($_SESSION,$config);

$quotes = array();

$query = "SELECT quote_author,quote_comment,quote_id FROM `".$config['db']['pre']."quotes`";
$query_result = @mysql_query ($query) OR error(mysql_error());
while ($info = @mysql_fetch_array($query_result))
{
	$quotes[$info['quote_id']]['quote'] = stripslashes($info['quote_comment']);
	$quotes[$info['quote_id']]['author'] = stripslashes($info['quote_author']);
}

$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/quotes.html");
$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['QUOTES']));
$page->SetLoop ('QUOTES', $quotes);
$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
$page->CreatePageEcho($lang,$config);
?>