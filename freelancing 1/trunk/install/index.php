<?php
require_once('../includes/config.php');

$install_version = '1.7.3';

if(isset($_GET['lang']))
{
	$_POST['lang'] = $_GET['lang'];
}

if(isset($_POST['lang']))
{
	require_once('lang/lang_'.$_POST['lang'].'.php');
}

// Check to see if the script is already installed
if(isset($config['installed']))
{
	if($config['version'] == $install_version)
	{
		// Exit the script
		exit($config['site_title'].' is already installed.');
	}
	else
	{
		header('Location: upgrade_'.$config['version'].'.php');
		exit;
	}
}

$error = '';

// Check that their config file is writtable
if(is_writable('../includes/config.php'))
{
	if(!isset($_POST['lang']))
	{
		$step = 2;
	}
	else
	{
		if(!isset($_POST['DBHost']))
		{
			$step = 3;
		}
		else
		{
			// Test the connection
			if(mysql_connect($_POST['DBHost'], $_POST['DBUser'], $_POST['DBPass']))
			{
				if(mysql_select_db($_POST['DBName']))
				{
					if(isset($_POST['adminuser']))
					{
						if(trim($_POST['adminuser']) == '')
						{
							$step = 4;
						}
						else
						{
							$site_path = str_replace('\\','/',ereg_replace('install', '', dirname(__FILE__)));
							$site_url = "http://" . $_SERVER['HTTP_HOST'] . ereg_replace ("index.php", "", ereg_replace ("install/", "", $_SERVER['PHP_SELF']));
									
							// Content that will be written to the config file
							$content = "<?php\n";
							$content.= "\$config['db']['host'] = '".addslashes($_POST['DBHost'])."';\n";
							$content.= "\$config['db']['name'] = '".addslashes($_POST['DBName'])."';\n";
							$content.= "\$config['db']['user'] = '".addslashes($_POST['DBUser'])."';\n";
							$content.= "\$config['db']['pass'] = '".addslashes($_POST['DBPass'])."';\n";
							$content.= "\$config['db']['pre'] = '".addslashes($_POST['DBPre'])."';\n";
							$content.= "\n";
							$content.= "\$config['site_title'] = 'Freelancers';\n";
							$content.= "\$config['site_url'] = '".addslashes($site_url)."';\n";
							$content.= "\$config['admin_email'] = '';\n";
							$content.= "\$config['cron_time'] = '0';\n";
							$content.= "\$config['pay_type'] = '".$config['pay_type']."';\n";
							$content.= "\$config['security'] = '".$config['security']."';\n";
							$content.= "\$config['mailbox_en'] = '".$config['mailbox_en']."';\n";
							$content.= "\n";
							$content.= "\$config['currency_sign'] = '\$';\n";
							$content.= "\$config['currency_code'] = 'USD';\n";
							$content.= "\$config['transfer_en'] = '1';\n";
							$content.= "\$config['transfer_min'] = '10';\n";
							$content.= "\$config['escrow_en'] = '0';\n";
							$content.= "\n";
							$content.= "\$config['start_amount_provider'] = '5';\n";
							$content.= "\$config['start_amount_buyer'] = '5';\n";
							$content.= "\$config['post_project_amount'] = '0';\n";
							$content.= "\$config['post_featured_amount'] = '30';\n";
							$content.= "\$config['post_job_amount'] = '40';\n";
							$content.= "\$config['provider_com'] = '".addslashes(stripslashes($config['provider_com']))."';\n";
							$content.= "\$config['buyer_com'] = '".addslashes(stripslashes($config['buyer_com']))."';\n";
							$content.= "\$config['provider_fee'] = '".addslashes(stripslashes($config['provider_fee']))."';\n";
							$content.= "\$config['buyer_fee'] = '".addslashes(stripslashes($config['buyer_fee']))."';\n";
							$content.= "\$config['bid_fee'] = '0';\n";
							$content.= "\$config['enable_quotes'] = '1';\n";
							$content.= "\$config['multiple_accounts'] = '1';\n";
							$content.= "\$config['latest_project_limit'] = '10';\n";
							$content.= "\$config['featured_project_limit'] = '10';\n";
							$content.= "\$config['job_listings_limit'] = '10';\n";
							$content.= "\$config['mod_rewrite'] = '0';\n";
							$content.= "\$config['rows_per_page'] = '15';\n";
							$content.= "\$config['transfer_filter'] = '1';\n";
							$content.= "\$config['provider_public_post'] = '0';\n";
							$content.= "\$config['email_validation'] = '1';\n";
							$content.= "\$config['userlangsel'] = '0';\n";
							$content.= "\n";
							$content.= "\$config['email']['type'] = '".addslashes(stripslashes($config['email']['type']))."';\n";
							$content.= "\$config['email']['smtp']['host'] = '".addslashes(stripslashes($config['email']['smtp']['host']))."';\n";
							$content.= "\$config['email']['smtp']['user'] = '".addslashes(stripslashes($config['email']['smtp']['user']))."';\n";
							$content.= "\$config['email']['smtp']['pass'] = '".addslashes(stripslashes($config['email']['smtp']['pass']))."';\n";
							$content.= "\n";
							$content.= "\$config['xml']['latest'] = '".$config['xml']['latest']."';\n";
							$content.= "\$config['xml']['featured'] = '".$config['xml']['featured']."';\n";
							$content.= "\n";
							$content.= "\$config['images']['max_size'] = '30000';\n";
							$content.= "\$config['images']['max_height'] = '500';\n";
							$content.= "\$config['images']['max_width'] = '200';\n";
							$content.= "\n";
							$content.= "\$config['cookie_time'] = '2592000';\n";
							$content.= "\$config['cookie_name'] = 'KubelabsC';\n";
							$content.= "\n";
							$content.= "\$config['tpl_name'] = '".$config['tpl_name']."';\n";
							$content.= "\$config['version'] = '".$config['version']."';\n";
							$content.= "\$config['lang'] = '".addslashes($_POST['lang'])."';\n";
							$content.= "\$config['temp_php'] = '".$config['temp_php']."';\n";
							$content.= "\$config['installed'] = '1';\n";
							$content.= "?>";
						
							// Open the includes/config.php for writting
							$handle = fopen('../includes/config.php', 'w');
							// Write the config file
							fwrite($handle, $content);
							// Close the file
							fclose($handle);
						
							// Create admin menu table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."amenu` (  `menu_id` smallint(3) unsigned NOT NULL auto_increment,  `sort_id` smallint(3) unsigned NOT NULL default '0',  `parent_id` smallint(3) unsigned NOT NULL default '0',  `menu_title` varchar(40) NOT NULL default '',  `menu_icon` varchar(100) NOT NULL default '',  `menu_url` varchar(255) NOT NULL default '',  `menu_target` varchar(10) NOT NULL default '',  `menu_desc` varchar(100) NOT NULL default '',  PRIMARY KEY  (`menu_id`))");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (1, 10, 0, 'Configuration', '', '', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (8, 20, 1, 'Rules', '', '', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (5, 90, 0, 'Content', '', '', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (70, 30, 1, 'Site Details', '<img src=\"images/smicon_config.gif\">', 'configuration.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (6, 20, 91, 'Add Page', '<img src=\"images/smicon_addrule.gif\">', 'content_add.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (7, 30, 91, 'Edit Page', '<img src=\"images/smicon_editrule.gif\">', 'content_edit.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (9, 0, 8, 'Add Rule', '<img src=\"images/smicon_addrule.gif\">', 'rules_add.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (10, 0, 8, 'Edit Rule', '<img src=\"images/smicon_editrule.gif\">', 'rules_edit.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (11, 9, 5, 'FAQ', '', '', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (14, 0, 11, 'Add Entry', '<img src=\"images/smicon_addrule.gif\">', 'faq_entry_add.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (15, 0, 11, 'Edit Entry', '<img src=\"images/smicon_editrule.gif\">', 'faq_entry_edit.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (19, 20, 102, 'Edit Provider(s)', '<img src=\"images/smicon_editfreelance.gif\">', 'provider_edit.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (21, 20, 0, 'Projects', '', '', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (22, 2, 21, 'Search', '<img src=\"images/smicon_search.gif\">', 'project_search.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (23, 5, 5, 'Quotes', '', '', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (24, 0, 23, 'Add Quote', '<img src=\"images/smicon_addquote.gif\">', 'quote_add.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (25, 0, 23, 'Edit Quote', '<img src=\"images/smicon_editquote.gif\">', 'quotes_edit.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (27, 40, 1, 'Template Settings', '<img src=\"images/smicon_template.gif\">', 'template_settings.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (29, 5, 40, 'Transactions', '', '', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (30, 2, 29, 'Search', '<img src=\"images/smicon_search.gif\">', 'transaction_search.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (31, 3, 29, 'View All', '<img src=\"images/smicon_viewall.gif\">', 'transactions_view.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (34, 10, 101, 'Edit Buyer(s)', '<img src=\"images/smicon_editfreelance.gif\">', 'buyer_edit.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (39, 3, 21, 'View All', '<img src=\"images/smicon_viewall.gif\">', 'projects_view.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (36, 190, 0, 'XML', '', '', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (37, 0, 36, 'Manage', '<img src=\"images/smicon_manage.gif\">', 'xml_manage.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (38, 0, 36, 'View Links', '<img src=\"images/smicon_viewlinks.gif\">', 'xml_links.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (40, 50, 0, 'Payments', '', '', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (41, 20, 40, 'Settings', '<img src=\"images/smicon_manage.gif\">', 'payment_settings.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (43, 30, 40, 'Withdrawal Queue', '', 'payment_queue.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (44, 30, 0, 'Jobs', '', '', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (45, 3, 44, 'Search', '<img src=\"images/smicon_search.gif\">', 'jobs_search.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (46, 4, 44, 'View All', '<img src=\"images/smicon_viewall.gif\">', 'jobs_view.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (47, 10, 1, 'Attachments', '<img src=\"images/smicon_config.gif\">', 'attachments.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (71, 0, 47, 'Add Type', '<img src=\"images/smicon_addrule.gif\">', 'attachments_add.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (72, 0, 47, 'Edit Type(s)', '<img src=\"images/smicon_editrule.gif\">', 'attachments.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (73, 0, 21, 'Categories', '', '', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (74, 0, 73, 'Add Category', '<img src=\"images/smicon_addrule.gif\">', 'projcat_add.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (75, 0, 73, 'Edit Category', '<img src=\"images/smicon_editrule.gif\">', 'projcat_edit.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (76, 0, 44, 'Categories', '', '', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (77, 0, 76, 'Add Category', '<img src=\"images/smicon_addrule.gif\">', 'jobcat_add.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (78, 0, 76, 'Edit Category', '<img src=\"images/smicon_editrule.gif\">', 'jobcat_edit.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (79, 2, 44, 'Types', '', '', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (80, 0, 79, 'Add Type', '<img src=\"images/smicon_addrule.gif\">', 'jobtype_add.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (81, 0, 79, 'Edit Type', '<img src=\"images/smicon_editrule.gif\">', 'jobtype_edit.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (82, 1, 29, 'Add Transaction', '<img src=\"images/smicon_addrule.gif\">', 'transaction_add.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (83, 200, 0, 'Admins', '', '', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (84, 0, 83, 'Add Admin', '<img src=\"images/smicon_addrule.gif\">', 'admin_add.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (85, 0, 83, 'Edit Admin', '<img src=\"images/smicon_editrule.gif\">', 'admin_view.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (86, 300, 0, 'Logout', '', 'logout.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (90, 40, 0, 'Users', '', '', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (91, 50, 5, 'Pages', '', '', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (92, 0, 90, 'Bans', '', '', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (93, 10, 92, 'Add Ban', '<img src=\"images/smicon_addrule.gif\">', 'ban_add.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (94, 20, 92, 'Edit Ban', '<img src=\"images/smicon_editrule.gif\">', 'ban_view.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (96, 35, 1, 'Transaction Settings', '<img src=\"images/smicon_template.gif\">', 'transaction_settings.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (98, 15, 102, 'Search Provider(s)', '<img src=\"images/smicon_search.gif\">', 'search_users.php?type=provider', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (97, 5, 101, 'Search Buyer(s)', '<img src=\"images/smicon_search.gif\">', 'search_users.php?type=buyer', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (99, 7, 101, 'Add Buyer', '<img src=\"images/smicon_addrule.gif\">', 'buyer_add.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (100, 17, 102, 'Add Provider', '<img src=\"images/smicon_addrule.gif\">', 'provider_add.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (101, 10, 90, 'Buyers', '', '', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (102, 20, 90, 'Providers', '', '', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (103, 1, 21, 'Bids', '', '', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (104, 10, 103, 'Search', '<img src=\"images/smicon_search.gif\">', 'bids_search.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (105, 20, 103, 'View All', '<img src=\"images/smicon_viewall.gif\">', 'bids_view.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (106, 40, 1, 'Log Viewer', '<img src=\"images/smicon_edittemplate.gif\">', 'log_viewer.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (107, 25, 1, 'Custom Fields', '', '', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (108, 10, 107, 'Add Field', '<img src=\"images/smicon_addrule.gif\">', 'custom_add.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (109, 20, 107, 'Edit Field', '<img src=\"images/smicon_editrule.gif\">', 'custom_view.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (110, 8, 40, 'Escrow', '', '', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (111, 3, 110, 'View All', '<img src=\"images/smicon_viewall.gif\">', 'escrow_view.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (112, 32, 1, 'Settings Manager', '<img src=\"images/smicon_config.gif\">', 'settings.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (113, 30, 90, 'Usergroups', '', '', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (114, 10, 113, 'Add Usergroup', '<img src=\"images/smicon_addrule.gif\">', 'usergroup_add.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (115, 20, 113, 'Edit Usergroup', '<img src=\"images/smicon_editrule.gif\">', 'usergroup_edit.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (116, 9, 40, 'Upgrades', '', '', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (117, 10, 116, 'Add Upgrade', '<img src=\"images/smicon_addrule.gif\">', 'upgrades_add.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (118, 20, 116, 'Edit Upgrade', '<img src=\"images/smicon_editrule.gif\">', 'upgrades_edit.php', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (119, 10, 40, 'Subscribers', '', '', '', '');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (120, 20, 116, 'View Subscribers', '<img src=\"images/smicon_editrule.gif\">', 'subscriptions_view.php', '', '');");
						// Create Admins Table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."admins` (  `admin_id` int(11) unsigned NOT NULL auto_increment,  `username` varchar(40) NOT NULL default '',  `password` varchar(40) NOT NULL default '',  PRIMARY KEY  (`admin_id`))");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."admins` VALUES (1, '".addslashes($_POST['adminuser'])."', '".addslashes(md5($_POST['adminpass']))."');");
							// Create attachments Table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."attachments` (  `file_id` mediumint(8) unsigned NOT NULL auto_increment,  `file_name` varchar(255) NOT NULL default '',  `file_content` longblob NOT NULL,  `file_type` varchar(50) NOT NULL default '',  `file_size` int(11) unsigned NOT NULL default '0',  PRIMARY KEY  (`file_id`))");
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."attachments_types` (  `type_id` mediumint(8) unsigned NOT NULL auto_increment,  `type_ext` varchar(5) NOT NULL default '',  `type_content` varchar(30) NOT NULL default '',  `max_size` mediumint(7) unsigned NOT NULL default '0',  PRIMARY KEY  (`type_id`))");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."attachments_types` VALUES (1, 'jpg', 'image/pjpeg', 30000);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."attachments_types` VALUES (2, 'jpeg', 'image/pjpeg', 30000);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."attachments_types` VALUES (3, 'gif', 'image/gif', 30000);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."attachments_types` VALUES (4, 'jpg', 'image/jpeg', 30000);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."attachments_types` VALUES (5, 'jpeg', 'image/jpeg', 30000);");
							// Create Bids table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."bids` (  `bid_id` int(11) unsigned NOT NULL auto_increment,  `project_id` int(11) unsigned NOT NULL default '0',  `user_id` int(11) unsigned NOT NULL default '0',  `bid_days` smallint(2) unsigned NOT NULL default '0',  `bid_amount` mediumint(8) unsigned NOT NULL default '0',  `bid_time` int(11) unsigned NOT NULL default '0',  `bid_desc` mediumtext NOT NULL, `file_id` int(11) unsigned NOT NULL default '0',  PRIMARY KEY  (`bid_id`))");
							// Create categories table						
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."categories` (  `cat_id` mediumint(8) unsigned NOT NULL auto_increment,  `parent_id` mediumint(8) unsigned NOT NULL default '0',  `cat_name` varchar(20) NOT NULL default '', `cat_order` mediumint(8) unsigned NOT NULL default '0',  PRIMARY KEY  (`cat_id`))");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."categories` VALUES (1, 0, 'Perl/CGI', 0);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."categories` VALUES (2, 0, 'Cold Fusion', 0);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."categories` VALUES (3, 0, 'PHP', 0);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."categories` VALUES (4, 0, 'Flash', 0);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."categories` VALUES (5, 0, 'ASP', 0);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."categories` VALUES (6, 0, 'Python', 0);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."categories` VALUES (7, 0, 'C/C++', 0);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."categories` VALUES (8, 0, 'Visual Basic', 0);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."categories` VALUES (9, 0, 'Java', 0);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."categories` VALUES (10, 0, '.NET', 0);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."categories` VALUES (11, 0, 'JSP', 0);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."categories` VALUES (12, 0, 'Script Installation', 0);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."categories` VALUES (13, 0, 'Javascript', 0);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."categories` VALUES (14, 0, 'Website Design', 0);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."categories` VALUES (15, 0, 'XML', 0);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."categories` VALUES (16, 0, 'Graphic Design', 0);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."categories` VALUES (17, 0, 'VFX', 0);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."categories` VALUES (18, 0, 'CG', 0);");
							// Create confirmation table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."confirmation` (  `confirm_id` int(8) unsigned zerofill NOT NULL default '00000000',  `confirm_email` varchar(255) NOT NULL default '',  `confirm_type` enum('buyer','provider') NOT NULL default 'buyer',  `confirm_time` int(11) unsigned NOT NULL default '0',  KEY `confirm_id` (`confirm_id`))");
							// Create Countries table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."countries` (  `iso` char(2) NOT NULL default '',  `name` varchar(80) NOT NULL default '',  `printable_name` varchar(80) NOT NULL default '',  `iso3` char(3) default NULL,  `numcode` smallint(6) default NULL,  PRIMARY KEY  (`iso`))");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('AF', 'AFGHANISTAN', 'Afghanistan', 'AFG', 4);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('AL', 'ALBANIA', 'Albania', 'ALB', 8);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('DZ', 'ALGERIA', 'Algeria', 'DZA', 12);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('AS', 'AMERICAN SAMOA', 'American Samoa', 'ASM', 16);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('AD', 'ANDORRA', 'Andorra', 'AND', 20);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('AO', 'ANGOLA', 'Angola', 'AGO', 24);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('AI', 'ANGUILLA', 'Anguilla', 'AIA', 660);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('AQ', 'ANTARCTICA', 'Antarctica', NULL, NULL);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('AG', 'ANTIGUA AND BARBUDA', 'Antigua and Barbuda', 'ATG', 28);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('AR', 'ARGENTINA', 'Argentina', 'ARG', 32);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('AM', 'ARMENIA', 'Armenia', 'ARM', 51);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('AW', 'ARUBA', 'Aruba', 'ABW', 533);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('AU', 'AUSTRALIA', 'Australia', 'AUS', 36);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('AT', 'AUSTRIA', 'Austria', 'AUT', 40);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('AZ', 'AZERBAIJAN', 'Azerbaijan', 'AZE', 31);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('BS', 'BAHAMAS', 'Bahamas', 'BHS', 44);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('BH', 'BAHRAIN', 'Bahrain', 'BHR', 48);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('BD', 'BANGLADESH', 'Bangladesh', 'BGD', 50);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('BB', 'BARBADOS', 'Barbados', 'BRB', 52);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('BY', 'BELARUS', 'Belarus', 'BLR', 112);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('BE', 'BELGIUM', 'Belgium', 'BEL', 56);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('BZ', 'BELIZE', 'Belize', 'BLZ', 84);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('BJ', 'BENIN', 'Benin', 'BEN', 204);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('BM', 'BERMUDA', 'Bermuda', 'BMU', 60);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('BT', 'BHUTAN', 'Bhutan', 'BTN', 64);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('BO', 'BOLIVIA', 'Bolivia', 'BOL', 68);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('BA', 'BOSNIA AND HERZEGOVINA', 'Bosnia and Herzegovina', 'BIH', 70);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('BW', 'BOTSWANA', 'Botswana', 'BWA', 72);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('BV', 'BOUVET ISLAND', 'Bouvet Island', NULL, NULL);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('BR', 'BRAZIL', 'Brazil', 'BRA', 76);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('IO', 'BRITISH INDIAN OCEAN TERRITORY', 'British Indian Ocean Territory', NULL, NULL);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('BN', 'BRUNEI DARUSSALAM', 'Brunei Darussalam', 'BRN', 96);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('BG', 'BULGARIA', 'Bulgaria', 'BGR', 100);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('BF', 'BURKINA FASO', 'Burkina Faso', 'BFA', 854);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('BI', 'BURUNDI', 'Burundi', 'BDI', 108);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('KH', 'CAMBODIA', 'Cambodia', 'KHM', 116);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('CM', 'CAMEROON', 'Cameroon', 'CMR', 120);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('CA', 'CANADA', 'Canada', 'CAN', 124);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('CV', 'CAPE VERDE', 'Cape Verde', 'CPV', 132);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('KY', 'CAYMAN ISLANDS', 'Cayman Islands', 'CYM', 136);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('CF', 'CENTRAL AFRICAN REPUBLIC', 'Central African Republic', 'CAF', 140);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('TD', 'CHAD', 'Chad', 'TCD', 148);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('CL', 'CHILE', 'Chile', 'CHL', 152);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('CN', 'CHINA', 'China', 'CHN', 156);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('CX', 'CHRISTMAS ISLAND', 'Christmas Island', NULL, NULL);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('CC', 'COCOS (KEELING) ISLANDS', 'Cocos (Keeling) Islands', NULL, NULL);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('CO', 'COLOMBIA', 'Colombia', 'COL', 170);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('KM', 'COMOROS', 'Comoros', 'COM', 174);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('CG', 'CONGO', 'Congo', 'COG', 178);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('CD', 'CONGO, THE DEMOCRATIC REPUBLIC OF THE', 'Congo, the Democratic Republic of the', 'COD', 180);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('CK', 'COOK ISLANDS', 'Cook Islands', 'COK', 184);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('CR', 'COSTA RICA', 'Costa Rica', 'CRI', 188);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('CI', 'COTE D\'IVOIRE', 'Cote D\'Ivoire', 'CIV', 384);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('HR', 'CROATIA', 'Croatia', 'HRV', 191);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('CU', 'CUBA', 'Cuba', 'CUB', 192);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('CY', 'CYPRUS', 'Cyprus', 'CYP', 196);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('CZ', 'CZECH REPUBLIC', 'Czech Republic', 'CZE', 203);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('DK', 'DENMARK', 'Denmark', 'DNK', 208);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('DJ', 'DJIBOUTI', 'Djibouti', 'DJI', 262);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('DM', 'DOMINICA', 'Dominica', 'DMA', 212);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('DO', 'DOMINICAN REPUBLIC', 'Dominican Republic', 'DOM', 214);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('EC', 'ECUADOR', 'Ecuador', 'ECU', 218);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('EG', 'EGYPT', 'Egypt', 'EGY', 818);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('SV', 'EL SALVADOR', 'El Salvador', 'SLV', 222);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('GQ', 'EQUATORIAL GUINEA', 'Equatorial Guinea', 'GNQ', 226);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('ER', 'ERITREA', 'Eritrea', 'ERI', 232);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('EE', 'ESTONIA', 'Estonia', 'EST', 233);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('ET', 'ETHIOPIA', 'Ethiopia', 'ETH', 231);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('FK', 'FALKLAND ISLANDS (MALVINAS)', 'Falkland Islands (Malvinas)', 'FLK', 238);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('FO', 'FAROE ISLANDS', 'Faroe Islands', 'FRO', 234);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('FJ', 'FIJI', 'Fiji', 'FJI', 242);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('FI', 'FINLAND', 'Finland', 'FIN', 246);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('FR', 'FRANCE', 'France', 'FRA', 250);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('GF', 'FRENCH GUIANA', 'French Guiana', 'GUF', 254);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('PF', 'FRENCH POLYNESIA', 'French Polynesia', 'PYF', 258);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('TF', 'FRENCH SOUTHERN TERRITORIES', 'French Southern Territories', NULL, NULL);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('GA', 'GABON', 'Gabon', 'GAB', 266);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('GM', 'GAMBIA', 'Gambia', 'GMB', 270);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('GE', 'GEORGIA', 'Georgia', 'GEO', 268);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('DE', 'GERMANY', 'Germany', 'DEU', 276);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('GH', 'GHANA', 'Ghana', 'GHA', 288);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('GI', 'GIBRALTAR', 'Gibraltar', 'GIB', 292);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('GR', 'GREECE', 'Greece', 'GRC', 300);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('GL', 'GREENLAND', 'Greenland', 'GRL', 304);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('GD', 'GRENADA', 'Grenada', 'GRD', 308);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('GP', 'GUADELOUPE', 'Guadeloupe', 'GLP', 312);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('GU', 'GUAM', 'Guam', 'GUM', 316);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('GT', 'GUATEMALA', 'Guatemala', 'GTM', 320);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('GN', 'GUINEA', 'Guinea', 'GIN', 324);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('GW', 'GUINEA-BISSAU', 'Guinea-Bissau', 'GNB', 624);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('GY', 'GUYANA', 'Guyana', 'GUY', 328);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('HT', 'HAITI', 'Haiti', 'HTI', 332);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('HM', 'HEARD ISLAND AND MCDONALD ISLANDS', 'Heard Island and Mcdonald Islands', NULL, NULL);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('VA', 'HOLY SEE (VATICAN CITY STATE)', 'Holy See (Vatican City State)', 'VAT', 336);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('HN', 'HONDURAS', 'Honduras', 'HND', 340);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('HK', 'HONG KONG', 'Hong Kong', 'HKG', 344);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('HU', 'HUNGARY', 'Hungary', 'HUN', 348);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('IS', 'ICELAND', 'Iceland', 'ISL', 352);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('IN', 'INDIA', 'India', 'IND', 356);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('ID', 'INDONESIA', 'Indonesia', 'IDN', 360);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('IR', 'IRAN, ISLAMIC REPUBLIC OF', 'Iran, Islamic Republic of', 'IRN', 364);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('IQ', 'IRAQ', 'Iraq', 'IRQ', 368);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('IE', 'IRELAND', 'Ireland', 'IRL', 372);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('IL', 'ISRAEL', 'Israel', 'ISR', 376);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('IT', 'ITALY', 'Italy', 'ITA', 380);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('JM', 'JAMAICA', 'Jamaica', 'JAM', 388);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('JP', 'JAPAN', 'Japan', 'JPN', 392);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('JO', 'JORDAN', 'Jordan', 'JOR', 400);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('KZ', 'KAZAKHSTAN', 'Kazakhstan', 'KAZ', 398);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('KE', 'KENYA', 'Kenya', 'KEN', 404);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('KI', 'KIRIBATI', 'Kiribati', 'KIR', 296);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('KP', 'KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF', 'Korea, Democratic People\'s Republic of', 'PRK', 408);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('KR', 'KOREA, REPUBLIC OF', 'Korea, Republic of', 'KOR', 410);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('KW', 'KUWAIT', 'Kuwait', 'KWT', 414);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('KG', 'KYRGYZSTAN', 'Kyrgyzstan', 'KGZ', 417);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('LA', 'LAO PEOPLE\'S DEMOCRATIC REPUBLIC', 'Lao People\'s Democratic Republic', 'LAO', 418);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('LV', 'LATVIA', 'Latvia', 'LVA', 428);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('LB', 'LEBANON', 'Lebanon', 'LBN', 422);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('LS', 'LESOTHO', 'Lesotho', 'LSO', 426);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('LR', 'LIBERIA', 'Liberia', 'LBR', 430);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('LY', 'LIBYAN ARAB JAMAHIRIYA', 'Libyan Arab Jamahiriya', 'LBY', 434);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('LI', 'LIECHTENSTEIN', 'Liechtenstein', 'LIE', 438);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('LT', 'LITHUANIA', 'Lithuania', 'LTU', 440);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('LU', 'LUXEMBOURG', 'Luxembourg', 'LUX', 442);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('MO', 'MACAO', 'Macao', 'MAC', 446);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('MK', 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF', 'Macedonia, the Former Yugoslav Republic of', 'MKD', 807);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('MG', 'MADAGASCAR', 'Madagascar', 'MDG', 450);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('MW', 'MALAWI', 'Malawi', 'MWI', 454);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('MY', 'MALAYSIA', 'Malaysia', 'MYS', 458);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('MV', 'MALDIVES', 'Maldives', 'MDV', 462);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('ML', 'MALI', 'Mali', 'MLI', 466);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('MT', 'MALTA', 'Malta', 'MLT', 470);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('MH', 'MARSHALL ISLANDS', 'Marshall Islands', 'MHL', 584);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('MQ', 'MARTINIQUE', 'Martinique', 'MTQ', 474);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('MR', 'MAURITANIA', 'Mauritania', 'MRT', 478);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('MU', 'MAURITIUS', 'Mauritius', 'MUS', 480);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('YT', 'MAYOTTE', 'Mayotte', NULL, NULL);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('MX', 'MEXICO', 'Mexico', 'MEX', 484);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('FM', 'MICRONESIA, FEDERATED STATES OF', 'Micronesia, Federated States of', 'FSM', 583);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('MD', 'MOLDOVA, REPUBLIC OF', 'Moldova, Republic of', 'MDA', 498);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('MC', 'MONACO', 'Monaco', 'MCO', 492);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('MN', 'MONGOLIA', 'Mongolia', 'MNG', 496);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('MS', 'MONTSERRAT', 'Montserrat', 'MSR', 500);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('MA', 'MOROCCO', 'Morocco', 'MAR', 504);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('MZ', 'MOZAMBIQUE', 'Mozambique', 'MOZ', 508);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('MM', 'MYANMAR', 'Myanmar', 'MMR', 104);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('NA', 'NAMIBIA', 'Namibia', 'NAM', 516);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('NR', 'NAURU', 'Nauru', 'NRU', 520);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('NP', 'NEPAL', 'Nepal', 'NPL', 524);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('NL', 'NETHERLANDS', 'Netherlands', 'NLD', 528);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('AN', 'NETHERLANDS ANTILLES', 'Netherlands Antilles', 'ANT', 530);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('NC', 'NEW CALEDONIA', 'New Caledonia', 'NCL', 540);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('NZ', 'NEW ZEALAND', 'New Zealand', 'NZL', 554);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('NI', 'NICARAGUA', 'Nicaragua', 'NIC', 558);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('NE', 'NIGER', 'Niger', 'NER', 562);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('NG', 'NIGERIA', 'Nigeria', 'NGA', 566);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('NU', 'NIUE', 'Niue', 'NIU', 570);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('NF', 'NORFOLK ISLAND', 'Norfolk Island', 'NFK', 574);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('MP', 'NORTHERN MARIANA ISLANDS', 'Northern Mariana Islands', 'MNP', 580);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('NO', 'NORWAY', 'Norway', 'NOR', 578);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('OM', 'OMAN', 'Oman', 'OMN', 512);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('PK', 'PAKISTAN', 'Pakistan', 'PAK', 586);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('PW', 'PALAU', 'Palau', 'PLW', 585);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('PS', 'PALESTINIAN TERRITORY, OCCUPIED', 'Palestinian Territory, Occupied', NULL, NULL);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('PA', 'PANAMA', 'Panama', 'PAN', 591);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('PG', 'PAPUA NEW GUINEA', 'Papua New Guinea', 'PNG', 598);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('PY', 'PARAGUAY', 'Paraguay', 'PRY', 600);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('PE', 'PERU', 'Peru', 'PER', 604);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('PH', 'PHILIPPINES', 'Philippines', 'PHL', 608);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('PN', 'PITCAIRN', 'Pitcairn', 'PCN', 612);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('PL', 'POLAND', 'Poland', 'POL', 616);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('PT', 'PORTUGAL', 'Portugal', 'PRT', 620);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('PR', 'PUERTO RICO', 'Puerto Rico', 'PRI', 630);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('QA', 'QATAR', 'Qatar', 'QAT', 634);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('RE', 'REUNION', 'Reunion', 'REU', 638);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('RO', 'ROMANIA', 'Romania', 'ROM', 642);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('RU', 'RUSSIAN FEDERATION', 'Russian Federation', 'RUS', 643);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('RW', 'RWANDA', 'Rwanda', 'RWA', 646);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('SH', 'SAINT HELENA', 'Saint Helena', 'SHN', 654);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('KN', 'SAINT KITTS AND NEVIS', 'Saint Kitts and Nevis', 'KNA', 659);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('LC', 'SAINT LUCIA', 'Saint Lucia', 'LCA', 662);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('PM', 'SAINT PIERRE AND MIQUELON', 'Saint Pierre and Miquelon', 'SPM', 666);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('VC', 'SAINT VINCENT AND THE GRENADINES', 'Saint Vincent and the Grenadines', 'VCT', 670);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('WS', 'SAMOA', 'Samoa', 'WSM', 882);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('SM', 'SAN MARINO', 'San Marino', 'SMR', 674);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('ST', 'SAO TOME AND PRINCIPE', 'Sao Tome and Principe', 'STP', 678);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('SA', 'SAUDI ARABIA', 'Saudi Arabia', 'SAU', 682);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('SN', 'SENEGAL', 'Senegal', 'SEN', 686);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('CS', 'SERBIA AND MONTENEGRO', 'Serbia and Montenegro', NULL, NULL);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('SC', 'SEYCHELLES', 'Seychelles', 'SYC', 690);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('SL', 'SIERRA LEONE', 'Sierra Leone', 'SLE', 694);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('SG', 'SINGAPORE', 'Singapore', 'SGP', 702);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('SK', 'SLOVAKIA', 'Slovakia', 'SVK', 703);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('SI', 'SLOVENIA', 'Slovenia', 'SVN', 705);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('SB', 'SOLOMON ISLANDS', 'Solomon Islands', 'SLB', 90);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('SO', 'SOMALIA', 'Somalia', 'SOM', 706);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('ZA', 'SOUTH AFRICA', 'South Africa', 'ZAF', 710);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('GS', 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS', 'South Georgia and the South Sandwich Islands', NULL, NULL);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('ES', 'SPAIN', 'Spain', 'ESP', 724);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('LK', 'SRI LANKA', 'Sri Lanka', 'LKA', 144);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('SD', 'SUDAN', 'Sudan', 'SDN', 736);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('SR', 'SURINAME', 'Suriname', 'SUR', 740);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('SJ', 'SVALBARD AND JAN MAYEN', 'Svalbard and Jan Mayen', 'SJM', 744);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('SZ', 'SWAZILAND', 'Swaziland', 'SWZ', 748);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('SE', 'SWEDEN', 'Sweden', 'SWE', 752);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('CH', 'SWITZERLAND', 'Switzerland', 'CHE', 756);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('SY', 'SYRIAN ARAB REPUBLIC', 'Syrian Arab Republic', 'SYR', 760);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('TW', 'TAIWAN, PROVINCE OF CHINA', 'Taiwan, Province of China', 'TWN', 158);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('TJ', 'TAJIKISTAN', 'Tajikistan', 'TJK', 762);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('TZ', 'TANZANIA, UNITED REPUBLIC OF', 'Tanzania, United Republic of', 'TZA', 834);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('TH', 'THAILAND', 'Thailand', 'THA', 764);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('TL', 'TIMOR-LESTE', 'Timor-Leste', NULL, NULL);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('TG', 'TOGO', 'Togo', 'TGO', 768);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('TK', 'TOKELAU', 'Tokelau', 'TKL', 772);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('TO', 'TONGA', 'Tonga', 'TON', 776);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('TT', 'TRINIDAD AND TOBAGO', 'Trinidad and Tobago', 'TTO', 780);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('TN', 'TUNISIA', 'Tunisia', 'TUN', 788);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('TR', 'TURKEY', 'Turkey', 'TUR', 792);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('TM', 'TURKMENISTAN', 'Turkmenistan', 'TKM', 795);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('TC', 'TURKS AND CAICOS ISLANDS', 'Turks and Caicos Islands', 'TCA', 796);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('TV', 'TUVALU', 'Tuvalu', 'TUV', 798);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('UG', 'UGANDA', 'Uganda', 'UGA', 800);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('UA', 'UKRAINE', 'Ukraine', 'UKR', 804);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('AE', 'UNITED ARAB EMIRATES', 'United Arab Emirates', 'ARE', 784);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('GB', 'UNITED KINGDOM', 'United Kingdom', 'GBR', 826);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('US', 'UNITED STATES', 'United States', 'USA', 840);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('UM', 'UNITED STATES MINOR OUTLYING ISLANDS', 'United States Minor Outlying Islands', NULL, NULL);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('UY', 'URUGUAY', 'Uruguay', 'URY', 858);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('UZ', 'UZBEKISTAN', 'Uzbekistan', 'UZB', 860);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('VU', 'VANUATU', 'Vanuatu', 'VUT', 548);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('VE', 'VENEZUELA', 'Venezuela', 'VEN', 862);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('VN', 'VIET NAM', 'Viet Nam', 'VNM', 704);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('VG', 'VIRGIN ISLANDS, BRITISH', 'Virgin Islands, British', 'VGB', 92);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('VI', 'VIRGIN ISLANDS, U.S.', 'Virgin Islands, U.s.', 'VIR', 850);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('WF', 'WALLIS AND FUTUNA', 'Wallis and Futuna', 'WLF', 876);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('EH', 'WESTERN SAHARA', 'Western Sahara', 'ESH', 732);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('YE', 'YEMEN', 'Yemen', 'YEM', 887);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('ZM', 'ZAMBIA', 'Zambia', 'ZMB', 894);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."countries` VALUES ('ZW', 'ZIMBABWE', 'Zimbabwe', 'ZWE', 716);");
							// Create Email Queue Table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."emailq` ( `q_id` int(11) unsigned NOT NULL auto_increment,  `email` varchar(255) NOT NULL default '',  `subject` varchar(255) NOT NULL default '',  `body` mediumtext NOT NULL,  PRIMARY KEY  (`q_id`))");
							// Create FAQ Category table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."faq_cats` (  `cat_id` smallint(4) unsigned NOT NULL auto_increment,  `cat_pos` mediumint(6) NOT NULL default '0',  `cat_title` varchar(255) NOT NULL default '',  PRIMARY KEY  (`cat_id`))");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."faq_cats` VALUES (2, 0, 'General');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."faq_cats` VALUES (4, 0, 'Providers/Bidding');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."faq_cats` VALUES (5, 0, 'Providers/Payments');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."faq_cats` VALUES (6, 0, 'Terms and Conditions');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."faq_cats` VALUES (7, 0, 'Troubleshooting');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."faq_cats` VALUES (9, 0, 'Buyers/Payments');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."faq_cats` VALUES (10, 0, 'Buyers/Projects');");
							// Create FAQ entry table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."faq_entries` (  `faq_id` mediumint(8) unsigned NOT NULL auto_increment,  `faq_pid` smallint(4) NOT NULL default '0',  `faq_weight` mediumint(6) NOT NULL default '0',  `faq_title` varchar(255) NOT NULL default '',  `faq_content` text NOT NULL,  PRIMARY KEY  (`faq_id`))");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."faq_entries` VALUES (2, 2, 0, 'What is {$config['site_title']}?', '{$config['site_title']} is a service that connects providers with buyers that need custom programming done for their websites. Much like an auction, buyer projects are posted and interested providers bid on them.');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."faq_entries` VALUES (3, 2, 0, 'How many programmers use {$config['site_title']}?', 'There are over x active providers on {$config['site_title']}, with several new signing up every day.');");
							// Create Providers table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."providers` (  `provider_id` mediumint(8) unsigned NOT NULL auto_increment,  `group_id` int(11) unsigned NOT NULL default '1',  `provider_status` enum('0','1','2') NOT NULL default '0',  `provider_flaggedcount` mediumint(8) unsigned NOT NULL default '0',  `provider_frozenreason` varchar(255) NOT NULL default '',  `provider_username` varchar(40) NOT NULL default '',  `provider_password` varchar(50) NOT NULL default '',  `provider_email` varchar(255) NOT NULL default '',  `provider_name` varchar(40) NOT NULL default '',  `provider_types` varchar(200) NOT NULL default '',  `provider_price` smallint(4) unsigned NOT NULL default '0',  `provider_joined` int(11) unsigned NOT NULL default '0',  `provider_rating` smallint(2) unsigned NOT NULL default '0',  `provider_reviews` mediumint(8) unsigned NOT NULL default '0',  `provider_profile` mediumtext NOT NULL,  `provider_notify` enum('0','1') NOT NULL default '0',  `provider_picture` longblob NOT NULL,  `provider_pictype` varchar(50) NOT NULL default '',  `provider_paypal` varchar(255) NOT NULL default '',  `provider_confirm` int(8) unsigned NOT NULL default '0',  `provider_forgot` varchar(40) NOT NULL default '',  `provider_lang` varchar(50) NOT NULL default '',  `provider_custom_fields` mediumtext NOT NULL,  `provider_custom_values` mediumtext NOT NULL,  PRIMARY KEY  (`provider_id`))");
							// Create Provider Balance Table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."providers_balance` (  `provider_id` mediumint(8) unsigned NOT NULL default '0',  `balance_amount` float(8,2) NOT NULL default '0.00',  PRIMARY KEY  (`provider_id`))");
							// Create html content table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."html` (  `html_id` varchar(8) NOT NULL default '',  `html_type` smallint(2) unsigned NOT NULL default '0',  `html_title` varchar(255) NOT NULL default '',  `html_content` text NOT NULL,  PRIMARY KEY  (`html_id`))");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."html` VALUES ('privacy', '0', 'Privacy Policy', 'The information below has been created in order to show that we take your privacy very seriously:<BR><BR><BR><STRONG>Cookies</STRONG><BR>{$config['site_title']} stores a cookie on your computer containing your username and password so that you can be automatically logged in to the site. If you wish to get rid of this cookie simply click on the logout button.<BR><BR><BR><STRONG>Personal Information</STRONG><BR>The information that we collect when you signed up to {$config['site_title']} will never be disclosed to any other organisations.<BR><BR><BR><STRONG>Public Forums</STRONG><BR>Our public forums can be viewed by any members if the message is not set to private. Our php scripts will scan your message before you submit it to check for any rule breaking, if you are found to be breaking the rules the admin may be informed and may take action. ');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."html` VALUES ('terms', 'Terms', 'Enter your terms here');");
							// Create jobs table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."jobs` (  `job_id` mediumint(8) unsigned NOT NULL auto_increment,  `buyer_id` mediumint(8) unsigned NOT NULL default '0',  `job_title` varchar(50) NOT NULL default '',  `job_company` varchar(50) NOT NULL default '',  `job_category` mediumint(8) NOT NULL default '0',  `job_location` enum('0','1') NOT NULL default '0',  `job_country` varchar(60) NOT NULL default '',  `job_type` mediumint(8) unsigned NOT NULL default '0',  `job_salary` varchar(20) NOT NULL default '0',  `job_desc` mediumtext NOT NULL, `job_status` set('0','1') NOT NULL default '0', `job_contact` mediumtext NOT NULL,  PRIMARY KEY  (`job_id`))");
							// Create Job category table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."jobs_categories` (  `cat_id` mediumint(20) unsigned NOT NULL auto_increment,  `cat_title` varchar(40) NOT NULL default '',  PRIMARY KEY  (`cat_id`))");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."jobs_categories` VALUES (1, 'Accounting');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."jobs_categories` VALUES (2, 'Computer Hardware');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."jobs_categories` VALUES (3, 'Consulting');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."jobs_categories` VALUES (4, 'Customer Service');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."jobs_categories` VALUES (5, 'Data Entry');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."jobs_categories` VALUES (6, 'Database Administration');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."jobs_categories` VALUES (7, 'Documentation/Writing');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."jobs_categories` VALUES (8, 'Graphic Art');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."jobs_categories` VALUES (9, 'Legal');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."jobs_categories` VALUES (10, 'Management');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."jobs_categories` VALUES (11, 'Marketing');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."jobs_categories` VALUES (12, 'Network Administration');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."jobs_categories` VALUES (13, 'Non-Profit/Volunteer');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."jobs_categories` VALUES (14, 'Other');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."jobs_categories` VALUES (15, 'Publishing');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."jobs_categories` VALUES (16, 'Research');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."jobs_categories` VALUES (17, 'Sales');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."jobs_categories` VALUES (18, 'Search Engine Optimization');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."jobs_categories` VALUES (19, 'Software Programming');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."jobs_categories` VALUES (20, 'System Administrator');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."jobs_categories` VALUES (21, 'Tech Support');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."jobs_categories` VALUES (22, 'Training');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."jobs_categories` VALUES (23, 'Translation');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."jobs_categories` VALUES (24, 'Website Design');");
							// Create job type table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."jobs_types` (  `type_id` mediumint(8) unsigned NOT NULL auto_increment,  `type_title` varchar(40) NOT NULL default '',  PRIMARY KEY  (`type_id`))");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."jobs_types` VALUES (1, 'Employee');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."jobs_types` VALUES (2, 'Temporary');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."jobs_types` VALUES (3, 'Seasonal');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."jobs_types` VALUES (4, 'Internship');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."jobs_types` VALUES (5, 'Partnership');");
							// Create message table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."messages` (  `message_id` mediumint(8) unsigned NOT NULL auto_increment,  `project_id` mediumint(8) unsigned NOT NULL default '0',  `message_date` int(11) unsigned NOT NULL default '0',  `from_id` mediumint(8) unsigned NOT NULL default '0',  `from_type` enum('0','1') NOT NULL default '0',  `to_id` mediumint(8) unsigned NOT NULL default '0',  `message_content` mediumtext NOT NULL,  PRIMARY KEY  (`message_id`))");
							// Create notification table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."notification` (  `user_id` mediumint(8) unsigned NOT NULL default '0',  `cat_id` mediumint(8) unsigned NOT NULL default '0',  `user_email` varchar(255) NOT NULL default '',  KEY `cat_id` (`cat_id`))");
							// Create Payments Table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."payments` (  `payment_id` mediumint(8) unsigned NOT NULL auto_increment,  `payment_deposit` enum('0','1') NOT NULL default '0',  `payment_withdraw` enum('0','1') NOT NULL default '0',  `payment_subscription` enum('0','1') NOT NULL default '0',  `payment_title` varchar(255) NOT NULL default '',  `payment_folder` varchar(30) NOT NULL default '',  `payment_cost` varchar(40) NOT NULL default '',  `payment_cost_withdraw` varchar(40) NOT NULL default '',  `payment_desc_deposit` mediumtext NOT NULL,  `payment_desc_withdraw` mediumtext NOT NULL,  `payment_desc_subscription` mediumtext NOT NULL,  `payment_settings` mediumtext NOT NULL,  `payment_minimum_withdraw` mediumint(5) unsigned NOT NULL default '0',  `payment_minimum_deposit` mediumint(5) unsigned NOT NULL default '0',  PRIMARY KEY  (`payment_id`))");						
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."payments` VALUES (1, '1', '1', '1', 'Paypal', 'paypal', '3% + \$0.30', 'No fee', 'Make an instant deposit using online payment service <a href=\"http://www.paypal.com\" target=\"_new\">PayPal.com</a>.', 'Make a withdrawal using online payment service <a href=\"http://www.paypal.com\" target=\"_new\">PayPal.com</a>.', '', 'a:6:{s:14:\"paypal_address\";s:18:\"sales@site.com\";s:12:\"deposit_cost\";s:4:\"0.30\";s:18:\"deposit_percentage\";s:1:\"3\";s:13:\"withdraw_cost\";s:1:\"0\";s:19:\"withdraw_percentage\";s:1:\"0\";s:13:\"withdraw_link\";s:341:\"echo \'<a target=\"_new\" href=\"https://www.paypal.com/xclick/business=\' . urlencode(\$tran_settings[\'address\']) . \'&item_name=\' . \$config[\'site_title\'] . \'+Withdrawal&amount=\' . \$tran_amount . \'&no_note=1&currency_code=USD&notify_url=\' . \$config[\'site_url\'] . \'includes/payments/withdraw_ipn.php&custom=\' . \$tran_id . \'\">Click here to pay</a>\';\";}', 5, 5);");						
							mysql_query('INSERT INTO `'.addslashes($_POST['DBPre']).'payments` VALUES (2, \'0\', \'1\', \'0\', \'Wire Transfer\', \'wire_transfer\', \'\', \'$20\', \'\', \'Have the money wire transfered to your Bank account.\', \'\', \'a:1:{s:13:"withdraw_link";s:249:"echo "<a href=\\\\"#\\\\" onclick=\\\\"javascript:window.open(\'\'print_trans_info.php?id=$tran_id\'\',\'\'ZoomImage\'\',\'\'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,menubar=no,width=550,height=400\'\');\\\\">Click here to pay</a>";";}\', 0, 0);');					
							mysql_query('INSERT INTO `'.addslashes($_POST['DBPre']).'payments` VALUES (3, \'0\', \'0\', \'0\', \'Cheque\', \'cheque\', \'\', \'\', \'\', \'\', \'\', \'a:10:{s:13:"withdraw_link";s:249:"echo "<a href=\\\\"#\\\\" onclick=\\\\"javascript:window.open(\'\'print_trans_info.php?id=$tran_id\'\',\'\'ZoomImage\'\',\'\'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,menubar=no,width=550,height=400\'\');\\\\">Click here to pay</a>";";s:12:"deposit_cost";s:0:"";s:18:"deposit_percentage";s:0:"";s:10:"Payable_To";s:0:"";s:9:"Address_1";s:0:"";s:9:"Address_2";s:0:"";s:4:"City";s:0:"";s:5:"State";s:0:"";s:13:"Post/Zip_Code";s:0:"";s:7:"Country";s:0:"";}\', 0, 0);');
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."payments` VALUES (4, '0', '0', '0', 'NoChex', 'nochex', '3% + $0.30', 'No Fee', 'Make an instant deposit using online payment service <a href=\"http://www.nochex.com\" target=\"_new\">NoChex.com</a>.', 'Make a withdrawal using online payment service <a href=\"http://www.nochex.com\" target=\"_new\">NoChex.com</a>.', '',  'a:6:{s:12:\"deposit_cost\";s:4:\"0.30\";s:18:\"deposit_percentage\";s:1:\"3\";s:13:\"withdraw_cost\";s:1:\"0\";s:19:\"withdraw_percentage\";s:1:\"0\";s:13:\"withdraw_link\";s:165:\"echo \'<a target=\"_new\" href=\"https://secure.nochex.com/?merchant_id=\' . urlencode(\$tran_settings[\'address\']) . \'&amount=\' . \$tran_amount . \'\">Click here to pay</a>\';\";s:14:\"nochex_address\";s:18:\"sales@site.com\";}', '5', '5');");							
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."payments` VALUES (5, '0', '0', '0', 'moneybookers', 'moneybookers', '3% + $0.30', 'No Fee', 'Make an instant deposit using online payment service <a href=\"http://www.moneybookers.com\" target=\"_new\">moneybookers.com</a>.', 'Make a withdrawal using online payment service <a href=\"http://www.moneybookers.com\" target=\"_new\">moneybookers.com</a>.', '',  'a:7:{s:20:\"moneybookers_address\";s:18:\"sales@site.com\";s:12:\"deposit_cost\";s:4:\"0.30\";s:18:\"deposit_percentage\";s:1:\"3\";s:13:\"withdraw_cost\";s:1:\"0\";s:19:\"withdraw_percentage\";s:1:\"0\";s:10:\"MD5_Secret\";s:0:\"\";s:13:\"withdraw_link\";s:347:\"echo \'<a target=\"_new\" href=\"https://www.moneybookers.com/app/payment.pl?pay_to_email=\' . urlencode(\$tran_settings[\'address\']) . \'&detail1_text=\' . \$config[\'site_title\'] . \'+Withdrawal&amount=\' . \$tran_amount . \'&currency=USD&notify_url=\' . \$config[\'site_url\'] . \'includes/payments/withdraw_ipn.php&custom=\' . \$tran_id . \'\">Click here to pay</a>\';\";}', '5', '5');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."payments` VALUES (6, '0', '0', '0', 'egold', 'egold', '3% + $0.30', 'No Fee', 'Make an instant deposit using online payment service <a href=\"http://www.egold.com\" target=\"_new\">egold.com</a>.', 'Make a withdrawal using online payment service <a href=\"http://www.egold.com\" target=\"_new\">egold.com</a>.', '',  'a:7:{s:13:\"egold_account\";s:6:\"000000\";s:12:\"deposit_cost\";s:4:\"0.30\";s:18:\"deposit_percentage\";s:1:\"3\";s:13:\"withdraw_cost\";s:1:\"0\";s:19:\"withdraw_percentage\";s:1:\"0\";s:10:\"MD5_Secret\";s:0:\"\";s:13:\"withdraw_link\";s:560:\"echo \'<a target=\"_new\" href=\"https://www.e-gold.com/sci_asp/payments.asp?PAYEE_ACCOUNT=\' . urlencode(\$tran_settings[\"account\"]) . \'&PAYMENT_AMOUNT=\' . \$tran_amount . \'&PAYMENT_UNITS=1&STATUS_URL=\' . \$config[\"site_url\"] . \'includes/payments/withdraw_ipn.php&BAGGAGE_FIELDS=Description+transaction_id&Description=\' . \$config[\"site_title\"] . \'+Withdrawal&transaction_id=\' . \$tran_id . \'&PAYEE_NAME=\'.\$config[\'site_url\'].\'&PAYMENT_METAL_ID=1&PAYMENT_URL=\'.\$config[\'site_url\'] . \'manage.php&NOPAYMENT_URL=\'.\$config[\'site_url\'] . \'manage.php\">Click here to pay</a>\';\";}', '5', '5');");					
							// Create projects table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."projects` (  `project_id` int(11) unsigned NOT NULL auto_increment,  `project_status` set('0','1','2') NOT NULL default '0',  `project_title` varchar(200) NOT NULL default '',  `project_desc` mediumtext NOT NULL,  `project_types` varchar(200) NOT NULL default '',  `project_subtypes` varchar(200) NOT NULL default '',  `project_db` varchar(100) NOT NULL default '',  `project_os` varchar(100) NOT NULL default '',  `project_budget_min` mediumint(8) unsigned NOT NULL default '0',  `project_budget_max` mediumint(8) unsigned NOT NULL default '0',  `project_start` int(12) NOT NULL default '0',  `project_end` int(12) NOT NULL default '0',  `project_featured` set('0','1') NOT NULL default '0',  `project_fileid` mediumint(8) unsigned NOT NULL default '0',  `project_bids` smallint(5) unsigned NOT NULL default '0',  `project_avgbid` mediumint(8) unsigned NOT NULL default '0',  `provider_id` mediumint(8) unsigned NOT NULL default '0',  `buyer_id` mediumint(8) unsigned NOT NULL default '0',  `buyer_rated` enum('0','1') NOT NULL default '0',  `provider_rated` enum('0','1') NOT NULL default '0', `checkstamp` varchar(255) NOT NULL default '', `project_emailed` tinyint(1) unsigned NOT NULL default '0',`project_paid` tinyint(1) unsigned NOT NULL default '0',  `project_custom_fields` mediumtext NOT NULL,
  `project_custom_values` mediumtext NOT NULL, PRIMARY KEY  (`project_id`), FULLTEXT KEY `project_title` (`project_title`,`project_desc`)) ENGINE=MyISAM"); 
							// Create qoutes table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."quotes` (  `quote_id` mediumint(8) unsigned NOT NULL auto_increment,  `quote_comment` mediumtext NOT NULL,  `quote_author` varchar(40) NOT NULL default '',  PRIMARY KEY  (`quote_id`)) TYPE=MyISAM AUTO_INCREMENT=8 ;");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."quotes` VALUES (1, 'Thanks to {$config['site_title']} I have found solutions to all my Server problems, and all within 10 mins of posting. I had two projects and I am very pleased with the high standard of work and will certainly be using {$config['site_title']} again. I will be putting a link to {$config['site_title']}, for all my customers, on my web hosting site. Thank you all for a great service and great programmers. YOU ARE #1!', 'John');");
							// Create reviews table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."reviews` (  `review_id` mediumint(8) unsigned NOT NULL auto_increment,  `review_comment` mediumtext NOT NULL,  `review_rating` smallint(2) unsigned NOT NULL default '0',  `review_time` int(11) unsigned NOT NULL default '0',  `review_type` enum('0','1') NOT NULL default '0',  `project_id` mediumint(8) unsigned NOT NULL default '0',  `buyer_id` mediumint(8) unsigned NOT NULL default '0',  `provider_id` mediumint(8) unsigned NOT NULL default '0',  PRIMARY KEY  (`review_id`))");
							// Create Rules Table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."rules` (  `rule_id` mediumint(8) unsigned NOT NULL auto_increment,  `page` varchar(40) NOT NULL default '',  `rule_title` varchar(200) NOT NULL default '',  `rule_eregi` varchar(255) NOT NULL default '',  `rule_msg` varchar(255) NOT NULL default '',  PRIMARY KEY  (`rule_id`))");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."rules` VALUES (1, 'board_post.php', 'No Email Address', '[_a-z0-9-]+(\\.[_a-z0-9-]+)*@[a-z0-9-]+(\\.[a-z0-9-]{2,})', 'You Cannot post email address\' or IM Address\' in the message board.');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."rules` VALUES (1, 'board_post.php', 'No HTML Code', '<([^<>]*)>', 'The use of HTML or other scripting languages is not permitted.');");
							// Create Transactions Table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."transactions` (  `transaction_id` mediumint(8) unsigned NOT NULL auto_increment,  `transaction_status` smallint(2) unsigned NOT NULL default '0',  `transaction_type` enum('buy','pri','adm') NOT NULL default 'buy',  `transaction_method` varchar(20) NOT NULL default '',  `buyer_id` mediumint(8) unsigned NOT NULL default '0',  `provider_id` mediumint(8) unsigned NOT NULL default '0',  `transaction_ip` varchar(15) NOT NULL default '',  `transaction_time` int(11) unsigned NOT NULL default '0',  `transaction_amount` decimal(8,2) NOT NULL default '0.00',  `transaction_description` varchar(255) NOT NULL default '',  `transaction_proc` varchar(40) NOT NULL default '', `transaction_settings` mediumtext NOT NULL,  PRIMARY KEY  (`transaction_id`))");
							// Create Violations table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."violation` (  `violation_id` mediumint(8) unsigned NOT NULL auto_increment,  `violation_name` varchar(40) NOT NULL default '',  `violation_username` varchar(40) NOT NULL default '',  `violation_subject` varchar(200) NOT NULL default '',  `violation_user` varchar(40) NOT NULL default '',  `violation_url` varchar(255) NOT NULL default '',  `violation_details` mediumtext NOT NULL,  `violation_time` int(11) unsigned NOT NULL default '0',  PRIMARY KEY  (`violation_id`))");
							// Create Buyers tabke
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."buyers` (  `buyer_id` mediumint(8) unsigned NOT NULL auto_increment,  `group_id` int(11) unsigned NOT NULL default '1',  `buyer_status` enum('0','1','2') NOT NULL default '0',  `buyer_flaggedcount` mediumint(8) unsigned NOT NULL default '0',  `buyer_frozenreason` varchar(255) NOT NULL default '',  `buyer_username` varchar(20) NOT NULL default '',  `buyer_password` varchar(50) NOT NULL default '',  `buyer_email` varchar(255) NOT NULL default '',  `buyer_name` varchar(40) NOT NULL default '',  `buyer_joined` int(11) unsigned NOT NULL default '0',  `buyer_rating` smallint(2) unsigned NOT NULL default '0',  `buyer_reviews` mediumint(8) unsigned NOT NULL default '0',  `buyer_confirm` int(8) unsigned NOT NULL default '0',  `buyer_forgot` varchar(40) NOT NULL default '',  `buyer_lang` varchar(50) NOT NULL default '',  `buyer_custom_fields` mediumtext NOT NULL,  `buyer_custom_values` mediumtext NOT NULL,  PRIMARY KEY  (`buyer_id`))");
								// Create Buyer balance table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."buyers_balance` (  `buyer_id` mediumint(8) unsigned NOT NULL default '0',  `balance_amount` float(8,2) NOT NULL default '0.00',  PRIMARY KEY  (`buyer_id`))");
							// Create log table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."logs` (  `log_id` int(11) unsigned NOT NULL auto_increment,  `log_date` int(11) unsigned NOT NULL default '0',  `log_summary` varchar(100) NOT NULL default '',  `log_details` mediumtext NOT NULL,  PRIMARY KEY  (`log_id`))");
							// Create bans table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."bans` (  `ban_id` int(11) unsigned NOT NULL auto_increment,  `ban_type` varchar(20) NOT NULL default '',  `ban_value` varchar(255) NOT NULL default '',  `ban_time` int(11) unsigned NOT NULL default '0',  PRIMARY KEY  (`ban_id`))");
							// Create inbox table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."inbox` (  `message_id` int(11) unsigned NOT NULL auto_increment,  `message_from` int(11) unsigned NOT NULL default '0',  `message_fromtype` enum('0','1') NOT NULL default '0',  `message_to` int(11) unsigned NOT NULL default '0',  `message_totype` enum('0','1') NOT NULL default '0',  `message_date` int(11) unsigned NOT NULL default '0',  `message_subject` varchar(50) NOT NULL default '',  `message_body` mediumtext NOT NULL,  `message_read` enum('0','1') NOT NULL default '0',  PRIMARY KEY  (`message_id`),  KEY `message_from` (`message_from`,`message_fromtype`),  KEY `message_to` (`message_to`,`message_totype`))");
							// Create usergroup table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."usergroups` (  `group_id` int(11) unsigned NOT NULL auto_increment,  `group_removable` tinyint(1) unsigned NOT NULL default '0',  `group_name` varchar(50) NOT NULL default '',  `group_create_project` tinyint(1) NOT NULL default '1',  `group_create_job` tinyint(1) NOT NULL default '1',  `group_bid` tinyint(1) NOT NULL default '1',  `post_project_discount` double(8,2) NOT NULL default '0.00',  `post_featured_discount` double(8,2) NOT NULL default '0.00',  `post_job_discount` double(8,2) NOT NULL default '0.00',  PRIMARY KEY  (`group_id`))");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."usergroups` VALUES(1, 0, 'Registered User', 1, 1, 1, 0.00, 0.00, 0.00);");
							// Create Custom Fields Table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."custom_fields` (  `custom_id` int(11) unsigned NOT NULL auto_increment,  `custom_page` varchar(60) NOT NULL default '',  `custom_name` varchar(40) NOT NULL default '',  `custom_title` varchar(100) NOT NULL default '',  `custom_type` varchar(40) NOT NULL default '',  `custom_content` varchar(20) NOT NULL default '',  `custom_min` int(11) unsigned NOT NULL default '0',  `custom_max` int(11) unsigned NOT NULL default '0',  `custom_required` tinyint(1) unsigned NOT NULL default '0',  `custom_options` mediumtext NOT NULL,  `custom_default` varchar(200) NOT NULL default '',  PRIMARY KEY  (`custom_id`),  KEY `custom_page` (`custom_page`));");
							// Create Settings Table						
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."settings` (  `setting_id` int(11) unsigned NOT NULL auto_increment,  `group_id` int(11) unsigned NOT NULL default '0',  `setting_file` varchar(100) NOT NULL default '',  `setting_title` varchar(200) NOT NULL default '',  `setting_name` varchar(100) NOT NULL default '',  `setting_type` varchar(30) NOT NULL default 'textfield',  `setting_options` mediumtext NOT NULL,  `setting_value` mediumtext NOT NULL,  `setting_display` tinyint(1) unsigned NOT NULL default '0',  PRIMARY KEY  (`setting_id`),  KEY `setting_name` (`setting_name`),  KEY `setting_file` (`setting_file`));");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."settings` VALUES (1, 0, '', 'Cron Time', 'cron_time', 'hidden', '', '0', 0);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."settings` VALUES (2, 2, 'bid.php', 'Allow Bid Attachments', 'bid_attach', 'select', '1|Yes,0|No', '1', 1);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."settings` VALUES (3, 3, 'index.php', 'Project Start Date Format', 'index_project_start_date', 'textfield', '', 'n/j/Y', 1);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."settings` VALUES (4, 3, 'index.php', 'Project End Date Format', 'index_project_end_date', 'textfield', '', 'n/j/Y', 1);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."settings` VALUES (5, 4, 'transactions.php', 'Transaction Date Format', 'trans_date_format', 'textfield', '', 'n/j/Y', 1 ) ;");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."settings` VALUES (6, 4, 'escrow.php', 'Allow Provider To Escrow', 'allow_provider_escrow', 'select', '1|Yes,0|No', '0', 1);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."settings` VALUES (7, 5, 'search.php', 'Search Mode', 'search_mode', 'select', '1|Basic,2|Full-Text', '1', '1');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."settings` VALUES (8, 6, '', 'Urgent Time', 'urgent_time', 'textfield', '', '86400', 1);");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."settings` VALUES (9, 0, 'cron.php', 'Keep Online Time', 'keep_online_time', 'textfield', '', '900', 0);
");
							// Create Escrow Table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."escrow` ( `escrow_id` int(11) unsigned NOT NULL auto_increment,  `escrow_from` char(3) NOT NULL default '',  `escrow_status` tinyint(1) unsigned NOT NULL default '0',  `escrow_amount` decimal(8,2) NOT NULL default '0.00',  `escrow_desc` varchar(200) NOT NULL default '',  `buyer_id` int(11) unsigned NOT NULL default '0',  `provider_id` int(11) unsigned NOT NULL default '0',  PRIMARY KEY  (`escrow_id`))");
							// Create Block list table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."block` (  `block_id` int(11) unsigned NOT NULL auto_increment,  `block_type` char(3) NOT NULL default '',  `user_id` int(11) unsigned NOT NULL default '0',  `user_id2` int(11) unsigned NOT NULL default '0',  PRIMARY KEY  (`block_id`),  UNIQUE KEY `user_id` (`user_id`,`user_id2`))");
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."settings_groups` (  `group_id` int(11) unsigned NOT NULL auto_increment,  `group_name` varchar(50) NOT NULL default '',  PRIMARY KEY  (`group_id`))");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."settings_groups` VALUES (2, 'Bidding');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."settings_groups` VALUES (3, 'Front Page');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."settings_groups` VALUES (4, 'Transactions');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."settings_groups` VALUES (5, 'Search');");
							mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."settings_groups` VALUES (6, 'Misc');");
							// Add Upgrades table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."upgrades` ( `upgrade_id` int(11) unsigned NOT NULL auto_increment,  `sub_id` int(11) unsigned NOT NULL default '0',  `user_type` varchar(15) NOT NULL default '',  `user_id` int(11) unsigned NOT NULL default '0',  `upgrade_lasttime` int(11) unsigned NOT NULL default '0',  `upgrade_expires` int(11) unsigned NOT NULL default '0',  PRIMARY KEY  (`upgrade_id`))");
							// Create subscription table
							mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."subscriptions` (  `sub_id` int(11) unsigned NOT NULL auto_increment,  `sub_title` varchar(100) NOT NULL default '',  `group_id` int(11) unsigned NOT NULL default '0',  `sub_term` varchar(10) NOT NULL default 'MONTHLY',  `sub_amount` double(8,2) NOT NULL default '0.00',  `sub_account` tinyint(1) unsigned NOT NULL default '1',  `sub_type` char(3) NOT NULL default '',  PRIMARY KEY  (`sub_id`))");
							
							$step = 5;
						}
					}
					else
					{
						$step = 4;
					}
				}
				else
				{
					$error_number = mysql_errno();
				
					if($error_number == '1044')
					{
						$error = $lang['ERROR1044'];
					}
					elseif($error_number == '1046')
					{
						$error = $lang['ERROR1046'];
					}
					elseif($error_number = '1049')
					{
						$error = $lang['ERROR1049'];
					}
					else
					{
						$error = mysql_error().' - '.$error_number;
					}
					$step = 3;
				}
			}
			else
			{
				$error_number = mysql_errno();
			
				if($error_number == '1045')
				{
					$error = $lang['ERROR1045'];
				}
				elseif($error_number == '2005')
				{
					$error = $lang['ERROR2005'];
				}
				else
				{
					$error = mysql_error().' - '.$error_number;
				}
				$step = 3;
			}
		}
	}
}
else
{
	$step = 1;
	$error = 'Could not write to your includes/config.php file.<br><br>Please check that you have set the chmod/permisions to 0777';
}

