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

// Get Settings
$settings = get_settings('escrow.php',$config);

if(!$config['escrow_en'])
{
	message($lang['DISABLEDESCROW'], $config, $lang,'',false);
}

if(checkloggedin())
{
	if(isset($_GET['approve']))
	{
		if($_SESSION['user']['type'] == 'buyer')
		{
			$escrow_exists = mysql_fetch_array(mysql_query("SELECT * FROM `".$config['db']['pre']."escrow` WHERE escrow_id='".validate_input($_GET['approve'])."' AND escrow_status='0' AND escrow_from='buy' AND buyer_id='".validate_input($_SESSION['user']['id'])."' LIMIT 1"));
		
			if(isset($escrow_exists['escrow_id']))
			{
				if($escrow_exists['escrow_id'])
				{
					mysql_query("UPDATE `".$config['db']['pre']."escrow` SET `escrow_status` = '1' WHERE `escrow_id` = '".validate_input($escrow_exists['escrow_id'])."' LIMIT 1 ;");
					
					mysql_query("UPDATE `".$config['db']['pre']."providers_balance` SET `balance_amount` = balance_amount+".$escrow_exists['escrow_amount']." WHERE `provider_id` = '" . $escrow_exists['provider_id'] . "' LIMIT 1 ;");
					mysql_query("INSERT INTO `".$config['db']['pre']."transactions` ( `transaction_type` , `transaction_method` , `buyer_id` , `provider_id` , `transaction_ip` , `transaction_time` , `transaction_amount` , `transaction_description` ) VALUES ('buy', 'escrow', '" . $_SESSION['user']['id'] . "', '" . $escrow_exists['provider_id'] . "', '" . encode_ip($_SERVER,$_ENV) . "', '" . time() . "', '" . validate_input($escrow_exists['escrow_amount']) . "', 'Escrow Transfer from ".$lang['BUYERU']." to ".$lang['PROVIDERU']."');");
				}
			}
		}
		else
		{
			$escrow_exists = mysql_fetch_array(mysql_query("SELECT * FROM `".$config['db']['pre']."escrow` WHERE escrow_id='".validate_input($_GET['approve'])."' AND escrow_status='0' AND escrow_from='pro' AND provider_id='".validate_input($_SESSION['user']['id'])."' LIMIT 1"));
		
			if(isset($escrow_exists['escrow_id']))
			{
				if($escrow_exists['escrow_id'])
				{
					mysql_query("UPDATE `".$config['db']['pre']."escrow` SET `escrow_status` = '1' WHERE `escrow_id` = '".validate_input($escrow_exists['escrow_id'])."' LIMIT 1 ;");
					
					mysql_query("UPDATE `".$config['db']['pre']."buyers_balance` SET `balance_amount` = balance_amount+".$escrow_exists['escrow_amount']." WHERE `buyer_id` = '" . $escrow_exists['buyer_id'] . "' LIMIT 1 ;");				
					mysql_query("INSERT INTO `".$config['db']['pre']."transactions` ( `transaction_type` , `transaction_method` , `buyer_id` , `provider_id` , `transaction_ip` , `transaction_time` , `transaction_amount` , `transaction_description` ) VALUES ('pri', 'escrow', '" . $escrow_exists['buyer_id'] . "', '" . $_SESSION['user']['id'] . "', '" . encode_ip($_SERVER,$_ENV) . "', '" . time() . "', '" . validate_input($escrow_exists['escrow_amount']) . "', 'Escrow Transfer from ".$lang['PROVIDERU']." to ".$lang['BUYERU']."');");
					
				}
			}
		}
	
		header("Location: manage.php");
		exit;
	}
	
	if(isset($_GET['cancel']))
	{	
		if($_SESSION['user']['type'] == 'buyer')
		{
			$escrow_exists = mysql_fetch_array(mysql_query("SELECT * FROM `".$config['db']['pre']."escrow` WHERE escrow_id='".validate_input($_GET['cancel'])."' AND escrow_status='0' AND escrow_from='pro' AND buyer_id='".validate_input($_SESSION['user']['id'])."' LIMIT 1"));
		
			if(isset($escrow_exists['escrow_id']))
			{
				if($escrow_exists['escrow_id'])
				{
					mysql_query("UPDATE `".$config['db']['pre']."escrow` SET `escrow_status` = '2' WHERE `escrow_id` = '".validate_input($escrow_exists['escrow_id'])."' LIMIT 1 ;");
				
					mysql_query("UPDATE `".$config['db']['pre']."providers_balance` SET `balance_amount` = balance_amount+".$escrow_exists['escrow_amount']." WHERE `provider_id` = '" . $escrow_exists['provider_id'] . "' LIMIT 1 ;");				
					mysql_query("INSERT INTO `".$config['db']['pre']."transactions` ( `transaction_type` , `transaction_method` , `buyer_id` , `provider_id` , `transaction_ip` , `transaction_time` , `transaction_amount` , `transaction_description` ) VALUES ('pri', 'escrow', '" . $escrow_exists['buyer_id'] . "', '" . $_SESSION['user']['id'] . "', '" . encode_ip($_SERVER,$_ENV) . "', '" . time() . "', '" . validate_input($escrow_exists['escrow_amount']) . "', 'Escrow Cancel from ".$lang['PROVIDERU']." to ".$lang['BUYERU']."');");
				}
			}
		}
		else
		{
			$escrow_exists = mysql_fetch_array(mysql_query("SELECT * FROM `".$config['db']['pre']."escrow` WHERE escrow_id='".validate_input($_GET['cancel'])."' AND escrow_status='0' AND escrow_from='buy' AND provider_id='".validate_input($_SESSION['user']['id'])."' LIMIT 1"));
		
			if(isset($escrow_exists['escrow_id']))
			{
				if($escrow_exists['escrow_id'])
				{
					mysql_query("UPDATE `".$config['db']['pre']."escrow` SET `escrow_status` = '2' WHERE `escrow_id` = '".validate_input($escrow_exists['escrow_id'])."' LIMIT 1 ;");					
					
					mysql_query("UPDATE `".$config['db']['pre']."buyers_balance` SET `balance_amount` = balance_amount+".$escrow_exists['escrow_amount']." WHERE `buyer_id` = '" . $escrow_exists['buyer_id'] . "' LIMIT 1 ;");
					mysql_query("INSERT INTO `".$config['db']['pre']."transactions` ( `transaction_type` , `transaction_method` , `buyer_id` , `provider_id` , `transaction_ip` , `transaction_time` , `transaction_amount` , `transaction_description` ) VALUES ('buy', 'escrow', '" . $_SESSION['user']['id'] . "', '" . $escrow_exists['provider_id'] . "', '" . encode_ip($_SERVER,$_ENV) . "', '" . time() . "', '" . validate_input($escrow_exists['escrow_amount']) . "', 'Escrow Cancel from ".$lang['BUYERU']." to ".$lang['PROVIDERU']."');");
					
				}
			}
		}
	
		header("Location: manage.php");
		exit;
	}

	if($_SESSION['user']['type'] == 'provider')
	{
		if($settings['allow_provider_escrow'] == '0')
		{
			message($lang['DISABLEDESCROWPROVIDER'], $config, $lang,'',false);
		}
	}

	if(isset($_POST['Submit']))
	{
		if($config['transfer_min'] > $_POST['amount'])
		{
			message($lang['TRANSFERATLEAST'].$config['currency_sign'].$config['transfer_min'], $config, $lang);
		}
	
		if($_SESSION['user']['type'] == 'buyer')
		{
			$query2 = "SELECT balance_amount FROM `".$config['db']['pre']."buyers_balance` WHERE buyer_id='" . $_SESSION['user']['id'] . "' LIMIT 1";
			$query_result2 = @mysql_query ($query2) OR error(mysql_error());
			while ($info2 = @mysql_fetch_array($query_result2))
			{
				$balance = $info2['balance_amount'];
			}
			
			if($balance < $_POST['amount'])
			{
				message($lang['NO_MONEY'],$config,$lang);
			}
			else
			{
				$deducted = $balance - $_POST['amount'];
				
				$query2 = "SELECT provider_id FROM `".$config['db']['pre']."providers` WHERE provider_username='" . addslashes($_POST['username']) . "' LIMIT 1";
				$query_result2 = @mysql_query ($query2) OR error(mysql_error());
				$num_rows = mysql_num_rows($query_result2);
				
				if($num_rows == 1)
				{
					while ($info2 = @mysql_fetch_array($query_result2))
					{
						$user_id = $info2['provider_id'];
					}
					
					$query2 = "SELECT balance_amount FROM `".$config['db']['pre']."providers_balance` WHERE provider_id='" . $user_id . "' LIMIT 1";
					$query_result2 = @mysql_query ($query2) OR error(mysql_error());
					while ($info2 = @mysql_fetch_array($query_result2))
					{
						$user_balance = $info2['balance_amount'];
					}
					
					$added = $user_balance + $_POST['amount'];

					mysql_query("UPDATE `".$config['db']['pre']."buyers_balance` SET `balance_amount` = '" . $deducted . "' WHERE `buyer_id` = '" . $_SESSION['user']['id'] . "' LIMIT 1 ;");
	
					mysql_query("INSERT INTO `".$config['db']['pre']."escrow` (`escrow_from` ,`escrow_status` ,`escrow_amount` ,`escrow_desc` ,`buyer_id` ,`provider_id`) VALUES ('buy', '0', '".validate_input($_POST['amount'])."', '', '" . $_SESSION['user']['id'] . "', '".$user_id."');");
				
					message($lang['ESCROWCREATED'],$config,$lang);
				}
				else
				{
					message($lang['SORRYUSER'],$config,$lang);
				}
			}
		}
		else
		{
			$query2 = "SELECT balance_amount FROM `".$config['db']['pre']."providers_balance` WHERE provider_id='" . $_SESSION['user']['id'] . "' LIMIT 1";
			$query_result2 = mysql_query ($query2) OR error(mysql_error());
			while ($info2 = mysql_fetch_array($query_result2))
			{
				$balance = $info2['balance_amount'];
			}
			
			if($_POST['amount'] > $balance)
			{
				message($lang['NO_MONEY'],$config,$lang);
			}
			else
			{
				$deducted = ($balance - $_POST['amount']);
			
				$query2 = "SELECT buyer_id FROM `".$config['db']['pre']."buyers` WHERE buyer_username='" . addslashes($_POST['username']) . "' LIMIT 1";
				$query_result2 = mysql_query ($query2) OR error(mysql_error());
				$num_rows = mysql_num_rows($query_result2);
				
				if($num_rows == 1)
				{
					while ($info2 = @mysql_fetch_array($query_result2))
					{
						$user_id = $info2['buyer_id'];
					}
					
					$query2 = "SELECT balance_amount FROM `".$config['db']['pre']."buyers_balance` WHERE buyer_id='" . $user_id . "' LIMIT 1";
					$query_result2 = @mysql_query ($query2) OR error(mysql_error());
					while ($info2 = @mysql_fetch_array($query_result2))
					{
						$user_balance = $info2['balance_amount'];
					}
					
					$added = $user_balance + $_POST['amount'];
					
					mysql_query("UPDATE `".$config['db']['pre']."providers_balance` SET `balance_amount` = '" . $deducted . "' WHERE `provider_id` = '" . $_SESSION['user']['id'] . "' LIMIT 1 ;");				
					
					mysql_query("INSERT INTO `".$config['db']['pre']."escrow` (`escrow_from` ,`escrow_status` ,`escrow_amount` ,`escrow_desc` ,`buyer_id` ,`provider_id`) VALUES ('pro', '0', '".validate_input($_POST['amount'])."', '', '" . $user_id . "', '".$_SESSION['user']['id']."');");
					
				
					message($lang['ESCROWCREATED'],$config,$lang);
				}
				else
				{
					message($lang['SORRYUSER'],$config,$lang);
				}
			}
		}
	}
	else
	{
		if($_SESSION['user']['type'] == 'buyer')
		{
			$transfer_users = array();
			$user_where = '';
			
			$query2 = "SELECT provider_id FROM `".$config['db']['pre']."transactions` WHERE buyer_id='" . $_SESSION['user']['id'] . "' AND transaction_method='escrow' GROUP BY provider_id";
			$query_result2 = mysql_query ($query2) OR error(mysql_error());
			while ($info2 = mysql_fetch_array($query_result2))
			{
				if($info2['provider_id'] != 0)
				{
					$transfer_users[$info2['provider_id']] = '';
					
					if($user_where == '')
					{
						$user_where = "provider_id='".$info2['provider_id']."'";
					}
					else
					{
						$user_where.= " OR provider_id='".$info2['provider_id']."'";
					}
				}
			}
			
			if($user_where != '')
			{
				$query2 = "SELECT provider_id,provider_username FROM `".$config['db']['pre']."providers` WHERE ".$user_where." LIMIT ".count($transfer_users);
				$query_result2 = mysql_query ($query2) OR error(mysql_error());
				while ($info2 = mysql_fetch_array($query_result2))
				{
					$transfer_users[$info2['provider_id']]['username'] = $info2['provider_username'];
				}
			}
		
			$page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/escrow.html');
			$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['ESCROWM']));
			$page->SetParameter ('TYPE', $lang['PROVIDERU']);
			$page->SetLoop ('USER_LIST',$transfer_users);
			$page->SetParameter ('NUM_USERS',count($transfer_users));
			$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
			$page->CreatePageEcho($lang,$config);
		}
		else
		{
			$transfer_users = array();
			$user_where = '';
			
			$query2 = "SELECT buyer_id FROM `".$config['db']['pre']."transactions` WHERE provider_id='" . $_SESSION['user']['id'] . "' AND transaction_method='escrow' GROUP BY buyer_id";
			$query_result2 = mysql_query ($query2) OR error(mysql_error());
			while ($info2 = mysql_fetch_array($query_result2))
			{
				if($info2['buyer_id'] != 0)
				{
					$transfer_users[$info2['buyer_id']] = '';
					
					if($user_where == '')
					{
						$user_where = "buyer_id='".$info2['buyer_id']."'";
					}
					else
					{
						$user_where.= " OR buyer_id='".$info2['buyer_id']."'";
					}
				}
			}
			
			if($user_where != '')
			{
				$query2 = "SELECT buyer_id,buyer_username FROM `".$config['db']['pre']."buyers` WHERE ".$user_where." LIMIT ".count($transfer_users);
				$query_result2 = mysql_query ($query2) OR error(mysql_error());
				while ($info2 = mysql_fetch_array($query_result2))
				{
					$transfer_users[$info2['buyer_id']]['username'] = $info2['buyer_username'];
				}
			}
		
			$page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/escrow.html');
			$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['ESCROWM']));
			$page->SetParameter ('TYPE', $lang['BUYERU']);
			$page->SetLoop ('USER_LIST',$transfer_users);
			$page->SetParameter ('NUM_USERS',count($transfer_users));
			$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
			$page->CreatePageEcho($lang,$config);
		}
	}
}
else
{
	header("Location: login.php");
	exit;
}
?>