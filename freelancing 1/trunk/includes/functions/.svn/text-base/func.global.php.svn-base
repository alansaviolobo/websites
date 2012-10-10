<?php
function create_header($config,$lang,$page_title='')
{
	$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/overall_header.html");
	$page->SetParameter('SITE_TITLE', $config['site_title']);
	$page->SetParameter('PAGE_TITLE', $page_title);
	$page->SetParameter('TPL_NAME', $config['tpl_name']);
	$page->SetParameter('SITE_URL', $config['site_url']);
	$page->SetParameter('MAILBOX_EN', $config['mailbox_en']);
	
	if(isset($_SESSION['user']['id']))
	{
		$page->SetParameter('LOGGED_IN', 1);
		$page->SetParameter('USER_TYPE',$_SESSION['user']['type']);
		
		if($config['mailbox_en'])
		{
			$page->SetParameter('MAILBOX_SHOW',1);
		}
		else
		{
			$page->SetParameter('MAILBOX_SHOW',0);
		}
	}
	else
	{
		$page->SetParameter('LOGGED_IN', 0);
		$page->SetParameter('USER_TYPE','');
		$page->SetParameter('MAILBOX_SHOW',0);
	}
	
	return $page->CreatePageReturn($lang,$config);
}

function create_footer($config,$lang)
{
	$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/overall_footer.html");
	$page->SetParameter('VERSION',$config['version']);
	return $page->CreatePageReturn($lang,$config);
}

function db_connect($config)
{
    $db_connection = mysql_connect ($config['db']['host'], $config['db']['user'], $config['db']['pass']) OR error (mysql_error(), __LINE__, __FILE__, 0, '', '');
    $db_select = mysql_select_db ($config['db']['name']) or error (mysql_error(), __LINE__, __FILE__, 0, '', '');
	
	return $db_connection;
}

function error($msg, $line='', $file='', $formatted=0,$lang=array(),$config=array())
{
	if($formatted == 0)
	{
		echo "Low Level Error: " . $msg;
	}
	else
	{
		if(!isset($lang['ERROR']))
		{
			$lang['ERROR'] = '';
		}
	
		$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/error.html");
		$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['ERROR']));
		$page->SetParameter ('MESSAGE', $msg);
		$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
		$page->CreatePageEcho($lang,$config);
	}
	exit;
}

