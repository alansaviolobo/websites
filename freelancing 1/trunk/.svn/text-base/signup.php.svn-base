<?php
require_once('includes/config.php');
require_once("includes/classes/class.phpmailer.php");
require_once('includes/classes/class.template_engine.php');
require_once('includes/functions/func.global.php');
require_once('includes/functions/func.signup.php');
require_once('includes/lang/lang_'.$config['lang'].'.php');

db_connect($config);

session_start();

if(!isset($type))
{
	$type = '';
}
if($type == '')
{
	if(isset($_GET['type']))
	{
		$type = $_GET['type'];
	}
	elseif(isset($_POST['type']))
	{
		$type = $_POST['type'];
	}
}
if(!isset($_POST['paypal']))
{
	$_POST['paypal'] = '';
}

if(isset($_GET['confirm']))
{
	$check_confirm = 0;

	if($type == 'buyer')
	{
		$check_confirm = mysql_num_rows(mysql_query("SELECT 1 FROM `".$config['db']['pre']."buyers` WHERE buyer_id='".validate_input($_GET['user'])."' AND buyer_confirm='".validate_input($_GET['confirm'])."' LIMIT 1"));
	}
	elseif($type == 'provider')
	{
		$check_confirm = mysql_num_rows(mysql_query("SELECT 1 FROM `".$config['db']['pre']."providers` WHERE provider_id='".validate_input($_GET['user'])."' AND provider_confirm='".validate_input($_GET['confirm'])."' LIMIT 1"));
	}
	
	if($check_confirm)
	{
		if($type == 'buyer')
		{
			mysql_query("UPDATE `".$config['db']['pre']."buyers` SET `buyer_status` = '1', `buyer_confirm` = '' WHERE buyer_id='".validate_input($_GET['user'])."' AND buyer_confirm='".validate_input($_GET['confirm'])."' LIMIT 1 ;");
		}
		elseif($type == 'provider')
		{
			mysql_query("UPDATE `".$config['db']['pre']."providers` SET `provider_status` = '1', `provider_confirm` = '' WHERE provider_id='".validate_input($_GET['user'])."' AND provider_confirm='".validate_input($_GET['confirm'])."' LIMIT 1 ;");
		}
	
		message($lang['THANKSIGNUP'], $config,$lang, 'login.php');
	}
	else
	{
		message($lang['CONFUSED'],$config,$lang,'',false);
	}
	
	exit;
}

// Check if this is an availability check from signup page using ajax
if(isset($_GET['avail']))
{
	if(ereg('[^A-Za-z0-9]',$_GET['avail']))
	{
		echo $_GET['avail'].'|2';
		exit;
	}

	// Check if anyone has this username
	if($type == 'buyer')
	{
		$availcheck = mysql_num_rows(mysql_query("SELECT 1 FROM `".$config['db']['pre']."buyers` WHERE buyer_username='".validate_input($_GET['avail'])."' LIMIT 1"));
	}
	elseif($type == 'provider')
	{
		$availcheck = mysql_num_rows(mysql_query("SELECT 1 FROM `".$config['db']['pre']."providers` WHERE provider_username='".validate_input($_GET['avail'])."' LIMIT 1"));
	}

	if($availcheck)
	{
		// Someone already has this username
		echo $_GET['avail'].'|0';
	}
	else
	{
		// That username is available
		echo $_GET['avail'].'|1';
	}
	
	exit;
}

$custom_fields = array();

if($type == 'buyer')
{
	$query = "SELECT * FROM `".$config['db']['pre']."custom_fields` WHERE custom_page='profile_buyer'";
}
else
{
	$query = "SELECT * FROM `".$config['db']['pre']."custom_fields` WHERE custom_page='profile_provider'";
}
$query_result = @mysql_query ($query) OR error(mysql_error());
while ($info = @mysql_fetch_array($query_result))
{
	$custom_fields[$info['custom_id']]['id'] = $info['custom_id'];
	$custom_fields[$info['custom_id']]['type'] = $info['custom_type'];
	$custom_fields[$info['custom_id']]['name'] = $info['custom_name'];
	$custom_fields[$info['custom_id']]['title'] = stripslashes($info['custom_title']);
	$custom_fields[$info['custom_id']]['maxlength'] = $info['custom_max'];
	
	if(isset($_POST['custom'][$info['custom_id']]))
	{
		$custom_fields[$info['custom_id']]['default'] = substr(strip_tags($_POST['custom'][$info['custom_id']]),0,$info['custom_max']);
		$custom_fields[$info['custom_id']]['userent'] = 1;
	}
	else
	{
		$custom_fields[$info['custom_id']]['default'] = $info['custom_default'];
		$custom_fields[$info['custom_id']]['userent'] = 0;
	}
	
	if($info['custom_type'] == 'select')
	{
		$options = explode(',',stripslashes($info['custom_options']));		
	
		$selectbox = '<select name="custom['.$info['custom_id'].']" id="custom['.$info['custom_id'].']">';
		foreach($options as $key3=>$value3)
		{
			if($value3 == $custom_fields[$info['custom_id']]['default'])
			{
				$selectbox.= '<option value="'.$value3.'" selected>'.$value3.'</option>';
			}
			else
			{
				$selectbox.= '<option value="'.$value3.'">'.$value3.'</option>';
			}
		}
		$selectbox.= '</select>';
	
		$custom_fields[$info['custom_id']]['selectbox'] = $selectbox;
	}
	else
	{
		$custom_fields[$info['custom_id']]['selectbox'] = '';
	}
}

