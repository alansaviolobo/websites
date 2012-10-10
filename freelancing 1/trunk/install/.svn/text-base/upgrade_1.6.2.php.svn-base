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
	echo 'Before you run an upgrade it is recomended that you backup your '.$config['site_title'].' database<br><Br>Are you sure you want to upgrade your '.$config['site_title'].' installation from '.$config['version'].' to '.$install_version.'?<br><br><a href="upgrade_1.6.2.php?install=1">Yes do it</a>';
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
	mysql_query("DROP TABLE `".$config['db']['pre']."amenu`");
	mysql_query("CREATE TABLE `".addslashes($config['db']['pre'])."amenu` (  `menu_id` smallint(3) unsigned NOT NULL auto_increment,  `sort_id` smallint(3) unsigned NOT NULL default '0',  `parent_id` smallint(3) unsigned NOT NULL default '0',  `menu_title` varchar(40) NOT NULL default '',  `menu_icon` varchar(100) NOT NULL default '',  `menu_url` varchar(255) NOT NULL default '',  `menu_target` varchar(10) NOT NULL default '',  `menu_desc` varchar(100) NOT NULL default '',  PRIMARY KEY  (`menu_id`));");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (1, 10, 0, 'Configuration', '', '', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (8, 20, 1, 'Rules', '', '', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (5, 90, 0, 'Content', '', '', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (70, 30, 1, 'Site Details', '<img src=\"images/smicon_config.gif\">', 'configuration.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (6, 20, 91, 'Add Page', '<img src=\"images/smicon_addrule.gif\">', 'content_add.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (7, 30, 91, 'Edit Page', '<img src=\"images/smicon_editrule.gif\">', 'content_edit.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (9, 0, 8, 'Add Rule', '<img src=\"images/smicon_addrule.gif\">', 'rules_add.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (10, 0, 8, 'Edit Rule', '<img src=\"images/smicon_editrule.gif\">', 'rules_edit.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (11, 9, 5, 'FAQ', '', '', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (14, 0, 11, 'Add Entry', '<img src=\"images/smicon_addrule.gif\">', 'faq_entry_add.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (15, 0, 11, 'Edit Entry', '<img src=\"images/smicon_editrule.gif\">', 'faq_entry_edit.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (19, 20, 102, 'Edit Provider(s)', '<img src=\"images/smicon_editfreelance.gif\">', 'provider_edit.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (21, 20, 0, 'Projects', '', '', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (22, 2, 21, 'Search', '<img src=\"images/smicon_search.gif\">', 'project_search.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (23, 5, 5, 'Quotes', '', '', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (24, 0, 23, 'Add Quote', '<img src=\"images/smicon_addquote.gif\">', 'quote_add.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (25, 0, 23, 'Edit Quote', '<img src=\"images/smicon_editquote.gif\">', 'quotes_edit.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (27, 40, 1, 'Template Settings', '<img src=\"images/smicon_template.gif\">', 'template_settings.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (29, 5, 40, 'Transactions', '', '', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (30, 2, 29, 'Search', '<img src=\"images/smicon_search.gif\">', 'transaction_search.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (31, 3, 29, 'View All', '<img src=\"images/smicon_viewall.gif\">', 'transactions_view.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (34, 10, 101, 'Edit Buyer(s)', '<img src=\"images/smicon_editfreelance.gif\">', 'buyer_edit.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (39, 3, 21, 'View All', '<img src=\"images/smicon_viewall.gif\">', 'projects_view.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (36, 190, 0, 'XML', '', '', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (37, 0, 36, 'Manage', '<img src=\"images/smicon_manage.gif\">', 'xml_manage.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (38, 0, 36, 'View Links', '<img src=\"images/smicon_viewlinks.gif\">', 'xml_links.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (40, 50, 0, 'Payments', '', '', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (41, 20, 40, 'Settings', '<img src=\"images/smicon_manage.gif\">', 'payment_settings.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (43, 30, 40, 'Withdrawal Queue', '', 'payment_queue.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (44, 30, 0, 'Jobs', '', '', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (45, 3, 44, 'Search', '<img src=\"images/smicon_search.gif\">', 'jobs_search.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (46, 4, 44, 'View All', '<img src=\"images/smicon_viewall.gif\">', 'jobs_view.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (47, 10, 1, 'Attachments', '<img src=\"images/smicon_config.gif\">', 'attachments.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (71, 0, 47, 'Add Type', '<img src=\"images/smicon_addrule.gif\">', 'attachments_add.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (72, 0, 47, 'Edit Type(s)', '<img src=\"images/smicon_editrule.gif\">', 'attachments.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (73, 0, 21, 'Categories', '', '', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (74, 0, 73, 'Add Category', '<img src=\"images/smicon_addrule.gif\">', 'projcat_add.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (75, 0, 73, 'Edit Category', '<img src=\"images/smicon_editrule.gif\">', 'projcat_edit.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (76, 0, 44, 'Categories', '', '', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (77, 0, 76, 'Add Category', '<img src=\"images/smicon_addrule.gif\">', 'jobcat_add.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (78, 0, 76, 'Edit Category', '<img src=\"images/smicon_editrule.gif\">', 'jobcat_edit.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (79, 2, 44, 'Types', '', '', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (80, 0, 79, 'Add Type', '<img src=\"images/smicon_addrule.gif\">', 'jobtype_add.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (81, 0, 79, 'Edit Type', '<img src=\"images/smicon_editrule.gif\">', 'jobtype_edit.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (82, 1, 29, 'Add Transaction', '<img src=\"images/smicon_addrule.gif\">', 'transaction_add.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (83, 200, 0, 'Admins', '', '', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (84, 0, 83, 'Add Admin', '<img src=\"images/smicon_addrule.gif\">', 'admin_add.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (85, 0, 83, 'Edit Admin', '<img src=\"images/smicon_editrule.gif\">', 'admin_view.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (86, 300, 0, 'Logout', '', 'logout.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (90, 40, 0, 'Users', '', '', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (91, 50, 5, 'Pages', '', '', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (92, 0, 90, 'Bans', '', '', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (93, 10, 92, 'Add Ban', '<img src=\"images/smicon_addrule.gif\">', 'ban_add.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (94, 20, 92, 'Edit Ban', '<img src=\"images/smicon_editrule.gif\">', 'ban_view.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (96, 35, 1, 'Transaction Settings', '<img src=\"images/smicon_template.gif\">', 'transaction_settings.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (98, 15, 102, 'Search Provider(s)', '<img src=\"images/smicon_search.gif\">', 'search_users.php?type=provider', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (97, 5, 101, 'Search Buyer(s)', '<img src=\"images/smicon_search.gif\">', 'search_users.php?type=buyer', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (99, 7, 101, 'Add Buyer', '<img src=\"images/smicon_addrule.gif\">', 'buyer_add.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (100, 17, 102, 'Add Provider', '<img src=\"images/smicon_addrule.gif\">', 'provider_add.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (101, 10, 90, 'Buyers', '', '', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (102, 20, 90, 'Providers', '', '', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (103, 1, 21, 'Bids', '', '', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (104, 10, 103, 'Search', '<img src=\"images/smicon_search.gif\">', 'bids_search.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (105, 20, 103, 'View All', '<img src=\"images/smicon_viewall.gif\">', 'bids_view.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES(106, 40, 1, 'Log Viewer', '<img src=\"images/smicon_edittemplate.gif\">', 'log_viewer.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES(107, 25, 1, 'Custom Fields', '', '', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES(108, 10, 107, 'Add Field', '<img src=\"images/smicon_addrule.gif\">', 'custom_add.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES(109, 20, 107, 'Edit Field', '<img src=\"images/smicon_editrule.gif\">', 'custom_view.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (110, 8, 40, 'Escrow', '', '', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES (111, 3, 110, 'View All', '<img src=\"images/smicon_viewall.gif\">', 'escrow_view.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES(112, 32, 1, 'Settings Manager', '<img src=\"images/smicon_config.gif\">', 'settings.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES(113, 30, 90, 'Usergroups', '', '', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES(114, 10, 113, 'Add Usergroup', '<img src=\"images/smicon_addrule.gif\">', 'usergroup_add.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES(115, 20, 113, 'Edit Usergroup', '<img src=\"images/smicon_editrule.gif\">', 'usergroup_edit.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES(116, 9, 40, 'Upgrades', '', '', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES(117, 10, 116, 'Add Upgrade', '<img src=\"images/smicon_addrule.gif\">', 'upgrades_add.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES(118, 20, 116, 'Edit Upgrade', '<img src=\"images/smicon_editrule.gif\">', 'upgrades_edit.php', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES(119, 10, 40, 'Subscribers', '', '', '', '');");
	mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."amenu` VALUES(120, 20, 116, 'View Subscribers', '<img src=\"images/smicon_editrule.gif\">', 'subscriptions_view.php', '', '');");
	echo "success<br>";
	
	echo "Update Categories Table... \t\t";
	@mysql_query("ALTER TABLE `".addslashes($config['db']['pre'])."categories` CHANGE `cat_id` `cat_id` MEDIUMINT( 8 ) UNSIGNED NOT NULL AUTO_INCREMENT ") or install_error('ERROR ('.mysql_error().')');
	echo "success<br>";
	
	// Change the bids table
	echo "Updating The Bids Table...  \t\t";
	@mysql_query("ALTER TABLE `".addslashes($config['db']['pre'])."bids` ADD `file_id` INT( 11 ) UNSIGNED NOT NULL ") or install_error('ERROR ('.mysql_error().')');
	echo "success<br>";
	
	echo "Adding the block Table...  \t\t";
	@mysql_query("CREATE TABLE `".addslashes($config['db']['pre'])."block` (  `block_id` int(11) unsigned NOT NULL auto_increment,  `block_type` char(3) NOT NULL default '',  `user_id` int(11) unsigned NOT NULL default '0',  `user_id2` int(11) unsigned NOT NULL default '0',  PRIMARY KEY  (`block_id`),  UNIQUE KEY `user_id` (`user_id`,`user_id2`))");
	echo "success<br>";
	
	echo "Updating the buyers Table...  \t\t";
	@mysql_query("ALTER TABLE `".addslashes($config['db']['pre'])."buyers` ADD `buyer_custom_fields` MEDIUMTEXT NOT NULL ,ADD `buyer_custom_values` MEDIUMTEXT NOT NULL;");
	echo "success<br>";

	echo "Updating the providers Table...  \t\t";
	@mysql_query("ALTER TABLE `".addslashes($config['db']['pre'])."providers` ADD `provider_custom_fields` MEDIUMTEXT NOT NULL ,ADD `provider_custom_values` MEDIUMTEXT NOT NULL;");
	echo "success<br>";
	
	echo "Updated the html Table...  \t\t";
	@mysql_query("ALTER TABLE `".addslashes($config['db']['pre'])."html` ADD `html_type` SMALLINT( 2 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `html_id` ;");
	echo "success<br>";

	echo "Updated the settings Table...  \t\t";
	@mysql_query("ALTER TABLE `".addslashes($config['db']['pre'])."settings` ADD `group_id` INT( 11 ) UNSIGNED NOT NULL AFTER `setting_id` ;");
	echo "success<br>";

	echo "Add Settings Group Table...  \t\t";
	@mysql_query("CREATE TABLE `".addslashes($config['db']['pre'])."settings_groups` (  `group_id` int(11) unsigned NOT NULL auto_increment,  `group_name` varchar(50) NOT NULL default '',  PRIMARY KEY  (`group_id`))");
	@mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."settings_groups` VALUES (2, 'Bidding');");
	@mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."settings_groups` VALUES (3, 'Front Page');");
	@mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."settings_groups` VALUES (4, 'Transactions');");
	@mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."settings_groups` VALUES (5, 'Search');");
	echo "success<br>";
	
	echo "Insert Settings...  \t\t";
	@mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."settings` VALUES(2, 2, 'bid.php', 'Allow Bid Attachments', 'bid_attach', 'select', '1|Yes,0|No', '1', 1);");
	@mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."settings` VALUES(3, 3, 'index.php', 'Project Start Date Format', 'index_project_start_date', 'textfield', '', 'n/j/Y', 1);");
	@mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."settings` VALUES(4, 3, 'index.php', 'Project End Date Format', 'index_project_end_date', 'textfield', '', 'n/j/Y', 1);");
	@mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."settings` VALUES(5, 4, 'transactions.php', 'Transaction Date Format', 'trans_date_format', 'textfield', '', 'n/j/Y', 1 ) ;");
	@mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."settings` VALUES (6, 4, 'escrow.php', 'Allow Provider To Escrow', 'allow_provider_escrow', 'select', '1|Yes,0|No', '0', 1);");
	@mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."settings` VALUES (7, 5, 'search.php', 'Search Mode', 'search_mode', 'select', '1|Basic,2|Full-Text', '1', '1');");
	@mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."settings` VALUES (8, 6, '', 'Urgent Time', 'urgent_time', 'textfield', '', '86400', 1);");
	echo "success<br>";
	
	echo "Add Search Index... \t\t";
	@mysql_query("ALTER TABLE `".addslashes($config['db']['pre'])."projects` ADD FULLTEXT (`project_title` ,`project_desc`)");
	echo "success<br>";

	echo "Add Category Order... \t\t";
	@mysql_query("ALTER TABLE `".addslashes($config['db']['pre'])."categories` ADD `cat_order` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0';");
	echo "success<br>";
	
	echo "Add Online Fields...  \t\t";
	@mysql_query("ALTER TABLE `".addslashes($config['db']['pre'])."buyers` ADD `buyer_online` TINYINT( 1 ) UNSIGNED NOT NULL ,ADD `buyer_lastactive` INT( 11 ) UNSIGNED NOT NULL ;");
	@mysql_query("ALTER TABLE `".addslashes($config['db']['pre'])."providers` ADD `provider_online` TINYINT( 1 ) UNSIGNED NOT NULL ,ADD `provider_lastactive` INT( 11 ) UNSIGNED NOT NULL ;");
	echo "success<br>";
	
	echo "Add Subscription Table...  \t\t";
	@mysql_query("CREATE TABLE `".addslashes($config['db']['pre'])."subscriptions` (  `sub_id` int(11) unsigned NOT NULL auto_increment,  `sub_title` varchar(100) NOT NULL default '',  `group_id` int(11) unsigned NOT NULL default '0',  `sub_term` varchar(10) NOT NULL default 'MONTHLY',  `sub_amount` double(8,2) NOT NULL default '0.00',  `sub_account` tinyint(1) unsigned NOT NULL default '1',  `sub_type` char(3) NOT NULL default '',  PRIMARY KEY  (`sub_id`))");
	echo "success<br>";
	
	echo "Update Payments Table...  \t\t";
	@mysql_query("ALTER TABLE `".addslashes($config['db']['pre'])."payments` ADD `payment_subscription` ENUM( '0', '1' ) NOT NULL AFTER `payment_withdraw`;");
	@mysql_query("ALTER TABLE `".addslashes($config['db']['pre'])."payments` ADD `payment_desc_subscription` MEDIUMTEXT NOT NULL AFTER `payment_desc_withdraw` ;");
	@mysql_query("UPDATE `".addslashes($config['db']['pre'])."payments` SET `payment_subscription` = '1' WHERE `payment_id` = 1 LIMIT 1 ;");
	echo "success<br>";

	echo "Add Category Count Field... \t\t";
	@mysql_query("ALTER TABLE `".addslashes($config['db']['pre'])."categories` ADD `cat_count` MEDIUMINT( 8 ) UNSIGNED NOT NULL ;");
	echo "success<br>";
	
	echo "Add Settings...  \t\t";
	@mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."settings` VALUES (8, 6, '', 'Urgent Time', 'urgent_time', 'textfield', '', '86400', 1);");
	@mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."settings` VALUES (9, 0, 'cron.php', 'Keep Online Time', 'keep_online_time', 'textfield', '', '900', 0);");
	@mysql_query("INSERT INTO `".addslashes($config['db']['pre'])."settings_groups` (`group_id` ,`group_name`) VALUES ('6', 'Misc');");
	echo "success<br>";
	
	echo "Update Cheque Plugin...  \t\t";
	@mysql_query('UPDATE `'.addslashes($config['db']['pre']).'payments` SET `payment_settings` = \'a:10:{s:13:"withdraw_link";s:249:"echo "<a href=\\\\"#\\\\" onclick=\\\\"javascript:window.open(\'\'print_trans_info.php?id=$tran_id\'\',\'\'ZoomImage\'\',\'\'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,menubar=no,width=550,height=400\'\');\\\\">Click here to pay</a>";";s:12:"deposit_cost";s:0:"";s:18:"deposit_percentage";s:0:"";s:10:"Payable_To";s:0:"";s:9:"Address_1";s:0:"";s:9:"Address_2";s:0:"";s:4:"City";s:0:"";s:5:"State";s:0:"";s:13:"Post/Zip_Code";s:0:"";s:7:"Country";s:0:"";}\' WHERE `lance_payments`.`payment_id` = 3 LIMIT 1;');
	echo "success<br>";
	
	echo "Add Upgrades Table...  \t\t";
	@mysql_query("CREATE TABLE `".addslashes($config['db']['pre'])."upgrades` (  `upgrade_id` int(11) unsigned NOT NULL auto_increment,  `sub_id` int(11) unsigned NOT NULL default '0',  `user_type` varchar(15) NOT NULL default '',  `user_id` int(11) unsigned NOT NULL default '0',  `upgrade_lasttime` int(11) unsigned NOT NULL default '0',  `upgrade_expires` int(11) unsigned NOT NULL default '0',  PRIMARY KEY  (`upgrade_id`))");
	echo "success<br>";	
	
	echo "Modify Usergroups Table...  \t\t";
	@mysql_query("ALTER TABLE `".addslashes($config['db']['pre'])."usergroups` ADD `post_project_discount` DOUBLE( 8, 2 ) NOT NULL , ADD `post_featured_discount` DOUBLE( 8, 2 ) NOT NULL ,ADD `post_job_discount` DOUBLE( 8, 2 ) NOT NULL ;");
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
	$content.= "\$config['escrow_en'] = '0';\n";
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
	$content.= "\$config['temp_php'] = '0';\n";
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