function get_job_types($config,$selected=array())
{
	$query = "SELECT cat_id,cat_title FROM ".$config['db']['pre']."jobs_categories ORDER BY cat_title";
	$query_result = mysql_query($query);
	while ($info = mysql_fetch_array($query_result))
	{
		$jobtypes[$info['cat_id']]['title'] = $info['cat_title'];
		$jobtypes[$info['cat_id']]['id'] = $info['cat_id'];
		if($selected!="")
		{
			foreach($selected as $select)
			{
				if($select==$info['cat_id'])
				{
					$jobtypes[$info['cat_id']]['selected'] = "checked ";
				}
			}
		}
	}

	return $jobtypes;
}
function get_subjob_types($selected="",$cat_id)
{
	$jobtypes=array();
	$query = "SELECT * FROM ".$config['db']['pre']."jobs_subcategories WHERE cat_id=".$cat_id." ORDER BY subcat_title";
	$query_result = mysql_query($query);
	while ($info = mysql_fetch_array($query_result))
	{
		$jobtypes[$info['csubat_id']]['title'] = $info['subcat_title'];
		$jobtypes[$info['subcat_id']]['id'] = $info['subcat_id'];
		if($selected!="")
		{
			foreach($selected as $select)
			{
				if($select==$info['subcat_id'])
				{
					$jobtypes[$info['subcat_id']]['selected'] = "checked ";
				}
			}
		}
	}

	return $jobtypes;
}
function get_categories($config,$selected=array(),$selected_text='checked')
{
	$jobtypes = array();
	$jobtypes2 = array();
	$parents = array();

	$query = "SELECT * FROM ".$config['db']['pre']."categories ORDER BY cat_name";
	$query_result = mysql_query($query);
	while ($info = mysql_fetch_array($query_result))
	{
		if(!isset($info['parent_id']))
		{
			$info['parent_id'] = 0;
		}
		else
		{
			if(isset($parents[$info['parent_id']]))
			{
				$parents[$info['parent_id']] = ($parents[$info['parent_id']]+1);
			}
			else
			{
				$parents[$info['parent_id']] = 1;
			}
		}
	
		$jobtypes[$info['parent_id']][$info['cat_id']]['title'] = stripslashes($info['cat_name']);
		$jobtypes[$info['parent_id']][$info['cat_id']]['id'] = $info['cat_id'];
		$jobtypes[$info['parent_id']][$info['cat_id']]['selected'] = '';
		$jobtypes[$info['parent_id']][$info['cat_id']]['parent_id'] = $info['parent_id'];
		$jobtypes[$info['parent_id']][$info['cat_id']]['catcount'] = 0;
		$jobtypes[$info['parent_id']][$info['cat_id']]['counter'] = 0;
		
		foreach($selected as $select)
		{
			if($select==$info['cat_id'])
			{
				$jobtypes[$info['parent_id']][$info['cat_id']]['selected'] = $selected_text;
			}
		}
	}
	
	foreach($jobtypes as $key=>$value)
	{
		foreach($value as $key2=>$value2)
		{
			if(isset($parents[$key2]))
			{
				$jobtypes[$key][$key2]['catcount']  = $parents[$key2];
			}
		}
	}
	
	$counter = 1;
	
	foreach($jobtypes[0] as $key=>$value)
	{
		$value['counter'] = $counter;
		if($value['catcount'])
		{
			$value['ctype'] = 1;
		}
		else
		{
			$value['ctype'] = 0;
		}
		
		$jobtypes2[$key] =  $value;
		$counter++;
		
		if(isset($jobtypes[$key]))
		{		
			foreach($jobtypes[$key] as $key2=>$value2)
			{
				$value2['counter'] = $counter;
				$value2['ctype'] = 2;
			
				$jobtypes2[$key2] =  $value2;
				
				$counter++;
			}
		}
	}	

	return $jobtypes2;
}

function message_content($message_content, $to_id, $from_id, $from_type, $sessions,$lang)
{
	$message_content = text_wrap($message_content,50);
	
	$types['buyer'] = 1;
	$types['provider'] = 0;

	if($to_id == 0)
	{
		return "<br>[".$lang['PUBMESS']."]<br><br>".$message_content;
	}
	else
	{
		if(isset($sessions['user']))
		{
			if($sessions['user']['type'] == 'buyer')
			{
				$type = 1;
				$opposite = 0;
			}
			else
			{
				$type = 0;
				$opposite = 1;
			}
			
			if( ( ($to_id == $sessions['user']['id']) AND ($from_type == $type) ) OR ( ($from_id == $sessions['user']['id']) AND ($from_type == $opposite) ) )
			{
				return '<br>'.$message_content;
			}
			else
			{
				return '['.$lang['PRIVMESSFOR'].' {MESSAGES.to_username}. <a href="login.php?ref=board.php%3Fi%3D{PROJECT_ID}">'.$lang['CLICKLOGIN'].'</a>]';
			}
		}
		else
		{
			return '['.$lang['PRIVMESSFOR'].' {MESSAGES.to_username}. <a href="login.php?ref=board.php%3Fi%3D{PROJECT_ID}">'.$lang['CLICKLOGIN'].'</a>]';
		}
	}
}

function get_feedback($creator_id,$type,$config)
{
	$feedbackarr = array();
	$reviews = 0;
	$rating = 0;

	$query = "SELECT " . $type . "_reviews," . $type . "_rating FROM `".$config['db']['pre'].$type . "s` WHERE " . $type . "_id='" . $creator_id . "' LIMIT 1";
	$query_result = mysql_query ($query) OR error(mysql_error());
	while ($info = mysql_fetch_array($query_result))
	{
		$reviews = $info['0'];
		$rating = $info['1'];
	}
	
	$feedbackarr['reviews'] = $reviews;
	$feedbackarr['rating'] = $rating;
	
	return $feedbackarr;	
}