if($step == 1)
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $config['site_title']; ?> Installation</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.style1 {
	font-size: 24px;
	font-weight: bold;
}
.style2 {
	color: #003366;
	font-weight: bold;
}
.style5 {font-size: 9px}
.style12 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; }
.style15 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: bold; }
.error { color:#FF0000;}
-->
</style></head>

<body>
<table width="500"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="500%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><span class="style1"><?php echo $config['site_title']; ?> Installation</span></td>
        <td align="right" valign="bottom">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><br></td>
  </tr>
  <tr>
    <td>
	<br><br>
	<span class="error"><?php echo $error;?></span><br><br><br>
	<a href="index.php">Click here</a> once you have corrected this.<br><br><br><br><bR>
    </td>
  </tr>
  <tr>
    <td><div align="center"><span class="style5">&copy; 2008 <a href="http://www.technotrix.co.in">Technotrix</a></span></div><br><br></td>
  </tr>
</table>
</body>
</html>
<?php
}
elseif($step == 2)
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $config['site_title']; ?> Installation</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.style1 {
	font-size: 24px;
	font-weight: bold;
}
.style2 {
	color: #003366;
	font-weight: bold;
}
.style5 {font-size: 9px}
-->
</style></head>

<body>
<table width="500"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="500%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><span class="style1"><?php echo $config['site_title']; ?> Installation</span></td>
        <td align="right" valign="bottom">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><br><br><br></td>
  </tr>
  <tr>
    <td>Please select the language you would like <?php echo $config['site_title']; ?> to use:<br><small style="color:#FF0000;">*Some parts of the installation may not be in your chosen language</small><Br>
      <br>      <table width="500"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="33%" height="140" align="left"><table border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td align="center"><a href="index.php?lang=english"><img src="images/flag_en.gif" alt="English" width="130" height="87" vspace="2" border="0"></a><br>
                <a href="index.php?lang=english">English</a></td>
            </tr>
          </table></td>
          <td width="33%" height="140" align="left"><table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td align="center"><a href="index.php?lang=german"><img src="images/flag_german.gif" alt="Deutsch" width="130" height="87" vspace="2" border="0"></a><br>
                <a href="index.php?lang=german">Deutsch</a></td>
            </tr>
          </table></td>
          <td width="33%" height="140" align="left"><table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td align="center"><a href="index.php?lang=french"><img src="images/flag_french.gif" alt="French" width="130" height="87" vspace="2" border="0"></a><br>
                <a href="index.php?lang=french">Fran&ccedil;ais</a></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td width="33%" height="140" align="left"><table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td align="center"><a href="index.php?lang=spanish"><img src="images/flag_spanish.gif" alt="Espanol" width="130" height="87" vspace="2" border="0"></a><br>
                <a href="index.php?lang=spanish">Espa&ntilde;ol</a></td>
            </tr>
          </table></td>
          <td width="33%" height="140" align="left"><table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td align="center"><a href="index.php?lang=italian"><img src="images/flag_italian.gif" alt="Italian" width="130" height="87" vspace="2" border="0"></a><br>
                <a href="index.php?lang=italian">Italian</a></td>
            </tr>
          </table></td>
          <td width="33%" height="140" align="left"></td>
        </tr>
      </table>
      <br>
      <br>
    </td>
  </tr>
  <tr>
    <td><div align="center"><span class="style5">&copy; 2008 <a href="http://www.technotrix.co.in">Technotrix</a></span></div><br><br></td>
  </tr>
