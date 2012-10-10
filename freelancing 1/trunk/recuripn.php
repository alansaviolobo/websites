<?php
require_once('includes/config.php');
require_once('includes/classes/class.template_engine.php');
require_once('includes/functions/func.global.php');
$config['lang'] = check_user_lang($config);
require_once('includes/lang/lang_'.$config['lang'].'.php');
require_once('includes/functions/func.users.php');

db_connect($config);

if(!isset($_GET['i']))
{
	exit('Error: Invalid Payment Processor');
}

$_GET['i'] = str_replace('.','',$_GET['i']);
$_GET['i'] = str_replace('/','',$_GET['i']);
$_GET['i'] = strip_tags($_GET['i']);

if(ereg('[^A-Za-z0-9_]',$_GET['i']))
{
	exit('Error: Invalid Payment Processor');
}

if(trim($_GET['i']) == '')
{
	exit('Error: Invalid Payment Processor');
}

$payment_settings = mysql_fetch_array(mysql_query("SELECT payment_settings,payment_folder FROM ".$config['db']['pre']."payments WHERE payment_folder='".validate_input($_GET['i'])."' LIMIT 1"));
$settings_array = unserialize($payment_settings['payment_settings']);

if(!isset($payment_settings['payment_folder']))
{
	exit('Payment Processor not found');
}

require_once('includes/payments/'.$payment_settings['payment_folder'].'/recuripn.php');
?>