// Check if they have submitted the signup page
if(isset($_POST['username']))
{
	// Initiate error messages
	$errors = 0;
	$username_error = '';
	$password_error = '';
	$email_error = '';
	$agree_error = '';
	$security_error = '';
	$paypal_error = '';
	$name_error = '';
	
	$_POST['username'] = strip_tags($_POST['username']);
	
	if(ereg('[^A-Za-z0-9]',$_POST['username']))
	{
		$errors++;
		$username_error = $lang['USERALPHA'];
	}
	elseif( (strlen($_POST['username']) < 4) OR (strlen($_POST['username']) > 16) )
	{
		$errors++;
		$username_error = $lang['USERLEN'];
	}
	else
	{
		if($type == 'buyer')
		{
			$avail = mysql_num_rows(mysql_query("SELECT 1 FROM `".$config['db']['pre']."buyers` WHERE buyer_username='".validate_input($_POST['username'])."' LIMIT 1"));
		}
		elseif($type == 'provider')
		{
			$avail = mysql_num_rows(mysql_query("SELECT 1 FROM `".$config['db']['pre']."providers` WHERE provider_username='".validate_input($_POST['username'])."' LIMIT 1"));
		}
		
		if($avail)
		{
			$errors++;
			$username_error = $lang['USERUNAV'];
		}
		else
		{
			$username_ban_check = mysql_num_rows(mysql_query("SELECT 1 FROM ".$config['db']['pre']."bans WHERE ban_value='" . validate_input($_POST['username']) . "' AND ban_type='USERNAME' LIMIT 1"));
				
			if($username_ban_check)
			{
				$errors++;
				$username_error = $lang['USERBAN'];
			}
		}
	}
	
	if( (strlen($_POST['password']) < 4) OR (strlen($_POST['password']) > 16) )
	{
		$errors++;
		$password_error = $lang['PASSLENG'];
	}
	elseif($_POST['password'] != $_POST['password2'])
	{
		$errors++;
		$password_error = $lang['PASSNOMATCH'];
	}
	
	if(trim($_POST['email']) == '')
	{
		$errors++;
		$email_error = $lang['ENTEREMAIL'];
	}
	elseif(!eregi("^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$", $_POST['email'])) 
	{
		$errors++;
		$email_error = $lang['EMAILINV'];
	}
	else
	{
		$account_exists = check_account_exists($config,$_POST['email'],$type);
		
		if($account_exists == 1)
		{
			$errors++;
			$email_error = $lang['ACCAEXIST'];
		}
		else
		{
			$email_ban_check = mysql_num_rows(mysql_query("SELECT 1 FROM ".$config['db']['pre']."bans WHERE ban_value='" . validate_input($_POST['email']) . "' AND ban_type='EMAIL' LIMIT 1"));
			
			if($email_ban_check)
			{
				$errors++;
				$email_error = $lang['EMAILBAN'];
			}
		}
	}
	
	// If they have specified a patpal address check that it's a valid email address
	if(isset($_POST['paypal']))
	{
		if($_POST['paypal'] != '')
		{
			if(!eregi("^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$", $_POST['paypal'])) 
			{
				$errors++;
				$paypal_error = $lang['INVALIDPAYPAL'];
			}
			else
			{
				$paypal_ban_check = mysql_num_rows(mysql_query("SELECT 1 FROM ".$config['db']['pre']."bans WHERE ban_value='" . validate_input($_POST['paypal']) . "' AND ban_type='PAYPAL' LIMIT 1"));
				
				if($paypal_ban_check)
				{
					$errors++;
					$paypal_error = $lang['PAYPALBAN'];
				}
			}
		}
	}
	
	if(!isset($_POST['notify']))
	{
		$_POST['notify'] = 0;
	}
	
	// Check they have agreed to the terms
	if(!isset($_POST['agree']))
	{
		$errors++;
		$agree_error = $lang['ACCEPTTERMS'];
	}
	
	if($config['security'])
	{
		$_POST['security_code'] = trim($_POST['security_code']);
		
		if(strtoupper($_POST['security_code']) != strtoupper($_SESSION['seccode']))
		{
			$security_error = $lang['WORDINCORECT'];
			$errors++;
		}
	}
	
	if($errors == 0)
	{
		$custom_db_fields = array();
		$custom_db_fields2 = '';
		$custom_db_data = array();
		$custom_db_data2 = '';
						
		foreach($custom_fields as $key=>$value)
		{
			if($value['userent'])
			{
				$custom_db_fields[$value['id']] = $value['title'];
				$custom_db_data[$value['id']] = str_replace(',','&#44;',$value['default']);
			}
		}
		
		$custom_db_fields2 = implode(',',$custom_db_fields);
		$custom_db_data2 = implode(',',$custom_db_data);	
	
		$confirm_id = get_random_id();
	
		if($type == 'buyer')
		{
			mysql_query("INSERT INTO `".$config['db']['pre']."buyers` ( `buyer_id` , `buyer_username` , `buyer_password` , `buyer_email` , `buyer_name` , `buyer_joined` , `buyer_rating` , `buyer_reviews` , `buyer_confirm` , `buyer_custom_fields` , `buyer_custom_values` ) VALUES ('', '" . validate_input($_POST['username']) . "', '" . validate_input(md5($_POST['password'])) . "', '" . validate_input($_POST['email']) . "', '" . validate_input($_POST['name']) . "', '" . time() . "', '0', '0', '".validate_input($confirm_id)."', '" . validate_input($custom_db_fields2) . "', '" . validate_input($custom_db_data2) . "');");

			$user_id = mysql_insert_id();
	
			mysql_query("INSERT INTO `".$config['db']['pre']."buyers_balance` ( `buyer_id` , `balance_amount` ) VALUES ('" . $user_id . "', '" . $config['start_amount_buyer'] . "');");
		}
		else
		{	
			if(!isset($_POST['jobtype']))
			{
				$_POST['jobtype'] = array();
			}
			
			$pic_data = '';
			
			if(isset($_FILES['picture']))
			{
				if($_FILES['picture']['error'] == 0)
				{
					if($imageinfo = getimagesize($_FILES['picture']['tmp_name']))
					{
						if( ($config['images']['max_width'] > $imageinfo['0']) AND ($config['images']['max_height'] > $imageinfo['1']) )
						{	
							$pic_data = addslashes(fread(fopen($_FILES['picture']['tmp_name'], "r"), filesize($_FILES['picture']['tmp_name'])));
						}
					}
				}
			}
		
			mysql_query("INSERT INTO `".$config['db']['pre']."providers` ( `provider_id` , `provider_username` , `provider_password` , `provider_email` , `provider_name` , `provider_types` , `provider_price` , `provider_joined` , `provider_rating` , `provider_reviews` , `provider_profile` , `provider_notify` , `provider_picture` , `provider_pictype` , `provider_confirm` , `provider_custom_fields` , `provider_custom_values` ) VALUES ('', '" . validate_input($_POST['username']) . "', '" . validate_input(md5($_POST['password'])) . "', '" . validate_input($_POST['email']) . "', '" . validate_input($_POST['name']) . "', '" . validate_input(implode(',', $_POST['jobtype'])) . "', '" . validate_input($_POST['rate']) . "', '" . time() . "', '0', '0', '" . validate_input($_POST['profile']) . "', '" . validate_input($_POST['notify']) . "', '" . validate_input($pic_data) . "', '" . validate_input($_FILES['picture']['type']) . "', '".validate_input($confirm_id)."', '" . validate_input($custom_db_fields2) . "', '" . validate_input($custom_db_data2) . "');");

			$user_id = mysql_insert_id();
			
			mysql_query("INSERT INTO `".$config['db']['pre']."providers_balance` ( `provider_id` , `balance_amount` ) VALUES ('" . $user_id . "', '" . $config['start_amount_provider'] . "');");

			if(isset($_POST['notify']))
			{
				if(isset($_POST['jobtype']))
				{
					foreach ($_POST['jobtype'] as $key=>$value) 
					{
						mysql_query("INSERT INTO `".$config['db']['pre']."notification` ( `user_id` , `cat_id` , `user_email` ) VALUES ('" . $user_id  . "', '" . $key . "', '" . validate_input($_POST['email']) . "');");
					}
				}
			}
		}
		
		$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/email_signup_confirm.html");
		$page->SetParameter ('ID', $confirm_id);
		$page->SetParameter ('USER_ID', $user_id);
		$page->SetParameter ('USER_TYPE', $type);
		$page->SetParameter ('SITE_URL', $config['site_url']);
		$page->SetParameter ('EMAIL', $_POST['email']);
		$page->SetParameter ('SITE_TITLE', $config['site_title']);
		$email_body = $page->CreatePageReturn($lang,$config);

		email($_POST['email'],$config['site_title'].' - '.$lang['EMAILCONFIRM'],$email_body,$config);

		message($lang['THANKCONFIRM'], $config,$lang,'',false);
	}
}

