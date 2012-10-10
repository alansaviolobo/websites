<?php
require_once('../includes/config.php');

function install_error($error)
{
	if(!isset($_GET['ignore_errors']))
	{
		exit($error.'<br><Br><a href="'.$_SERVER['PHP_SELF'].'?ignore_errors=1&install=1">Click here</a> to run the upgrade and ignore errors');
	}
}

$install_version = '1.7.3';

// Check to see if the script is already installed
if(isset($config['installed']))
{
	if($config['version'] == $install_version)
	{
		// Exit the script
		exit($config['site_title'].' is already installed.');
	}
}

if(!isset($_GET['install']))
{
	echo 'Before you run an upgrade it is recomended that you backup your '.$config['site_title'].' database<br><Br>Are you sure you want to upgrade your '.$config['site_title'].' installation from '.$config['version'].' to '.$install_version.'?<br><br><a href="upgrade_1.7.0.php?install=1">Yes do it</a>';
}
else
{
	ignore_user_abort(1);

	echo '<pre>';

	// Try to connect to the databse
	echo "Connecting to database.... \t";
    $db_connection = @mysql_connect ($config['db']['host'], $config['db']['user'], $config['db']['pass']) OR install_error('ERROR ('.mysql_error().')');
    $db_select = @mysql_select_db ($config['db']['name']) OR install_error('ERROR ('.mysql_error().')');
	echo "success<br>";
	
// Change the admin menu table
	echo "Updating The Admin Menu Table...  \t\t";
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES(119, 10, 40, 'Subscribers', '', '', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES(120, 20, 116, 'View Subscribers', '<img src=\"images/smicon_editrule.gif\">', 'subscriptions_view.php', '', '');");
	echo "success<br>";
	
	echo "Update Cheque Plugin...  \t\t";
	@mysql_query('UPDATE `'.addslashes($config['db']['pre']).'payments` SET `payment_settings` = \'a:10:{s:13:"withdraw_link";s:249:"echo "<a href=\\\\"#\\\\" onclick=\\\\"javascript:window.open(\'\'print_trans_info.php?id=$tran_id\'\',\'\'ZoomImage\'\',\'\'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,menubar=no,width=550,height=400\'\');\\\\">Click here to pay</a>";";s:12:"deposit_cost";s:0:"";s:18:"deposit_percentage";s:0:"";s:10:"Payable_To";s:0:"";s:9:"Address_1";s:0:"";s:9:"Address_2";s:0:"";s:4:"City";s:0:"";s:5:"State";s:0:"";s:13:"Post/Zip_Code";s:0:"";s:7:"Country";s:0:"";}\' WHERE `lance_payments`.`payment_id` = 3 LIMIT 1;');
	echo "success<br>";
	
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."payments` VALUES (5, '0', '0', '0', 'moneybookers', 'moneybookers', '3% + $0.30', 'No Fee', 'Make an instant deposit using online payment service <a href=\"http://www.moneybookers.com\" target=\"_new\">moneybookers.com</a>.', 'Make a withdrawal using online payment service <a href=\"http://www.moneybookers.com\" target=\"_new\">moneybookers.com</a>.', '',  'a:7:{s:20:\"moneybookers_address\";s:18:\"sales@site.com\";s:12:\"deposit_cost\";s:4:\"0.30\";s:18:\"deposit_percentage\";s:1:\"3\";s:13:\"withdraw_cost\";s:1:\"0\";s:19:\"withdraw_percentage\";s:1:\"0\";s:10:\"MD5_Secret\";s:0:\"\";s:13:\"withdraw_link\";s:347:\"echo \'<a target=\"_new\" href=\"https://www.moneybookers.com/app/payment.pl?pay_to_email=\' . urlencode(\$tran_settings[\'address\']) . \'&detail1_text=\' . \$config[\'site_title\'] . \'+Withdrawal&amount=\' . \$tran_amount . \'&currency=USD&notify_url=\' . \$config[\'site_url\'] . \'includes/payments/withdraw_ipn.php&custom=\' . \$tran_id . \'\">Click here to pay</a>\';\";}', '5', '5');");
	
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."payments` VALUES (6, '0', '0', '0', 'egold', 'egold', '3% + $0.30', 'No Fee', 'Make an instant deposit using online payment service <a href=\"http://www.egold.com\" target=\"_new\">egold.com</a>.', 'Make a withdrawal using online payment service <a href=\"http://www.egold.com\" target=\"_new\">egold.com</a>.', '',  'a:7:{s:13:\"egold_account\";s:6:\"000000\";s:12:\"deposit_cost\";s:4:\"0.30\";s:18:\"deposit_percentage\";s:1:\"3\";s:13:\"withdraw_cost\";s:1:\"0\";s:19:\"withdraw_percentage\";s:1:\"0\";s:10:\"MD5_Secret\";s:0:\"\";s:13:\"withdraw_link\";s:560:\"echo \'<a target=\"_new\" href=\"https://www.e-gold.com/sci_asp/payments.asp?PAYEE_ACCOUNT=\' . urlencode(\$tran_settings[\"account\"]) . \'&PAYMENT_AMOUNT=\' . \$tran_amount . \'&PAYMENT_UNITS=1&STATUS_URL=\' . \$config[\"site_url\"] . \'includes/payments/withdraw_ipn.php&BAGGAGE_FIELDS=Description+transaction_id&Description=\' . \$config[\"site_title\"] . \'+Withdrawal&transaction_id=\' . \$tran_id . \'&PAYEE_NAME=\'.\$config[\'site_url\'].\'&PAYMENT_METAL_ID=1&PAYMENT_URL=\'.\$config[\'site_url\'] . \'manage.php&NOPAYMENT_URL=\'.\$config[\'site_url\'] . \'manage.php\">Click here to pay</a>\';\";}', '5', '5');");
	// Check that config file is writtable
	echo "Checking config file.. \t\t";
	if(@is_writable('../includes/config.php'))
	{
		echo "success<br>";
	}
	else
	{
		echo 'ERROR (config.php permisions not set correctly)';
		exit;
	}
		
	// Start updating the config file with new variables
	echo "Writting config.php updates.. \t";
	$content = "<?php\n";
	$content.= "\$config['db']['host'] = '".addslashes(stripslashes($config['db']['host']))."';\n";
	$content.= "\$config['db']['name'] = '".addslashes(stripslashes($config['db']['name']))."';\n";
	$content.= "\$config['db']['user'] = '".addslashes(stripslashes($config['db']['user']))."';\n";
	$content.= "\$config['db']['pass'] = '".addslashes(stripslashes($config['db']['pass']))."';\n";
	$content.= "\$config['db']['pre'] = '".addslashes(stripslashes($config['db']['pre']))."';\n";
	$content.= "\n";
	$content.= "\$config['site_title'] = '".addslashes(stripslashes($config['site_title']))."';\n";
	$content.= "\$config['site_url'] = '".addslashes(stripslashes($config['site_url']))."';\n";
	$content.= "\$config['admin_email'] = '".addslashes(stripslashes($config['admin_email']))."';\n";
	$content.= "\$config['cron_time'] = '".addslashes(stripslashes($config['cron_time']))."';\n";
	$content.= "\$config['pay_type'] = '".addslashes(stripslashes($config['pay_type']))."';\n";
	$content.= "\$config['security'] = '".addslashes(stripslashes($config['security']))."';\n";
	$content.= "\$config['mailbox_en'] = '".addslashes(stripslashes($config['mailbox_en']))."';\n";
	$content.= "\n";
	$content.= "\$config['currency_sign'] = '".addslashes(stripslashes($config['currency_sign']))."';\n";
	$content.= "\$config['currency_code'] = '".addslashes(stripslashes($config['currency_code']))."';\n";
	$content.= "\$config['transfer_en'] = '".addslashes(stripslashes($config['transfer_en']))."';\n";
	$content.= "\$config['transfer_min'] = '".addslashes(stripslashes($config['transfer_min']))."';\n";
	$content.= "\$config['escrow_en'] = '".addslashes(stripslashes($config['escrow_en']))."';\n";
	$content.= "\n";
	$content.= "\$config['start_amount_provider'] = '".addslashes(stripslashes($config['start_amount_provider']))."';\n";
	$content.= "\$config['start_amount_buyer'] = '".addslashes(stripslashes($config['start_amount_buyer']))."';\n";
	$content.= "\$config['post_project_amount'] = '".addslashes(stripslashes($config['post_project_amount']))."';\n";
	$content.= "\$config['post_featured_amount'] = '".addslashes(stripslashes($config['post_featured_amount']))."';\n";
	$content.= "\$config['post_job_amount'] = '".addslashes(stripslashes($config['post_job_amount']))."';\n";
	$content.= "\$config['provider_com'] = '".addslashes(stripslashes($config['provider_com']))."';\n";
	$content.= "\$config['buyer_com'] = '".addslashes(stripslashes($config['buyer_com']))."';\n";
	$content.= "\$config['provider_fee'] = '".addslashes(stripslashes($config['provider_fee']))."';\n";
	$content.= "\$config['buyer_fee'] = '".addslashes(stripslashes($config['buyer_fee']))."';\n";
	$content.= "\$config['bid_fee'] = '".addslashes(stripslashes($config['bid_fee']))."';\n";
	$content.= "\$config['enable_quotes'] = '".addslashes(stripslashes($config['enable_quotes']))."';\n";
	$content.= "\$config['multiple_accounts'] = '".addslashes(stripslashes($config['multiple_accounts']))."';\n";
	$content.= "\$config['latest_project_limit'] = '".addslashes(stripslashes($config['latest_project_limit']))."';\n";
	$content.= "\$config['featured_project_limit'] = '".addslashes(stripslashes($config['featured_project_limit']))."';\n";
	$content.= "\$config['job_listings_limit'] = '".addslashes(stripslashes($config['job_listings_limit']))."';\n";
	$content.= "\$config['mod_rewrite'] = '".addslashes(stripslashes($config['mod_rewrite']))."';\n";
	$content.= "\$config['rows_per_page'] = '".addslashes(stripslashes($config['rows_per_page']))."';\n";
	$content.= "\$config['transfer_filter'] = '".addslashes(stripslashes($config['transfer_filter']))."';\n";
	$content.= "\$config['provider_public_post'] = '".addslashes(stripslashes($config['provider_public_post']))."';\n";
	$content.= "\$config['email_validation'] = '".addslashes(stripslashes($config['email_validation']))."';\n";
	$content.= "\$config['userlangsel'] = '".addslashes(stripslashes($config['userlangsel']))."';\n";
	$content.= "\n";
	$content.= "\$config['email']['type'] = '".addslashes(stripslashes($config['email']['type']))."';\n";
	$content.= "\$config['email']['smtp']['host'] = '".addslashes(stripslashes($config['email']['smtp']['host']))."';\n";
	$content.= "\$config['email']['smtp']['user'] = '".addslashes(stripslashes($config['email']['smtp']['user']))."';\n";
	$content.= "\$config['email']['smtp']['pass'] = '".addslashes(stripslashes($config['email']['smtp']['pass']))."';\n";
	$content.= "\n";
	$content.= "\$config['xml']['latest'] = '".$config['xml']['latest']."';\n";
	$content.= "\$config['xml']['featured'] = '".$config['xml']['featured']."';\n";
	$content.= "\n";
	$content.= "\$config['images']['max_size'] = '".addslashes(stripslashes($config['images']['max_size']))."';\n";
	$content.= "\$config['images']['max_height'] = '".addslashes(stripslashes($config['images']['max_height']))."';\n";
	$content.= "\$config['images']['max_width'] = '".addslashes(stripslashes($config['images']['max_width']))."';\n";
	$content.= "\n";
	$content.= "\$config['cookie_time'] = '".addslashes(stripslashes($config['cookie_time']))."';\n";
	$content.= "\$config['cookie_name'] = '".addslashes(stripslashes($config['cookie_name']))."';\n";
	$content.= "\n";
	$content.= "\$config['tpl_name'] = '".addslashes($config['tpl_name'])."';\n";
	$content.= "\$config['version'] = '1.7.3';\n";
	$content.= "\$config['lang'] = '".addslashes(stripslashes($config['lang']))."';\n";
	$content.= "\$config['temp_php'] = '".addslashes(stripslashes($config['temp_php']))."';\n";
	$content.= "\$config['installed'] = '1';\n";
	$content.= "?>";
	
	// Open the includes/config.php for writting
	$handle = fopen('../includes/config.php', 'w');
	// Write the config file
	fwrite($handle, $content);
	// Close the file
	fclose($handle);
	echo "success<br>";
	
	echo "<br><Br><Br>Thank You! for upgrading {$config['site_title']}, Please <a href=\"../index.php\">click here</a> to access your site";

	echo '</pre>';
}
?>