</table>
</body>
</html>
<?php
}
elseif($step == 3)
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $config['site_title']; ?> Installation</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.style1 {
	font-size: 24px;
	font-weight: bold;
}
.style2 {
	color: #003366;
	font-weight: bold;
}
.style5 {font-size: 9px}
.style12 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; }
.style15 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: bold; }
.error { color:#FF0000;}
-->
</style></head>

<body>
<table width="500"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="500%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><span class="style1"><?php echo $config['site_title']; ?> Installation</span></td>
        <td align="right" valign="bottom">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><br><br><br></td>
  </tr>
  <tr>
    <td><form name="form1" method="post" action="index.php" style="padding:0px;margin:0px;">
	<table border="0" cellspacing="0" cellpadding="3">
	<tr><td><?php echo $lang['MYSQLFILL']; ?>: <br>
      <br>
	<?php
	if($error != '')
	{
		echo '<span class="error">'.$error.'</span><br><Br>';
	}
	?>
	</td></tr></table>
      <br>      <table border="0" cellspacing="0" cellpadding="3">
        <tr>
          <td><span class="style12"><?php echo $lang['MYSQLHOST'];?>: </span></td>
          <td><input style="width:150px;" name="DBHost" type="text" id="DBHost" value="<?php if(isset($_POST['DBHost'])){ echo $_POST['DBHost']; } else { echo 'localhost'; } ?>"></td>
          <td><span class="style12">&nbsp;<a href="javascript:alert('<?php echo $lang['HOSTHELP'];?>');">(?)</a> </span></td>
        </tr>
        <tr>
          <td><span class="style12"><?php echo $lang['MYSQLUSER'];?>:</span></td>
          <td><input style="width:150px;" name="DBUser" type="text" id="DBUser" value="<?php if(isset($_POST['DBUser'])){ echo $_POST['DBUser']; } ?>"></td>
          <td><span class="style12">&nbsp;<a href="javascript:alert('<?php echo $lang['USERHELP'];?>');">(?)</a> </span></td>
        </tr>
        <tr>
          <td><span class="style12"><?php echo $lang['MYSQLPASS'];?>:</span></td>
          <td><input style="width:150px;" name="DBPass" type="password" id="DBPass" value="<?php if(isset($_POST['DBPass'])){ echo $_POST['DBPass']; } ?>"></td>
          <td><span class="style12">&nbsp;<a href="javascript:alert('<?php echo $lang['PASSHELP'];?>');">(?)</a> </span></td>
        </tr>
        <tr>
          <td><span class="style12"><?php echo $lang['MYSQLNAME'];?>: </span></td>
          <td><input style="width:150px;" name="DBName" type="text" id="DBName" value="<?php if(isset($_POST['DBName'])){ echo $_POST['DBName']; } ?>"></td>
          <td><span class="style12">&nbsp;<a href="javascript:alert('<?php echo $lang['NAMEHELP'];?>');">(?)</a> </span></td>
        </tr>
        <tr>
          <td><span class="style12"><?php echo $lang['MYSQLPRE'];?>: </span></td>
          <td><input style="width:150px;" name="DBPre" type="text" id="DBPre" value="<?php if(isset($_POST['DBPre'])){ echo $_POST['DBPre']; } else { echo 'lance_'; } ?>"></td>
          <td><span class="style12">&nbsp;<a href="javascript:alert('<?php echo $lang['PREHELP'];?>');">(?)</a> </span></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input style="width:150px;" name="Submit" type="submit" value="Next &gt;&gt;"></td>
          <td>&nbsp;</td>
        </tr>
      </table>
            <br><br><br>
			<input name="lang" type="hidden" value="<?php echo $_POST['lang'];?>">
			 </form>
    </td>
  </tr>
  <tr>
    <td><div align="center"><span class="style5">&copy; 2008 <a href="http://www.technotrix.co.in">Technotrix</a></span></div><br><br></td>
  </tr>
</table>
</body>
</html>
<?php
}
elseif($step == '4')
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $config['site_title']; ?> Installation</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.style1 {
	font-size: 24px;
	font-weight: bold;
}
.style2 {
	color: #003366;
	font-weight: bold;
}
.style5 {font-size: 9px}
.style12 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; }
.style15 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: bold; }
-->
</style></head>

