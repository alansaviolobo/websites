<?php
require_once('includes/config.php');
require_once('includes/classes/class.template_engine.php');
require_once('includes/functions/func.global.php');
require_once('includes/functions/func.cron.php');
require_once('includes/classes/class.phpmailer.php');
require_once('includes/lang/lang_'.$config['lang'].'.php');

db_connect($config);

// Get Settings
$settings = get_settings('cron.php',$config);

ignore_user_abort(1);
@set_time_limit(0);

$filedata = base64_decode('R0lGODlhAQABAIAAAMDAwAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==');
$filesize = strlen($filedata);

header('Content-type: image/gif');
header('Content-Length: ' . $filesize);
header('Connection: Close');

echo $filedata;

if((time()-300) > $settings['cron_time'])
{
	flush();
}
else
{
	exit;
}

$start_time = time();

update_setting($config,'cron_time',time());

// START - CLOSE OLD PROJECTS
$projects_closed = mysql_num_rows(mysql_query("SELECT 1 FROM `".$config['db']['pre']."projects` WHERE `project_end` < '" . time() . "' AND project_status='0' LIMIT 1"));
mysql_query("UPDATE `".$config['db']['pre']."projects` SET `project_status` = '1' WHERE `project_end` < '" . time() . "' AND project_status='0' LIMIT 1;");
// END - CLOSE OLD PROJECTS

// START UPDATE ONLINE STATUS
mysql_query("UPDATE `".$config['db']['pre']."providers` SET `provider_online` = '0' WHERE `provider_online` = '1' AND `provider_lastactive` < '".validate_input(time()-$settings['keep_online_time'])."';");
mysql_query("UPDATE `".$config['db']['pre']."buyers` SET `buyer_online` = '0' WHERE `buyer_online` = '1' AND `buyer_lastactive` < '".validate_input(time()-$settings['keep_online_time'])."';");
// END UPDATE ONLINE STATUS

// START REMOVE OLD PENDING TRANSACTIONS
mysql_query("DELETE FROM `".$config['db']['pre']."transactions` WHERE `transaction_time`< '".validate_input(time()-259200)."' AND transaction_status='1' LIMIT 30");
// END REMOVE OLD PENDING TRANSACTIONS

// START REMOVE EXPIRED UPGRADES
$query = "SELECT upgrade_id,user_id,user_type FROM ".$config['db']['pre']."upgrades WHERE `upgrade_expires`< '".validate_input(time()-43200)."'";
$query_result = mysql_query($query);
while ($info = @mysql_fetch_array($query_result))
{
	if($info['user_type'] == 'buyer')
	{
		mysql_query("UPDATE `".$config['db']['pre']."buyers` SET `group_id` = '1' WHERE `buyer_id` = '".validate_input($info['user_id'])."' LIMIT 1 ;");
	}
	else
	{
		mysql_query("UPDATE `".$config['db']['pre']."providers` SET `group_id` = '1' WHERE `provider_id` = '".validate_input($info['user_id'])."' LIMIT 1 ;");
	}

	mysql_query("DELETE FROM `".$config['db']['pre']."upgrades` WHERE `upgrade_id`= '".validate_input($info['upgrade_id'])."' LIMIT 1");
}
// END REMOVE EXPIRED UPGRADES

$email_q = 0;
$pemail_project = 0;

$query = "SELECT project_id,project_title,project_types FROM ".$config['db']['pre']."projects WHERE project_emailed='0' AND project_status='0'";
$query_result = mysql_query($query);
while ($info = @mysql_fetch_array($query_result))
{
	mysql_query("UPDATE `".$config['db']['pre']."projects` SET `project_emailed` = '1' WHERE `project_id` = '" . $info['project_id'] . "' LIMIT 1;");
	
	$where = '';
	$cats = explode(',',$info['project_types']);
	$qemails = array();
	$pemail_project++;
	
	foreach ($cats as $key => $value)
	{
		if($value != '')
		{
			if($where == '')
			{
				$where = "cat_id='".$value."'";
			}
			else
			{
				$where.= " OR cat_id='".$value."'";
			}
		}
	}
	
	if($where)
	{
		$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/email_project_notification.html");
		$page->SetParameter ('ID', $info['project_id']);
		$page->SetParameter ('SITE_URL', $config['site_url']);
		$page->SetParameter ('SITE_TITLE', stripslashes($config['site_title']));
		$page->SetParameter ('PROJECT_TITLE', $info['project_title']);
		$email_body = $page->CreatePageReturn($lang,$config);
	
		$query2 = "SELECT user_id,user_email FROM ".$config['db']['pre']."notification WHERE ".$where;
		$query_result2 = mysql_query($query2);
		while ($info2 = @mysql_fetch_array($query_result2))
		{
			$qemails[$info2['user_id']] = $info2['user_email'];
		}

		foreach ($qemails as $key => $value)
		{
			mysql_query("INSERT INTO `".$config['db']['pre']."emailq` ( `q_id` , `email` , `subject` , `body` ) VALUES ('', '".validate_input($value)."', '".$config['site_title'].$lang['PROJNOTICE']."', '".$email_body."');");
		
			$email_q++;
		}
	}
}

$emails_sent = 0;

$query = "SELECT q_id,email,subject,body FROM ".$config['db']['pre']."emailq ORDER BY q_id ASC LIMIT 60";
$query_result = mysql_query($query);
while ($info = @mysql_fetch_array($query_result))
{
	email($info['email'],$info['subject'],$info['body'],$config);
	
	mysql_query("DELETE FROM `".$config['db']['pre']."emailq` WHERE `q_id` = ".$info['q_id']." LIMIT 1");
	
	$emails_sent++;
}

$end_time = (time()-$start_time);

$cron_details = "Projects closed: ".$projects_closed."<br>";
$cron_details.= "New projects: ".$pemail_project."<br>";
$cron_details.= "Emails added to the queue: ".$email_q."<br>";
$cron_details.= "Emails sent: ".$emails_sent."<br>";
$cron_details.= "<br>";
$cron_details.= "Cron Took: ".$end_time." seconds";

log_adm_action($config,'Cron Run',$cron_details);
?>