<?php
require_once('includes/config.php');
require_once('includes/classes/class.template_engine.php');
require_once('includes/functions/func.global.php');
require_once('includes/functions/func.users.php');
session_start();
$config['lang'] = check_user_lang($config);
require_once('includes/lang/lang_'.$config['lang'].'.php');

db_connect($config);

if(checkloggedin())
{
	check_negative_balance($config);

	$langs = array();

	if ($handle = opendir('includes/lang/')) 
	{
		while (false !== ($file = readdir($handle))) 
		{
			if ($file != '.' && $file != '..') 
			{
				$langv = str_replace('.php','',$file);
				$langv = str_replace('lang_','',$langv);	
				
				$langs[]['value'] = $langv;
			}
		}
		closedir($handle);
	}
	
	sort($langs);
	
	foreach ($langs as $key => $value)
	{
		if($config['lang'] == $value['value'])
		{
			$langs[$key]['name'] = ucwords($value['value']);
			$langs[$key]['selected'] = 'selected';
		}
		else
		{
			$langs[$key]['name'] = ucwords($value['value']);
			$langs[$key]['selected'] = '';
		}
	}

	if($_SESSION['user']['type'] == 'provider')
	{
		if(!isset($_POST['Submit']))
		{	
			$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/profile_provider_edit.html");
			$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['EDITPROF']));
			$page->SetParameter ('USERNAME', $_SESSION['user']['name']);
			$page->SetParameter ('PROVIDER_ID', $_SESSION['user']['id']);
			$page->SetParameter ('PAY_TYPE', $config['pay_type']);
			$page->SetParameter ('LANG_SEL', $config['userlangsel']);
			$page->SetLoop ('LANGS', $langs);
				
			$query = "SELECT provider_name,provider_email,provider_price,provider_profile,provider_types, provider_notify,provider_paypal,provider_pictype, provider_custom_fields, provider_custom_values FROM `".$config['db']['pre']."providers` WHERE provider_id='" . $_SESSION['user']['id'] . "' LIMIT 1";
			$query_result = @mysql_query ($query) OR error(mysql_error());
			while ($info = @mysql_fetch_array($query_result))
			{
				$page->SetParameter ('NAME', $info['provider_name']);
				$page->SetParameter ('EMAIL', $info['provider_email']);
				$page->SetParameter ('RATE', $info['provider_price']);
				$page->SetParameter ('PROFILE', $info['provider_profile']);
				$page->SetParameter ('NOTIFY', $info['provider_notify']);
				$page->SetParameter ('PAYPAL', $info['provider_paypal']);
				$page->SetParameter ('PICTYPE', $info['provider_pictype']);
				
				$types2 = $info['provider_types'];
				
				$db_custom_fields = explode(',',stripslashes($info['provider_custom_fields']));
				$db_custom_data = explode(',',stripslashes($info['provider_custom_values']));
				$db_user_custom = array();
				
				foreach($db_custom_fields as $key=>$value)
				{
					if($value)
					{
						$db_user_custom[$value] = $db_custom_data[$key];
					}
				}
			}
			
			$custom_fields = array();
			
			$query = "SELECT * FROM `".$config['db']['pre']."custom_fields` WHERE custom_page='profile_provider'";
			$query_result = @mysql_query ($query) OR error(mysql_error());
			while ($info = @mysql_fetch_array($query_result))
			{
				$custom_fields[$info['custom_id']]['id'] = $info['custom_id'];
				$custom_fields[$info['custom_id']]['type'] = $info['custom_type'];
				$custom_fields[$info['custom_id']]['name'] = $info['custom_name'];
				$custom_fields[$info['custom_id']]['title'] = stripslashes($info['custom_title']);
				$custom_fields[$info['custom_id']]['maxlength'] = $info['custom_max'];
				if(isset($db_user_custom[stripslashes($info['custom_title'])]))
				{
					$custom_fields[$info['custom_id']]['default'] = $db_user_custom[stripslashes($info['custom_title'])];
				}
				else
				{
					$custom_fields[$info['custom_id']]['default'] = '';
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
			
			$types3 = explode(',', $types2);
			
			foreach ($types3 as $value) 
			{
				$types4[$value] = $value;
			}
			
			$types = get_categories($config);
		
			foreach ($types as $value) 
			{
				if(isset($types4[$value['id']]))
				{
					$types[$value['id']]['selected'] = 1;
					$types[$value['id']]['selectedval'] = 'checked';
				}
				else
				{
					$types[$value['id']]['selected'] = 0;
					$types[$value['id']]['selectedval'] = '';
				}
			}
		
			$page->SetLoop ('CUSTOMFIELDS',$custom_fields);
			$page->SetLoop ('JOBTYPE', $types);
			$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
			$page->CreatePageEcho($lang,$config);
		}
		else
		{
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
			
			$update_lang = '';
		
			if(isset($_POST['lang']))
			{
				$_POST['lang'] = trim($_POST['lang']);
				$_POST['lang'] = str_replace('.','',$_POST['lang']);
				
				if(file_exists('includes/lang/lang_'.$_POST['lang'].'.php'))
				{
					$update_lang = $_POST['lang'];
					$_SESSION['user']['lang'] = $_POST['lang'];
				}
			}
			
			if(!isset($_POST['paypal']))
			{
				$_POST['paypal'] = '';
			}
			
			mysql_query("DELETE FROM `".$config['db']['pre']."notification` WHERE `user_id` = '" . $_SESSION['user']['id'] . "'");
			
			$_SESSION['user']['email'] = $_POST['email'];
			
			if(isset($_POST['notify']))
			{
				foreach ($_POST['jobtype'] as $key=>$value) 
				{
					mysql_query("INSERT INTO `".$config['db']['pre']."notification` ( `user_id` , `cat_id` , `user_email` ) VALUES ('" . $_SESSION['user']['id'] . "', '" . validate_input($key) . "', '" . validate_input($_SESSION['user']['email']) . "');");
				}
			}
			
			foreach($_POST as $key=>$value)
			{
				if(!is_array($value))
				{
					$_POST[$key] = strip_tags($value);
				}
				else
				{
					foreach ($_POST[$key] as $key2=>$value2)
					{
						$_POST[$key][$key2] = strip_tags($value2);
					}
				}
			}
			
			if(!isset($_POST['jobtype']))
			{
				$_POST['jobtype'] = array();
			}
			
			$query = "SELECT * FROM `".$config['db']['pre']."custom_fields` WHERE custom_page='profile_provider'";
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
			
			if($pic_data != '')
			{
				mysql_query("UPDATE `".$config['db']['pre']."providers` SET `provider_name` = '" . validate_input($_POST['name']) .  "',`provider_email` = '" . validate_input($_POST['email']) .  "',`provider_types` = '" . validate_input(implode(',', $_POST['jobtype'])) . "',`provider_price` = '" . validate_input($_POST['rate']) . "',`provider_profile` = '" . validate_input($_POST['profile']) . "',`provider_notify` = '" . validate_input($_POST['notify']) . "',`provider_picture` = '" . validate_input($pic_data) . "',`provider_pictype` = '" . validate_input($_FILES['picture']['type']) . "',`provider_paypal` = '" . validate_input($_POST['paypal']) . "',`provider_lang` = '" . validate_input($update_lang) . "',`provider_custom_fields` = '" . validate_input($custom_db_fields2) . "',`provider_custom_values` = '" . validate_input($custom_db_data2) . "' WHERE `provider_id` = '" . $_SESSION['user']['id'] . "' LIMIT 1 ;");
			}
			else
			{
				mysql_query("UPDATE `".$config['db']['pre']."providers` SET `provider_name` = '" .validate_input($_POST['name']) .  "',`provider_email` = '" . validate_input($_POST['email']) .  "',`provider_types` = '" . validate_input(implode(',', $_POST['jobtype'])) . "',`provider_price` = '" . validate_input($_POST['rate']) . "',`provider_profile` = '" . validate_input($_POST['profile'] ). "',`provider_notify` = '" . validate_input($_POST['notify']) . "',`provider_paypal` = '" . validate_input($_POST['paypal']) . "',`provider_lang` = '" . validate_input($update_lang) . "',`provider_custom_fields` = '" . validate_input($custom_db_fields2) . "',`provider_custom_values` = '" . validate_input($custom_db_data2) . "' WHERE `provider_id` = '" . $_SESSION['user']['id'] . "' LIMIT 1 ;");
			}
					
			message($lang['PROFILE_THANKYOU'], $config,$lang, 'manage.php');
		}
	}
	else
	{
		if(!isset($_POST['Submit']))
		{		
			$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/profile_buyer_edit.html");
			$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['EDITPROF']));
			$page->SetParameter ('USERNAME', $_SESSION['user']['name']);
			$page->SetParameter ('LANG_SEL', $config['userlangsel']);
			$page->SetLoop ('LANGS', $langs);
			
			$query = "SELECT buyer_name,buyer_email,buyer_custom_fields,buyer_custom_values FROM `".$config['db']['pre']."buyers` WHERE buyer_id='" . $_SESSION['user']['id'] . "' LIMIT 1";
			$query_result = @mysql_query ($query) OR error(mysql_error());
			while ($info = @mysql_fetch_array($query_result))
			{
				$page->SetParameter ('NAME', $info['buyer_name']);
				$page->SetParameter ('EMAIL', $info['buyer_email']);
				
				$db_custom_fields = explode(',',stripslashes($info['provider_custom_fields']));
				$db_custom_data = explode(',',stripslashes($info['provider_custom_values']));
				$db_user_custom = array();
				
				foreach($db_custom_fields as $key=>$value)
				{
					if($value)
					{
						$db_user_custom[$value] = $db_custom_data[$key];
					}
				}
			}
			
			$custom_fields = array();
			
			$query = "SELECT * FROM `".$config['db']['pre']."custom_fields` WHERE custom_page='profile_buyer'";
			$query_result = @mysql_query ($query) OR error(mysql_error());
			while ($info = @mysql_fetch_array($query_result))
			{
				$custom_fields[$info['custom_id']]['id'] = $info['custom_id'];
				$custom_fields[$info['custom_id']]['type'] = $info['custom_type'];
				$custom_fields[$info['custom_id']]['name'] = $info['custom_name'];
				$custom_fields[$info['custom_id']]['title'] = stripslashes($info['custom_title']);
				$custom_fields[$info['custom_id']]['maxlength'] = $info['custom_max'];
				if(isset($db_user_custom[stripslashes($info['custom_title'])]))
				{
					$custom_fields[$info['custom_id']]['default'] = $db_user_custom[stripslashes($info['custom_title'])];
				}
				else
				{
					$custom_fields[$info['custom_id']]['default'] = '';
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
			
			$page->SetLoop ('CUSTOMFIELDS',$custom_fields);			
			$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
			$page->CreatePageEcho($lang,$config);
		}
		else
		{
			$_SESSION['user']['email'] = $_POST['email'];
		
			$update_lang = '';
		
			if(isset($_POST['lang']))
			{
				$_POST['lang'] = trim($_POST['lang']);
				$_POST['lang'] = str_replace('.','',$_POST['lang']);
				
				if(file_exists('includes/lang/lang_'.$_POST['lang'].'.php'))
				{
					$update_lang = $_POST['lang'];
					$_SESSION['user']['lang'] = $_POST['lang'];
				}
			}
			
			$query = "SELECT * FROM `".$config['db']['pre']."custom_fields` WHERE custom_page='profile_buyer'";
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
			
			mysql_query("UPDATE `".$config['db']['pre']."buyers` SET `buyer_name` = '" . validate_input($_POST['name']) . "',`buyer_email` = '" . validate_input($_POST['email']) . "',`buyer_lang` = '" . validate_input($update_lang) . "',`buyer_custom_fields` = '" . validate_input($custom_db_fields2) . "',`buyer_custom_values` = '" . validate_input($custom_db_data2) . "' WHERE `buyer_id` = '" . $_SESSION['user']['id'] . "' LIMIT 1 ;");
			
			message($lang['PROFILE_THANKYOU'], $config,$lang, 'manage.php');
		}
	}
}
else
{
	header("Location: login.php");
	exit;
}
?>