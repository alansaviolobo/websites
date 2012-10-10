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
				
if(checkloggedin())
{
	$project_info = mysql_fetch_row(mysql_query("SELECT project_id,provider_id FROM `".$config['db']['pre']."projects` WHERE project_id='".validate_input($_GET['id'])."' AND buyer_id='" . $_SESSION['user']['id'] . "' LIMIT 1"));

	if($project_info[0])
	{
		$bid = mysql_fetch_row(mysql_query("SELECT bid_amount FROM `".$config['db']['pre']."bids` WHERE project_id='".validate_input($_GET['id'])."' AND user_id='".$project_info[1]."' LIMIT 1"));

		$provider = mysql_fetch_row(mysql_query("SELECT provider_paypal FROM `".$config['db']['pre']."providers` WHERE provider_id='".$project_info[1]."' LIMIT 1"));

		if($provider[0] == '')
		{
			message($lang['SORNOPAYPAL'],$config,$lang,'',true);
		}

		$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/project_pay.html");
		$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['PAYPROJECT']));
		$page->SetParameter ('AMOUNT', $bid[0]);
		$page->SetParameter ('PROJECT_ID', $_GET['id']);
		$page->SetParameter ('PAYPAL_EMAIL', $provider[0]);
		$page->SetParameter ('SITE_TITLE', $config['site_title']);
		$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
		$page->CreatePageEcho($lang,$config);
	}
}
?>