<body>
<table width="500"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="500%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><span class="style1"><?php echo $config['site_title']; ?> Installation</span></td>
        <td align="right" valign="bottom">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><br></td>
  </tr>
  <tr>
    <td>
	<form name="form1" method="post" action="index.php" style="padding:0px;margin:0px;">
	      <?php echo $lang['ADMFILL'];?><br>
      <br>
      <br>      <table border="0" cellspacing="0" cellpadding="3">
        <tr>
          <td><span class="style12"><?php echo $lang['ADMUSER'];?>: </span></td>
          <td><input style="width:150px;" name="adminuser" type="text" id="adminuser" value="<?php if(isset($_POST['adminuser'])){ echo $_POST['adminuser']; } ?>"></td>
          <td><span class="style12">&nbsp;<a href="javascript:alert('<?php echo $lang['ADMUSERHELP'];?>');">(?)</a> </span></td>
        </tr>
        <tr>
          <td><span class="style12"><?php echo $lang['ADMPASS'];?>: </span></td>
          <td><input style="width:150px;" name="adminpass" type="password" id="adminpass" value="<?php if(isset($_POST['adminpass'])){ echo $_POST['adminpass']; } ?>"></td>
          <td><span class="style12">&nbsp;<a href="javascript:alert('<?php echo $lang['ADMPASSHELP'];?>');">(?)</a> </span></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input style="width:150px;" name="Submit" type="submit" value="<?php echo $lang['NEXT'];?>"></td>
          <td>&nbsp;</td>
        </tr>
      </table>
            <br>
            <br>
			<input name="DBHost" type="hidden" id="DBHost" value="<?php echo $_POST['DBHost'];?>">
			<input name="DBName" type="hidden" id="DBName" value="<?php echo $_POST['DBName'];?>">
			<input name="DBUser" type="hidden" id="DBUser" value="<?php echo $_POST['DBUser'];?>">
			<input name="DBPass" type="hidden" id="DBPass" value="<?php echo $_POST['DBPass'];?>">
			<input name="DBPre" type="hidden" id="DBPre" value="<?php echo $_POST['DBPre'];?>">
			<input name="lang" type="hidden" value="<?php echo $_POST['lang'];?>">
			</form>
    </td>
  </tr>
  <tr>
    <td><div align="center"><span class="style5">&copy; 2008 <a href="http://www.technotrix.co.in">Technotrix</a></span></div><br><br></td>
  </tr>
</table>
</body>
</html>
<?php
}
elseif($step == '5')
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $config['site_title']; ?> Installation</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.style1 {
	font-size: 24px;
	font-weight: bold;
}
.style2 {
	color: #003366;
	font-weight: bold;
}
.style5 {font-size: 9px}
.style12 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; }
.style15 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: bold; }
-->
</style></head>

<body>
<table width="500"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="500%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><span class="style1"><?php echo $config['site_title']; ?> Installation</span></td>
        <td align="right" valign="bottom">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><br></td>
  </tr>
  <tr>
    <td><?php echo $lang['THANKYOU'];?>:<br>
      <br>      
      - <a href="../index.php">Front End</a><br>
      <br>
      - <a href="../adm/">Admin</a><br>
      <br>
    </td>
  </tr>
  <tr>
    <td><div align="center"><span class="style5">&copy; 2008 <a href="http://www.technotrix.co.in">Technotrix</a></span></div><br><br></td>
  </tr>
</table>
</body>
</html>
<?php
}
?>