function email($email_to,$email_subject,$email_body,$config,$bcc=array())
{
	$mail = new PHPMailer();

	$mail->CharSet="utf-8";
	
	if($config['email']['type'] == 'smtp')
	{
		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->Username = $config['email']['smtp']['user'];
		$mail->Password = $config['email']['smtp']['pass'];
		$mail->Host = $config['email']['smtp']['host'];
	}
	elseif ($config['email']['type'] == 'sendmail')
	{
		$mail->IsSendmail();
	}
	else
	{
		$mail->IsMail();
	}
	
	$mail->FromName = $config['site_title'];
	$mail->From = $config['admin_email'];
	
	if(count($bcc) > 0)
	{
		$counter = 0;
		
		foreach ($bcc as $value) 
		{
			if($counter == 0)
			{
				$mail->AddAddress($value);
			}
			else
			{
				$mail->AddBCC($value);
			}
			$counter++;
		}
	}
	else
	{
		$mail->AddAddress($email_to);
	}
	
	$mail->Subject = $email_subject;
	$mail->Body = $email_body;
	
	$mail->IsHTML(false);
	
	$mail->Send();
}

function get_random_quote($config)
{
	$quote[0] = '';
	$quote[1] = '';

	$query = "SELECT quote_comment,quote_author FROM `".$config['db']['pre']."quotes` ORDER BY RAND() LIMIT 1";
	$query_result = mysql_query ($query) OR error(mysql_error());
	while ($info = mysql_fetch_array($query_result))
	{
		$quote[0] = stripslashes($info['quote_comment']);
		$quote[1] = stripslashes($info['quote_author']);
	}
	
	return $quote;
}

function message($message,$config,$lang,$forward='',$back=true)
{
	if($forward == '')
	{
		if($back)
		{
			$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/message.html");
			$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['MESSAGE']));
			$page->SetParameter ('MESSAGE', $message);
			$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
			$page->CreatePageEcho($lang,$config);
		}
		else
		{
			$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/message_noback.html");
			$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['MESSAGE']));
			$page->SetParameter ('MESSAGE', $message);
			$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
			$page->CreatePageEcho($lang,$config);
		}
	}
	else
	{
		$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/message_forward.html");
		$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['MESSAGE']));
		$page->SetParameter ('MESSAGE', $message);
		$page->SetParameter ('FORWARD', $forward);
		$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
		$page->CreatePageEcho($lang,$config);
	}
	exit;
}

function check_balance($type, $id, $amount,$config)
{
	$query = "SELECT balance_amount FROM `".$config['db']['pre'].$type . "s_balance` WHERE " . $type . "_id = '" . $id . "' LIMIT 1";
	$query_result = mysql_query($query);
	while ($info = mysql_fetch_array($query_result))
	{
		$balance = $info['balance_amount'];
	}

	$less = $balance - $amount;
	
	if($less >= 0)
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}

function minus_balance($type, $id, $amount,$config)
{
	$balance = 0;

	$query = "SELECT balance_amount FROM `".$config['db']['pre'].addslashes($type)."s_balance` WHERE " . addslashes($type) . "_id = '" . addslashes($id) . "' LIMIT 1";
	$query_result = mysql_query($query);
	while ($info = @mysql_fetch_array($query_result))
	{
		$balance = $info['balance_amount'];
	}

	$less = $balance - $amount;
	
	mysql_query("UPDATE `".$config['db']['pre'].addslashes($type). "s_balance` SET `balance_amount` = '" . $less . "' WHERE " . addslashes($type) . "_id = '" . addslashes($id) . "' LIMIT 1 ;");
}

