<?php
require_once('includes/config.php');
require_once('includes/classes/class.template_engine.php');
require_once('includes/functions/func.global.php');
session_start();
$config['lang'] = check_user_lang($config);
require_once('includes/lang/lang_'.$config['lang'].'.php');

db_connect($config);

if(isset($_SESSION['user']['id']))
{
	if($_SESSION['user']['type'] == 'buyer')
	{
		mysql_query("UPDATE `".$config['db']['pre']."buyers` SET `buyer_online` = '0' WHERE `buyer_id` = '".validate_input($_SESSION['user']['id'])."' LIMIT 1 ;");
	}
	else
	{
		mysql_query("UPDATE `".$config['db']['pre']."providers` SET `provider_online` = '0' WHERE `provider_id` = '".validate_input($_SESSION['user']['id'])."' LIMIT 1 ;");
	}
}

session_unset('user');
session_destroy();

setcookie($config['cookie_name'], '', time()-3600);

header("Location: login.php");
?>