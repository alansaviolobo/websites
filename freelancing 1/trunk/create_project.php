<?php
require_once('includes/config.php');
require_once("includes/classes/class.phpmailer.php");
require_once('includes/classes/class.template_engine.php');
require_once('includes/functions/func.global.php');
require_once('includes/functions/func.users.php');
session_start();
$config['lang'] = check_user_lang($config);
require_once('includes/lang/lang_'.$config['lang'].'.php');

db_connect($config);
check_cookie($_SESSION,$config);

// Get Settings
$settings = get_settings('create_project.php',$config);

if(checkloggedin())
{
	if($_SESSION['user']['type'] == 'buyer')
	{
		$custom_fields = array();
		
		$query = "SELECT * FROM `".$config['db']['pre']."custom_fields` WHERE custom_page='create_project'";
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
	
		// Get usergroup details
		$usergroup = check_user_group($config);
		
		if($usergroup['post_project_discount'])
		{
			if($config['post_project_amount'])
			{
				if($config['post_project_amount'] > $usergroup['post_project_discount'])
				{
					$config['post_project_amount'] = ($config['post_project_amount'] - $usergroup['post_project_discount']);
				}
				else
				{
					$config['post_project_amount'] = 0;
				}
			}
		}

		check_negative_balance($config);
	
		if(check_account_frozen($_SESSION['user']['id'], $_SESSION['user']['type'],$config))
		{
			message($lang['ACCOUNTFROZEN'],$config,$lang);
		}
		else
		{ 
			if(check_balance($_SESSION['user']['type'], $_SESSION['user']['id'],$config['post_project_amount'],$config))
			{
				if(isset($_POST['Submit']))
				{
					if(isset($_POST['featured']))
					{
						if(!check_balance($_SESSION['user']['type'], $_SESSION['user']['id'],$config['post_featured_amount'],$config))
						{
							message($lang['NOTENOUGHMONEY'], $config,$lang);
							exit;
						}
					}
					
					$errors = array();
					
					foreach($_POST as $key=>$value)
					{
						if(is_array($_POST[$key]))
						{
							foreach($_POST[$key] as $key2=>$value2)
							{
								$_POST[$key][$key2] = strip_tags($value2);
							}
						}
						else
						{
							$_POST[$key] = strip_tags($value);
						}
					}
					
					if(strlen($_POST['project_name']) <= 0)
					{
						$errors[]['message'] = $lang['PROJECTTITLEMISSING'];
					}
						
					if(!isset($_POST['jobtype']))
					{
						$errors[]['message'] = $lang['PICK1JOBTYPE'];
					}
						
					if(strlen($_POST['project_description']) <= 0)
					{
						$errors[]['message'] = $lang['PROJECTDESCRIPTIONMISSING'];
					}
						
					$query = "SELECT rule_eregi,rule_msg FROM `".$config['db']['pre']."rules` WHERE page='create_project.php'";
					$query_result = @mysql_query ($query) OR error(mysql_error());
					while ($info = @mysql_fetch_array($query_result))
					{
						if(eregi($info['rule_eregi'], $_POST['project_name']))
						{
							$errors[]['message'] = $info['rule_msg']."[Project Name]";
						}
						if(eregi($info['rule_eregi'], $_POST['project_description']))
						{
							$errors[]['message'] = $info['rule_msg']."[Project Description]";
						}
					}

					if(count($errors) > 0)
					{
						if(!isset($_POST['jobtype']))
						{
							$_POST['jobtype'] = array();
						}
					
						$jobtypes = get_categories($config,$_POST['jobtype']);
						
						$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/create_project.html");
						$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['CREATEPROJ']));
						$page->SetLoop ('JOBTYPES',$jobtypes);
						$page->SetParameter ('PROJECTTITLE',$_POST['project_name']);
						$page->SetParameter ('DESCRIPTION', $_POST['project_description']);
						$page->SetParameter ('MINBUDGET', $_POST['budget_min']);
						$page->SetParameter ('MAXBUDGET', $_POST['budget_max']);
						$page->SetParameter ('BIDDINGTIME', $_POST['days']);
						$page->SetParameter ('FEATURED_PRICE', $config['post_featured_amount']);
						
						if($_POST['featured']=='1')
						{
							$page->SetParameter ('FEATURED', 'selected');
						}
						else
						{
							$page->SetParameter ('FEATURED', '');
						}
						
						$page->SetLoop('ERRORS', $errors);
						if($_POST['featured']==1)
						{
							$page->SetParameter ('FEATURED', "checked");
						}
						$page->SetParameter ('ATTACHMENT', $_POST['attachment']);
						$page->SetLoop('ERRORS', array());
						$page->SetLoop ('CUSTOMFIELDS',$custom_fields);
						$page->SetParameter ('USERNAME', $_SESSION['user']['name']);
						$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
						$page->CreatePageEcho($lang,$config);
					}
					else
					{
						if(!isset($_POST['featured']))
						{
							$_POST['featured'] = 0;
						}
						
						$file_id = 0;
						
						if(isset($_FILES['attachment']))
						{
							if($_FILES['attachment']['error'] == 0)
							{
								$fileinfo=pathinfo($_FILES['attachment']['name']);

								$sql = "SELECT type_id,type_ext,type_content,max_size FROM ".$config['db']['pre']."attachments_types WHERE type_ext='".validate_input($fileinfo['extension'])."' AND type_content='".validate_input($_FILES['attachment']['type'])."' AND max_size>'".validate_input($_FILES['attachment']['size'])."' LIMIT 1";
								$query_result = mysql_query($sql);
								$num=mysql_num_rows($query_result);
								if($num>0)
								{
									$fp = fopen($_FILES['attachment']['tmp_name'], 'r');
									$content = fread($fp, $_FILES['attachment']['size']);
									$content = addslashes($content);
									fclose($fp);
								
									mysql_query("INSERT INTO `".$config['db']['pre']."attachments` ( `file_id` , `file_name` , `file_content` , `file_type` , `file_size` ) VALUES ('', '" . validate_input($_FILES['attachment']['name']) . "', '" . $content . "', '" . validate_input($_FILES['attachment']['type'])  . "', '" . validate_input($_FILES['attachment']['size'])  . "');");
								
									$file_id = mysql_insert_id();
								}
								else
								{
									message($lang['WRONGFILETYPE'],$config,$lang,'');
								}
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

						$end_time = time() + ($_POST['days'] * 86400);
						
						mysql_query("INSERT INTO `".$config['db']['pre']."projects` ( `project_id` , `project_title` , `project_desc` , `project_types` , `project_db` , `project_os` , `project_budget_min` , `project_budget_max` , `project_start` , `project_end` , `project_featured` , `project_fileid` , `buyer_id` , `project_custom_fields` , `project_custom_values` ) VALUES ('', '" . validate_input($_POST['project_name']) . "', '" . validate_input(nl2br($_POST['project_description'])) . "', '" . validate_input(implode(",", $_POST['jobtype'])) . "', '" . addslashes($_POST['dbtype']) . "', '" . validate_input($_POST['ostype']) . "', '" . validate_input($_POST['budget_min']) . "', '" . validate_input($_POST['budget_max']) . "', '" . time() . "', '" . $end_time . "', '" . validate_input($_POST['featured']) . "', '" . $file_id . "', '" . $_SESSION['user']['id'] . "', '" . validate_input($custom_db_fields2) . "', '" . validate_input($custom_db_data2) . "');") or die(mysql_error());
						
						$project_id = mysql_insert_id();
							
						if($_POST['featured'] == 1)
						{
							if($config['post_project_amount'])
							{
								minus_balance($_SESSION['user']['type'], $_SESSION['user']['id'],$config['post_project_amount'],$config);
								mysql_query("INSERT INTO `".$config['db']['pre']."transactions` ( `transaction_id` , `transaction_type` , `buyer_id` , `provider_id` , `transaction_ip` , `transaction_time` , `transaction_amount` , `transaction_description` , `transaction_method` ) VALUES ('', 'adm', '" . $_SESSION['user']['id'] . "', '0', '" . encode_ip($_SERVER,$_ENV) . "', '" . time() . "', '" . $config['post_project_amount'] . "', 'Project Created','withdraw');");
							}
						
							if($config['post_featured_amount'])
							{
								minus_balance($_SESSION['user']['type'], $_SESSION['user']['id'],$config['post_featured_amount'],$config);
								mysql_query("INSERT INTO `".$config['db']['pre']."transactions` ( `transaction_id` , `transaction_type` , `buyer_id` , `provider_id` , `transaction_ip` , `transaction_time` , `transaction_amount` , `transaction_description` , `transaction_method` ) VALUES ('', 'adm', '" . $_SESSION['user']['id'] . "', '0', '" . encode_ip($_SERVER,$_ENV) . "', '" . time() . "', '" . $config['post_featured_amount'] . "', 'Featured Project Created','withdraw');");
							}
						}
						else
						{
							if($config['post_project_amount'])
							{
								minus_balance($_SESSION['user']['type'], $_SESSION['user']['id'],$config['post_project_amount'],$config);
								mysql_query("INSERT INTO `".$config['db']['pre']."transactions` ( `transaction_id` , `transaction_type` , `buyer_id` , `provider_id` , `transaction_ip` , `transaction_time` , `transaction_amount` , `transaction_description` , `transaction_method` ) VALUES ('', 'adm', '" . $_SESSION['user']['id'] . "', '0', '" . encode_ip($_SERVER,$_ENV) . "', '" . time() . "', '" . $config['post_project_amount'] . "', 'Project Created','withdraw');");
							}
						}
						
						header("Location: project.php?id=" . $project_id);
						exit;
					}
				}
				else
				{
					$jobtypes = get_categories($config);
					
					$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/create_project.html");
					$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,'Create Project'));
					$page->SetParameter ('PROJECTTITLE','');
					$page->SetParameter ('DESCRIPTION', '');
					$page->SetParameter ('MINBUDGET', '');
					$page->SetParameter ('MAXBUDGET', '');
					$page->SetParameter ('BIDDINGTIME', '7');
					$page->SetParameter ('FEATURED', '');
					$page->SetParameter ('FEATURED_PRICE', $config['post_featured_amount']);
					$page->SetLoop ('JOBTYPES',$jobtypes);
					$page->SetLoop ('ERRORS', array());
					$page->SetLoop ('CUSTOMFIELDS',$custom_fields);
					$page->SetParameter ('USERNAME', $_SESSION['user']['name']);
					$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
					$page->CreatePageEcho($lang,$config);
				}
			}
			else
			{
				message($lang['NOTENOUGHMONEY'], $config,$lang);
				exit;
			}
		}
	}
	else
	{
		message($lang['MUSTLOGINCREATEPROJECT'],$config,$lang);
	}
}
else
{
	message($lang['MUSTLOGINCREATEPROJECT'],$config,$lang);
}
?>