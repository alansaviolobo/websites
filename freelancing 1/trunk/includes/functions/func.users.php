<?php
function userlogin($config,$username,$password,$type)
{
	$username = stripslashes($username);
	$password = stripslashes($password);
	$userinfo = array();
	
	if(strlen($username) > 20)
	{
		return 0;
	}
	if(strlen($password) > 50)
	{
		return 0;
	}

	if(!preg_match("/^[[:alnum:]]+$/", $username))
	{
		return 0;
	}

	if($type=='provider')
	{
		$query = "SELECT provider_id,provider_name,provider_email,provider_status,provider_lang,group_id FROM ".$config['db']['pre']."providers WHERE provider_username='" . validate_input($username) . "' AND provider_password='" . validate_input(md5($password)) . "' LIMIT 1";
		$query_result = mysql_query($query);
		$num_rows = mysql_num_rows($query_result);
	
		if($num_rows == 1)
		{
			while ($info = @mysql_fetch_array($query_result))
			{
				$userinfo['id'] = $info['provider_id'];
				$userinfo['type'] = 'provider';
				$userinfo['comp'] = $info['provider_name'];
				$userinfo['email'] = $info['provider_email'];
				$userinfo['status'] = $info['provider_status'];
				$userinfo['lang'] = $info['provider_lang'];
				$userinfo['group'] = $info['group_id'];
			}
			
			return $userinfo;
		}
		else
		{
			return 0;
		}
	}
	else
	{
		$query = "SELECT buyer_id,buyer_name,buyer_email,buyer_status,buyer_lang,group_id FROM ".$config['db']['pre']."buyers WHERE buyer_username='" . validate_input($username) . "' AND buyer_password='" . validate_input(md5($password)) . "' LIMIT 1";
		$query_result = mysql_query($query);
		$num_rows = mysql_num_rows($query_result);
		
		if($num_rows == 1)
		{
			while ($info = @mysql_fetch_array($query_result))
			{
				$userinfo['id'] = $info['buyer_id'];
				$userinfo['type'] = 'buyer';
				$userinfo['comp'] = $info['buyer_name'];
				$userinfo['email'] = $info['buyer_email'];
				$userinfo['status'] = $info['buyer_status'];
				$userinfo['lang'] = $info['buyer_lang'];
				$userinfo['group'] = $info['group_id'];
			}
		
			return $userinfo;
		}
		else
		{
			return 0;
		}
	}
}

function checkloggedin()
{
	if(isset($_SESSION['user']['id']))
	{
		return TRUE;
	}
	else
	{	
		return FALSE;
	}
}

function send_forgot_email($email,$type,$id,$config,$lang=array())
{
	$time = time();
	$rand = getrandnum(10);
	$forgot = md5($time.'_:_'.$rand.'_:_'.$email);
	
	if($type == 'buyer')
	{
		mysql_query("UPDATE `".$config['db']['pre']."buyers` SET `buyer_forgot` = '".validate_input($forgot)."' WHERE `buyer_id` =".validate_input($id)." LIMIT 1 ;");
	}
	else
	{
		mysql_query("UPDATE `".$config['db']['pre']."providers` SET `provider_forgot` = '".validate_input($forgot)."' WHERE `provider_id` =".validate_input($id)." LIMIT 1 ;");
	}

	require_once("includes/classes/class.phpmailer.php");
	
	$mail = new PHPMailer();
	
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
	$mail->AddAddress($email);
	
	$mail->Subject = $config['site_title'] . ': '.$lang['FORGOTPASS'];
	$mail->Body = $lang['TORESET'].":\n\n".$config['site_url']."login.php?type=".$type."&forgot=".$forgot."&r=".$rand."&e=".$email."&t=".$time;
	$mail->IsHTML(false);
	$mail->Send();
}
?>