function charge_comission($config,$bid_price,$buyer_id,$provider_id)
{
	if($config['provider_com'])
	{
		$comission = (($bid_price/100)*$config['provider_com']);
	
		minus_balance('provider', $provider_id, $comission,$config);
		mysql_query("INSERT INTO `".$config['db']['pre']."transactions` ( `transaction_id` , `transaction_type` , `transaction_method` , `buyer_id` , `provider_id` , `transaction_ip` , `transaction_time` , `transaction_amount` , `transaction_description` , `transaction_proc` , `transaction_settings` ) VALUES ('', 'adm', 'withdraw', '', '".$provider_id."', '" . encode_ip($_SERVER,$_ENV) . "', '" . time() . "', '" . $comission . "', 'Project Comission', '', '');");
	}
	if($config['provider_fee'])
	{
		minus_balance('provider', $provider_id, $config['provider_fee'],$config);
		mysql_query("INSERT INTO `".$config['db']['pre']."transactions` ( `transaction_id` , `transaction_type` , `transaction_method` , `buyer_id` , `provider_id` , `transaction_ip` , `transaction_time` , `transaction_amount` , `transaction_description` , `transaction_proc` , `transaction_settings` ) VALUES ('', 'adm', 'withdraw', '', '".$provider_id."', '" . encode_ip($_SERVER,$_ENV) . "', '" . time() . "', '" . $config['provider_fee'] . "', 'Project Fee', '', '');");
	}
	if($config['buyer_com'])
	{
		$comission = (($bid_price/100)*$config['buyer_com']);
	
		minus_balance('buyer', $buyer_id, $comission,$config);
		mysql_query("INSERT INTO `".$config['db']['pre']."transactions` ( `transaction_id` , `transaction_type` , `transaction_method` , `buyer_id` , `provider_id` , `transaction_ip` , `transaction_time` , `transaction_amount` , `transaction_description` , `transaction_proc` , `transaction_settings` ) VALUES ('', 'adm', 'withdraw', '" . $buyer_id . "', '', '" . encode_ip($_SERVER,$_ENV) . "', '" . time() . "', '" . $comission . "', 'Project Comission', '', '');");
	}
	if($config['buyer_fee'])
	{
		minus_balance('buyer', $buyer_id, $config['buyer_fee'],$config);
		mysql_query("INSERT INTO `".$config['db']['pre']."transactions` ( `transaction_id` , `transaction_type` , `transaction_method` , `buyer_id` , `provider_id` , `transaction_ip` , `transaction_time` , `transaction_amount` , `transaction_description` , `transaction_proc` , `transaction_settings` ) VALUES ('', 'adm', 'withdraw', '" . $buyer_id . "', '', '" . encode_ip($_SERVER,$_ENV) . "', '" . time() . "', '" . $config['buyer_fee'] . "', 'Project Fee', '', '');");
	}
}

function checkinstall($config)
{
	if(!isset($config['installed']))
	{
		header("Location: install/");
		exit;
	}
}

function transfer($config,$url,$msg)
{
	if(!$config['transfer_filter'])
	{
		header("Location: ".$url);
		exit;
	}

	ob_start();
	echo "<html>\n";
	echo "<head>\n";
	echo "<title><?php echo $config['site_title']; ?> Admin</title>\n";
	echo "<STYLE>\n";
	echo "<!--\n";
	echo "TABLE, TR, TD                { font-family:Verdana, Tahoma, Arial;font-size: 7.5pt; color:#000000}\n";
	echo "a:link, a:visited, a:active  { text-decoration:underline; color:#000000 }\n";
	echo "a:hover                      { color:#465584 }\n";
	echo "#alt1   { background-color: #EFEFEF  }\n";
	echo "body {\n";
	echo "	background-color: #FFFFFF;\n";
	echo "}\n";
	echo "-->\n";
	echo "</STYLE>\n";
	echo "<script language=\"JavaScript\" type=\"text/javascript\">\n";
	echo "function changeurl(){\n";
	echo "window.location='" . $url . "';\n";
	echo "}\n";
	echo "</script>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"></head>\n";
	echo "<body onload=\"window.setTimeout('changeurl();',2000);\">\n";
	echo "<table width='95%' height='85%'>\n";
	echo "<tr>\n";
	echo "<td valign='middle'>\n";
	echo "<table align='center' border=\"0\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"#FF9900\">\n";
	echo "<tr>\n";
	echo "<td id='mainbg'>";
	echo "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"12\">\n";
	echo "<tr>\n";
	echo "<td width=\"100%\" align=\"center\" id=alt1>\n";
	echo $msg . "<br><br>\n";
	echo "Please wait while we transfer you...<br><br>\n";
	echo "(<a href='" . $url . "'>Or click here if you do not wish to wait</a>)</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</body></html>\n";
	ob_end_flush();
}

