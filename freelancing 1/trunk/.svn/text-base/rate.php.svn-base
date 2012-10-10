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

if(isset($_POST['id']))
{
	$_GET['id'] = $_POST['id'];
}

if(checkloggedin())
{
	if($_SESSION['user']['type'] == 'provider')
	{
		$query = "SELECT buyer_id,project_title FROM `".$config['db']['pre']."projects` WHERE buyer_rated='0' AND project_id='" . validate_input($_GET['id']) . "' AND provider_id='" . $_SESSION['user']['id'] . "' AND project_status='2' LIMIT 1";
		$query_result = @mysql_query ($query) OR error(mysql_error());
		if(mysql_num_rows($query_result)==0)
		{
			message($lang['INVRATCODE'], $config, $lang);
		}
		while ($info = @mysql_fetch_array($query_result))
		{
			$project_title = strip_tags(stripslashes(substr($info['project_title'],0,25)));
			$userid = $info['buyer_id'];
		}
		
		$query = "SELECT buyer_username,buyer_reviews,buyer_rating FROM `".$config['db']['pre']."buyers` WHERE buyer_id='" . $userid . "' LIMIT 1";
		$query_result = @mysql_query ($query) OR error(mysql_error());
		while ($info = @mysql_fetch_array($query_result))
		{
			$username = $info['buyer_username'];
			$reviews = $info['buyer_reviews'] + 1;
			$current_rating = $info['buyer_rating'];
		}
		
		$type = 'buyer';
	}
	else
	{
		$query = "SELECT provider_id,project_title FROM `".$config['db']['pre']."projects` WHERE provider_rated='0' AND project_id='" . validate_input($_GET['id']) . "' AND buyer_id='" . $_SESSION['user']['id'] . "' AND project_status='2' LIMIT 1";
		$query_result = @mysql_query ($query) OR error(mysql_error());
		if(mysql_num_rows($query_result)==0)
		{
			message($lang['INVRATCODE'], $config, $lang);
		}
		while ($info = @mysql_fetch_array($query_result))
		{
			$project_title = strip_tags(stripslashes(substr($info['project_title'],0,25)));
			$userid = $info['provider_id'];
		}
		
		$query = "SELECT provider_username,provider_reviews,provider_rating FROM `".$config['db']['pre']."providers` WHERE provider_id='" . $userid . "' LIMIT 1";
		$query_result = @mysql_query ($query) OR error(mysql_error());
		while ($info = @mysql_fetch_array($query_result))
		{
			$username = $info['provider_username'];
			$reviews = $info['provider_reviews'] + 1;
			$current_rating = $info['provider_rating'];
		}
	
		$type = 'provider';
	}
	if(isset($_POST['Submit']))
	{
		if($_SESSION['user']['type'] == 'provider')
		{
			if($current_rating == 0)
			{
				$rating = $_POST['rate'];
			}
			else
			{
				$rating = ($current_rating + $_POST['rate']) / 2;
			}
			
			mysql_query("UPDATE `".$config['db']['pre']."buyers` SET `buyer_rating` = '" . $rating . "',`buyer_reviews` = '" . $reviews . "' WHERE `buyer_id` = '" . $userid . "' LIMIT 1 ;");
			mysql_query("UPDATE `".$config['db']['pre']."projects` SET `buyer_rated` = '1' WHERE `project_id` = '" . validate_input($_POST['id']) . "' LIMIT 1 ;");
			mysql_query("INSERT INTO `".$config['db']['pre']."reviews` ( `review_id` , `review_comment` , `review_rating` , `review_time` , `review_type` , `project_id` , `buyer_id` , `provider_id` ) VALUES ('', '" . addslashes($_POST['comment']) . "', '" . addslashes($_POST['rate']) . "', '" . time() .  "', '1', '" . validate_input($_GET['id']) . "', '" . validate_input($userid) . "', '" . $_SESSION['user']['id'] . "');");
		}
		else
		{
			if($current_rating == 0)
			{
				$rating = $_POST['rate'];
			}
			else
			{
				$rating = ($current_rating + $_POST['rate']) / 2;
			}
			
			mysql_query("UPDATE `".$config['db']['pre']."providers` SET `provider_rating` = '" . validate_input($rating) . "',`provider_reviews` = '" . validate_input($reviews) . "' WHERE `provider_id` = '" . $userid . "' LIMIT 1 ;");
			mysql_query("UPDATE `".$config['db']['pre']."projects` SET `provider_rated` = '1' WHERE `project_id` = '" . validate_input($_POST['id']) . "' LIMIT 1 ;");
			mysql_query("INSERT INTO `".$config['db']['pre']."reviews` ( `review_id` , `review_comment` , `review_rating` , `review_time` , `review_type` , `project_id` , `buyer_id` , `provider_id` ) VALUES ('', '" . validate_input($_POST['comment']) . "', '" . validate_input($_POST['rate']) . "', '" . time() .  "', '0', '" . validate_input($_GET['id']) . "', '" . $_SESSION['user']['id'] . "', '" . validate_input($userid) . "');");
		}
		
		message($lang['THANKRATING'] . ' ' . $username, $config, $lang,'manage.php');
	}
	else
	{
		$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/rate.html");
		$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang));
		$page->SetParameter ('TYPE', $type);
		$page->SetParameter ('USERNAME', $username);
		$page->SetParameter ('ID', $_GET['id']);
		$page->SetParameter ('PROJECT_TITLE', $project_title);
		$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
		$page->CreatePageEcho($lang,$config);
	}
}
?>