if($type == 'buyer')
{
	$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/signup_buyer.html");
	$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['SIGNUP']));
	$page->SetParameter ('SITE_TITLE', $config['site_title']);
	$page->SetParameter ('SECURITY_CODE',$config['security']);
	$page->SetParameter ('PAY_TYPE',$config['pay_type']);
	$page->SetLoop ('CUSTOMFIELDS',$custom_fields);
	if(isset($_POST['username']))
	{
		$page->SetParameter ('USERNAME_FIELD', $_POST['username']);
		$page->SetParameter ('EMAIL_FIELD', $_POST['email']);
		$page->SetParameter ('NAME_FIELD', $_POST['name']);
		$page->SetParameter ('PAYPAL_FIELD', $_POST['paypal']);
		
		$page->SetParameter ('USERNAME_ERROR', $username_error);
		$page->SetParameter ('PASSWORD_ERROR', $password_error);
		$page->SetParameter ('EMAIL_ERROR', $email_error);
		$page->SetParameter ('AGREE_ERROR', $agree_error);
		$page->SetParameter ('SECURITY_ERROR', $security_error);
		$page->SetParameter ('NAME_ERROR', $name_error);
		$page->SetParameter ('PAYPAL_ERROR', $paypal_error);
	}
	else
	{
		$page->SetParameter ('USERNAME_FIELD', '');
		$page->SetParameter ('EMAIL_FIELD', '');
		$page->SetParameter ('NAME_FIELD', '');
		$page->SetParameter ('PAYPAL_FIELD', '');
	
		$page->SetParameter ('USERNAME_ERROR', '');
		$page->SetParameter ('PASSWORD_ERROR', '');
		$page->SetParameter ('EMAIL_ERROR', '');
		$page->SetParameter ('AGREE_ERROR', '');
		$page->SetParameter ('SECURITY_ERROR', '');
		$page->SetParameter ('NAME_ERROR', '');
		$page->SetParameter ('PAYPAL_ERROR', '');
	}
	$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
	$page->CreatePageEcho($lang,$config);
}
elseif($type == 'provider')
{
	$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/signup_provider.html");
	$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['SIGNUP']));
	$page->SetParameter ('SITE_TITLE', $config['site_title']);
	$page->SetParameter ('SECURITY_CODE',$config['security']);
	$page->SetParameter ('PAY_TYPE',$config['pay_type']);
	$page->SetLoop ('CUSTOMFIELDS',$custom_fields);
	if(isset($_POST['username']))
	{
		$page->SetParameter ('USERNAME_FIELD', $_POST['username']);
		$page->SetParameter ('EMAIL_FIELD', $_POST['email']);
		$page->SetParameter ('NAME_FIELD', $_POST['name']);
		$page->SetParameter ('PAYPAL_FIELD', $_POST['paypal']);
		$page->SetParameter ('RATE_FIELD', $_POST['rate']);
		
		$page->SetParameter ('USERNAME_ERROR', $username_error);
		$page->SetParameter ('PASSWORD_ERROR', $password_error);
		$page->SetParameter ('EMAIL_ERROR', $email_error);
		$page->SetParameter ('AGREE_ERROR', $agree_error);
		$page->SetParameter ('SECURITY_ERROR', $security_error);
		$page->SetParameter ('NAME_ERROR', $name_error);
		$page->SetParameter ('PAYPAL_ERROR', $paypal_error);
		
		if(isset($_POST['jobtype']))
		{
			$page->SetLoop ('JOBTYPE', get_categories($config,$_POST['jobtype']));
		}
		else
		{
			$page->SetLoop ('JOBTYPE', get_categories($config));
		}
	}
	else
	{
		$page->SetParameter ('USERNAME_FIELD', '');
		$page->SetParameter ('EMAIL_FIELD', '');
		$page->SetParameter ('NAME_FIELD', '');
		$page->SetParameter ('PAYPAL_FIELD', '');
		$page->SetParameter ('RATE_FIELD', '');
	
		$page->SetParameter ('USERNAME_ERROR', '');
		$page->SetParameter ('PASSWORD_ERROR', '');
		$page->SetParameter ('EMAIL_ERROR', '');
		$page->SetParameter ('AGREE_ERROR', '');
		$page->SetParameter ('SECURITY_ERROR', '');
		$page->SetParameter ('NAME_ERROR', '');
		$page->SetParameter ('PAYPAL_ERROR', '');
		
		$page->SetLoop ('JOBTYPE', get_categories($config));
	}
	$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
	$page->CreatePageEcho($lang,$config);
}
else
{
	$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/signup.html");
	$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['SIGNUP']));
	$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
	$page->CreatePageEcho($lang,$config);
}
?>