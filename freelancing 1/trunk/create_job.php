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

if(checkloggedin())
{
	if($_SESSION['user']['type'] == 'buyer')
	{
		check_negative_balance($config);
		
		// Get usergroup details
		$usergroup = check_user_group($config);
		
		if($usergroup['post_job_discount'])
		{
			if($config['post_job_amount'])
			{
				if($config['post_job_amount'] > $usergroup['post_job_discount'])
				{
					$config['post_job_amount'] = ($config['post_job_amount'] - $usergroup['post_job_discount']);
				}
				else
				{
					$config['post_job_amount'] = 0;
				}
			}
		}
	
		if(check_account_frozen($_SESSION['user']['id'], $_SESSION['user']['type'],$config))
		{
			message($lang['ACCOUNTFROZEN'],$config,$lang);
		}
		else
		{ 
			if(check_balance($_SESSION['user']['type'], $_SESSION['user']['id'],$config['post_job_amount'],$config))
			{
				if(isset($_POST['Submit']))
				{				
					$errors = array();
					
					foreach($_POST as $key=>$value)
					{
						$_POST[$key] = strip_tags($value);
					}
					
					if(strlen($_POST['job_title']) <= 0)
					{
						$errors[]['message'] = $lang['JOBTITLEMISSING'];
					}
						
					if(strlen($_POST['company_name']) <= 0)
					{
						$errors[]['message'] = $lang['COMPANYNAMEMISSING'];
					}
						
					if(strlen($_POST['job_salary']) <= 0)
					{
						$errors[]['message'] = $lang['JOBSALARYMISSING'];
					}
					
					if(strlen($_POST['job_description']) <= 0)
					{
						$errors[]['message'] = $lang['JOBDESCRIPTIONMISSING'];
					}
					
					if(strlen($_POST['job_contact']) <= 0)
					{
						$errors[]['message'] = $lang['JOBCONTACTMISSING'];
					}
					
					if(!isset($_POST['jobtypes']))
					{
						$errors[]['message'] = $lang['JOBTYPEMISSING'];
					}
						
					if(count($errors) > 0)
					{					
						$page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/create_job.html');
						$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang));
						$page->SetLoop('JOBTYPES', get_job_types2($config));
						$page->SetLoop('COUNTRIES', get_country_list($config));
						$page->SetLoop('JOBCATS', get_job_cats($config));
						$page->SetLoop('ERRORS', $errors);
						$page->SetParameter ('USERNAME', $_SESSION['user']['name']);
						$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
						$page->CreatePageEcho($lang,$config);
					}
					else
					{
												
						mysql_query("INSERT INTO `".$config['db']['pre']."jobs` ( `job_id` , `buyer_id` , `job_title` , `job_company` , `job_category` , `job_location` , `job_country` , `job_type` , `job_salary` , `job_desc` , `job_contact` ) VALUES ('', '" . $_SESSION['user']['id'] . "', '" . validate_input($_POST['job_title']) . "', '" . validate_input($_POST['company_name']) . "', '" . validate_input($_POST['job_cat']) . "', '" . validate_input($_POST['job_location']) . "', '" . validate_input($_POST['job_country']) . "', '" . validate_input($_POST['jobtypes']) . "', '" . validate_input($_POST['job_salary']) . "', '" . validate_input($_POST['job_description']) . "', '" . validate_input($_POST['job_contact']) . "');");
						$job_id = mysql_insert_id();
						
						if($config['post_job_amount'])
						{
							minus_balance($_SESSION['user']['type'], $_SESSION['user']['id'],$config['post_job_amount'],$config);
							mysql_query("INSERT INTO `".$config['db']['pre']."transactions` ( `transaction_id` , `transaction_type` , `buyer_id` , `provider_id` , `transaction_ip` , `transaction_time` , `transaction_amount` , `transaction_description` , `transaction_method` ) VALUES ('', 'adm', '" . $_SESSION['user']['id'] . "', '0', '" . encode_ip($_SERVER,$_ENV) . "', '" . time() . "', '" . $config['post_job_amount'] . "', '".validate_input($lang['JOBCREATED'])."', 'withdraw');");
						}

						header("Location: job.php?id=" . $job_id);
						exit;
					}
				}
				else
				{
					
					$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/create_job.html");
					$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang));
					$page->SetLoop('ERRORS', array());
					$page->SetLoop('JOBTYPES', get_job_types2($config));
					$page->SetLoop('COUNTRIES', get_country_list($config));
					$page->SetLoop('JOBCATS', get_job_cats($config));
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
		message($lang['MUSTLOGINCREATEJOB'],$config,$lang);
	}
}
else
{
	message($lang['MUSTLOGINCREATEJOB'],$config,$lang);
}
?>