function encode_ip($_SERVER,$_ENV)
{
	if( getenv('HTTP_X_FORWARDED_FOR') != '' )
	{
		$client_ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : ( ( !empty($HTTP_ENV_VARS['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR'] : $REMOTE_ADDR );
	
		$entries = explode(',', getenv('HTTP_X_FORWARDED_FOR'));
		reset($entries);
		while (list(, $entry) = each($entries)) 
		{
			$entry = trim($entry);
			if ( preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list) )
			{
				$private_ip = array('/^0\./', '/^127\.0\.0\.1/', '/^192\.168\..*/', '/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/', '/^10\..*/', '/^224\..*/', '/^240\..*/');
				$found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);
	
				if ($client_ip != $found_ip)
				{
					$client_ip = $found_ip;
					break;
				}
			}
		}
	}
	else
	{
		$client_ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : ( ( !empty($_ENV['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR'] : $REMOTE_ADDR );
	}
	
	return $client_ip;
}

function check_account_frozen($user_id, $user_type,$config)
{
	$status = '';
	
	if($user_type == 'buyer')
	{
		$query = "SELECT buyer_status,buyer_frozenreason FROM ".$config['db']['pre']."buyers WHERE buyer_id='" . $user_id . "' LIMIT 1";
		$query_result = mysql_query($query);
		while ($info = mysql_fetch_array($query_result))
		{
			$status = $info['buyer_status'];
			$reason = $info['buyer_frozenreason'];
		}
	}
	else
	{
		$query = "SELECT provider_status,provider_frozenreason FROM ".$config['db']['pre']."providers WHERE provider_id='" . $user_id . "' LIMIT 1";
		$query_result = mysql_query($query);
		while ($info = mysql_fetch_array($query_result))
		{
			$status = $info['provider_status'];
			$reason = $info['provider_frozenreason'];
		}
	}
	
	if($status == 2)
	{
		return $reason;
	}
	else
	{
		return FALSE;
	}
}

function check_cookie($_SESSION,$config)
{

}

function get_job_cats($config)
{
	$cats = array();
	$count = 0;

	$query = "SELECT cat_id,cat_title FROM ".$config['db']['pre']."jobs_categories ORDER BY cat_title";
	$query_result = mysql_query($query);
	while ($info = mysql_fetch_array($query_result))
	{
		$cats[$count]['id'] = $info['cat_id'];
		$cats[$count]['title'] = $info['cat_title'];
		
		$count++;
	}
	
	return $cats;
}

function get_job_types2($config)
{
	$types = array();
	$count = 0;

	$query = "SELECT type_id,type_title FROM ".$config['db']['pre']."jobs_types ORDER BY type_title";
	$query_result = mysql_query($query);
	while ($info = mysql_fetch_array($query_result))
	{
		$types[$count]['id'] = $info['type_id'];
		$types[$count]['title'] = $info['type_title'];
		
		$count++;
	}
	
	return $types;
}

function get_country_list($config)
{
	$countries = array();
	$count = 0;

	$query = "SELECT printable_name FROM ".$config['db']['pre']."countries ORDER BY printable_name";
	$query_result = mysql_query($query);
	while ($info = mysql_fetch_array($query_result))
	{
		$countries[$count]['title'] = $info['printable_name'];
		
		$count++;
	}
	
	return $countries;
}

function calculate_avg_bid($config,$project_id,$amount='')
{
	$full_amount = 0;

	$query = "SELECT bid_amount FROM ".$config['db']['pre']."bids WHERE project_id='" . $project_id . "'";
	$query_result = mysql_query($query);
	$num_rows = mysql_num_rows($query_result);
	while ($info = mysql_fetch_array($query_result))
	{
		$full_amount = $full_amount + $info['bid_amount'];
	}
	if($num_rows == 0)
	{
		$avg_bid = $amount;
	}
	else
	{
		$avg_bid = $full_amount / $num_rows;
	}
	
	return $avg_bid;
}

function sendnotifications($config,$lang)
{
	$projects = array();

	$query = "SELECT project_id,project_title,project_types FROM ".$config['db']['pre']."projects WHERE project_status='0' AND project_start>" . (time()-10800);
	$query_result = mysql_query($query);
	while ($info = mysql_fetch_array($query_result))
	{
		$types = explode(',',$info['project_types']);
		
		foreach($types as $value)
		{
			$projects[$value][$info['project_id']] = $info['project_title'];
		}
	}
	
	$query = "SELECT user_id,cat_id,user_email FROM ".$config['db']['pre']."notification";
	$query_result = mysql_query($query);
	while ($info = mysql_fetch_array($query_result))
	{
		$emails[$info['user_id']]['email'] = $info['user_email'];
		$emails[$info['user_id']][$info['cat_id']] = $info['cat_id'];
	}

	foreach($emails as $value)
	{
		$user_projects = array();

		foreach($value as $key2=>$value2)
		{
			if($key2 != 'email')
			{
				if(isset($projects[$value2]))
				{
					$user_projects = $projects[$value2];
				}
			}
		}
		
		$count_proj = count($user_projects);
			
		if($count_proj > 0)
		{	
			$email_body = "The following " . count($user_projects) . " projects were recently added to " . $config['site_title'] . " and fit under your expertise:\n\n";
			
			foreach($user_projects as $key2=>$value2)
			{
				$email_body.= $value2 . "\n";
				$email_body.= $config['site_url'] . 'project.php?id=' . $key2 . "\n\n";
			}
			
			email($value['email'],$lang['NEW'].' '.$config['site_title'] . ' '.$lang['PROJNOTICE'],$email_body,$config);
		}
	}
}

function time_taken($time)
{
	if($time > 86400)
	{
		$days = floor($time/86400);
		$hours = floor(($time-($days*86400))/3600);
		
		if($days > 1)
		{
			$took = $days . ' days';
		}
		else
		{
			$took = $days . ' day';
		}
	}
	elseif($time > 3600)
	{
		$hours = floor(($time/60)/60);
		$mins = floor(($time-($hours*3600))/60);
		
		if($hours > 1)
		{
			$took = $hours.' hours';
		}
		else
		{
			$took = $hours.' hour';
		}
	}
	elseif($time > 60)
	{
		$mins = floor($time/60);
	
		$took = $mins . ' minutes';
	}
	else
	{
		$took = $time . ' seconds';
	}
	
	return $took;
}

function getrandnum($length)
{
	$randstr=''; 
	srand((double)microtime()*1000000); 
	$chars = array ( 'a','b','C','D','e','f','G','h','i','J','k','L','m','N','P','Q','r','s','t','U','V','W','X','y','z','1','2','3','4','5','6','7','8','9'); 
	for ($rand = 0; $rand <= $length; $rand++) 
	{ 
		$random = rand(0, count($chars) -1); 
		$randstr .= $chars[$random]; 
	}
	
	return $randstr;
}

function pagenav($total,$page,$perpage,$url,$posts=0) 
{
	$page_arr = array();
	$arr_count = 0;

	if($posts) 
	{
		$symb='&';
	}
	else
	{
		$symb='?';
	}
	$total_pages = ceil($total/$perpage);
	$llimit = 1;
	$rlimit = $total_pages;
	$window = 5;
	$html = '';
	if ($page<1 || !$page) 
	{
		$page=1;
	}
	
	if(($page - floor($window/2)) <= 0)
	{
		$llimit = 1;
		if($window > $total_pages)
		{
			$rlimit = $total_pages;
		}
		else
		{
			$rlimit = $window;
		}
	}
	else
	{
		if(($page + floor($window/2)) > $total_pages) 
		{
			if ($total_pages - $window < 0)
			{
				$llimit = 1;
			}
			else
			{
				$llimit = $total_pages - $window + 1;
			}
			$rlimit = $total_pages;
		}
		else
		{
			$llimit = $page - floor($window/2);
			$rlimit = $page + floor($window/2);
		}
	}
	if ($page>1)
	{
		$page_arr[$arr_count]['title'] = 'Prev';
		$page_arr[$arr_count]['link'] = $url.$symb.'page='.($page-1);
		$page_arr[$arr_count]['current'] = 0;
		
		$arr_count++;
	}

	for ($x=$llimit;$x <= $rlimit;$x++) 
	{
		if ($x <> $page) 
		{
			$page_arr[$arr_count]['title'] = $x;
			$page_arr[$arr_count]['link'] = $url.$symb.'page='.($x);
			$page_arr[$arr_count]['current'] = 0;
		} 
		else 
		{
			$page_arr[$arr_count]['title'] = $x;
			$page_arr[$arr_count]['link'] = $url.$symb.'page='.($x);
			$page_arr[$arr_count]['current'] = 1;
		}
		
		$arr_count++;
	}
	
	if($page < $total_pages)
	{
		$page_arr[$arr_count]['title'] = 'Next';
		$page_arr[$arr_count]['link'] = $url.$symb.'page='.($page+1);
		$page_arr[$arr_count]['current'] = 0;
		
		$arr_count++;
	}
	
	return $page_arr;
}

function validate_input($input,$dbcon=true,$content='all',$maxchars=0)
{
	if(get_magic_quotes_gpc()) 
	{
		if(ini_get('magic_quotes_sybase')) 
		{
			$input = str_replace("''", "'", $input);
		} 
		else 
		{
			$input = stripslashes($input);
		}
	}
	
	if($content == 'alnum')
	{
		$input = ereg_replace("[^a-zA-Z0-9]", '', $input);
	}
	elseif($content == 'num')
	{
		$input = ereg_replace("[^0-9]", '', $input);
	}
	elseif($content == 'alpha')
	{
		$input = ereg_replace("[^a-zA-Z]", '', $input);
	}
	
	if($maxchars)
	{
		$input = substr($input,0,$maxchars);
	}

	if($dbcon)
	{
		$input = mysql_real_escape_string($input);
	}
	else
	{
		$input = mysql_escape_string($input);
	}
	
	return $input;
}

function check_negative_balance($config)
{
	if(isset($_SESSION['user']['id']))
	{
		$balance_info = mysql_fetch_row(mysql_query("SELECT balance_amount FROM `".$config['db']['pre'].$_SESSION['user']['type'] . "s_balance` WHERE " . $_SESSION['user']['type'] . "_id = '" . $_SESSION['user']['id'] . "' LIMIT 1"));
		
		if($balance_info[0] < 0)
		{
			header("Location: deposit.php?warning=1");
			exit;
		}
	}
}

function text_wrap($text,$maxlength)
{
	$txtarr=explode(" ",$text);
	$newtext=array();
	foreach($txtarr as $k=>$txt)
	{
		if (strlen($txt)>$maxlength)
		{
			$txt=wordwrap($txt, $maxlength, " ", 1);
		}
			
		$newtext[]=$txt;
	}
	return implode(" ",$newtext);
}

function convert2link($string)
{
	$string = strtolower($string);

	$special_chars[] = 'ö';
	$special_chars[] = 'ü';
	$special_chars[] = 'Ö';
	$special_chars[] = 'Ä';
	$special_chars[] = 'Ü';
	$special_chars[] = 'ä';
	$special_chars[] = 'ü';
	$special_chars[] = 'ö';
	$special_chars[] = 'ß';
	$special_chars[] = 'Ž';
	$special_chars[] = '?';
	$special_chars[] = '.';
	$special_chars[] = ':';
	$special_chars[] = ',';
	$special_chars[] = '_';
	$special_chars[] = '-';
	$special_chars[] = '+';
	$special_chars[] = '&';
	$special_chars[] = '/';
	$special_chars[] = '\\';
	$special_chars[] = ' ';
	$special_chars[] = '"';
	$special_chars[] = '#';

	$special_chars2[] = 'oe';
	$special_chars2[] = 'ue';
	$special_chars2[] = 'Oe';
	$special_chars2[] = 'Ae';
	$special_chars2[] = 'Ue';
	$special_chars2[] = 'ae';
	$special_chars2[] = 'ue';
	$special_chars2[] = 'oe';
	$special_chars2[] = 'ss';
	$special_chars2[] = 'z';
	$special_chars2[] = '';
	$special_chars2[] = '';
	$special_chars2[] = '_';
	$special_chars2[] = '';
	$special_chars2[] = '';
	$special_chars2[] = '_';
	$special_chars2[] = '_';
	$special_chars2[] = '';
	$special_chars2[] = '_';
	$special_chars2[] = '_';
	$special_chars2[] = '_';
	$special_chars2[] = '_';
	$special_chars2[] = '';

	$string = str_replace($special_chars,$special_chars2,$string);

	return $string;
}

function check_user_lang($config)
{
	if(isset($config['userlangsel']))
	{
		if($config['userlangsel'])
		{
			if(isset($_SESSION['user']['id']))
			{
				if(isset($_SESSION['user']['lang']))
				{
					if($_SESSION['user']['lang'] != '')
					{
						$config['lang'] = $_SESSION['user']['lang'];
					}
				}
			}
		}
	}
	
	return $config['lang'];
}

function check_user_group($config)
{
	$usergroup = 1;

	if(isset($_SESSION['user']['id']))
	{
		if(!isset($_SESSION['user']['group']))
		{
			if($_SESSION['user']['type'] == 'buyer')
			{
				$user_info = mysql_fetch_row(mysql_query("SELECT group_id FROM ".$config['db']['pre']."buyers WHERE buyer_id='".validate_input($_SESSION['user']['id'])."' LIMIT 1"));
			}
			else
			{
				$user_info = mysql_fetch_row(mysql_query("SELECT group_id FROM ".$config['db']['pre']."providers WHERE provider_id='".validate_input($_SESSION['user']['id'])."' LIMIT 1"));
			}
			
			if(isset($user_info[0]))
			{
				$usergroup = $user_info[0];
				
				$_SESSION['user']['group'] = $usergroup;
			}
		}
	}
	
	$group_info = array();	
	$group_info['post_project_discount'] = '0';
	$group_info['post_job_discount'] = '0';
	
	$group_get_info = mysql_fetch_array(mysql_query("SELECT * FROM ".$config['db']['pre']."usergroups WHERE group_id='".validate_input($usergroup)."' LIMIT 1"));
	
	if(isset($group_get_info['post_project_discount']))
	{
		$group_info = $group_get_info;
	}
	
	return $group_info;
}

function get_settings($page,$config)
{
	$settings = array();
	
	$query = "SELECT setting_id,setting_name,setting_value FROM ".$config['db']['pre']."settings WHERE setting_file='' OR setting_file='".validate_input($page)."'";
	$query_result = @mysql_query ($query) OR error(mysql_error(), __LINE__, __FILE__, 0, '', '');
	while ($info = @mysql_fetch_array($query_result))
	{
		$settings[$info['setting_name']] = $info['setting_value'];
	}
	
	return $settings;
}

function update_setting($config,$name,$value)
{
	mysql_query("UPDATE `".$config['db']['pre']."settings` SET `setting_value` = '".validate_input($value)."' WHERE `setting_name` = '".validate_input($name)."' LIMIT 1 ;");
}

function update_lastactive($config)
{
	if(isset($_SESSION['user']['id']))
	{
		if($_SESSION['user']['type'] == 'buyer')
		{
			mysql_query("UPDATE `".$config['db']['pre']."buyers` SET `buyer_online` = '1',`buyer_lastactive` = '".time()."' WHERE `buyer_id` = '".addslashes($_SESSION['user']['id'])."' LIMIT 1 ;");
		}
		else
		{
			mysql_query("UPDATE `".$config['db']['pre']."providers` SET `provider_online` = '1',`provider_lastactive` = '".time()."' WHERE `provider_id` = '".addslashes($_SESSION['user']['id'])."' LIMIT 1 ;");
		}
	}
}
?>