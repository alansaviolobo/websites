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

if(!$config['transfer_en'])
{
	message($lang['DISABLEDTRANSFER'], $config, $lang,'',false);
}

if(checkloggedin())
{
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
					mysql_query("UPDATE `".$config['db']['pre']."providers_balance` SET `balance_amount` = '" . $added . "' WHERE `provider_id` = '" . $user_id . "' LIMIT 1 ;");
					mysql_query("INSERT INTO `".$config['db']['pre']."transactions` ( `transaction_id` , `transaction_type` , `transaction_method` , `buyer_id` , `provider_id` , `transaction_ip` , `transaction_time` , `transaction_amount` , `transaction_description` ) VALUES ('', 'buy', 'transfer', '" . $_SESSION['user']['id'] . "', '" . $user_id . "', '" . encode_ip($_SERVER,$_ENV) . "', '" . time() . "', '" . addslashes($_POST['amount']) . "', '".addslashes($lang['TRANSTO'])." ".addslashes($_SESSION['user']['name'])." (".addslashes($lang['BUYERU']).") ".addslashes($lang['TO'])." " . addslashes($_POST['username']) . " (".addslashes($lang['PROVIDERU']).")');");
				
					message($lang['ENOUGH_MONEY'],$config,$lang);
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
					mysql_query("UPDATE `".$config['db']['pre']."buyers_balance` SET `balance_amount` = '" . $added . "' WHERE `buyer_id` = '" . $user_id . "' LIMIT 1 ;");
					mysql_query("INSERT INTO `".$config['db']['pre']."transactions` ( `transaction_id` , `transaction_type` , `transaction_method` , `buyer_id` , `provider_id` , `transaction_ip` , `transaction_time` , `transaction_amount` , `transaction_description` ) VALUES ('', 'pri', 'transfer', '" . $user_id . "', '" . $_SESSION['user']['id'] . "', '" . encode_ip($_SERVER,$_ENV) . "', '" . time() . "', '" . addslashes($_POST['amount']) . "', '".addslashes($lang['TRANSTO'])." ".addslashes($_SESSION['user']['name'])." (".addslashes($lang['PROVIDERU']).") to " . addslashes($_POST['username']) . " (".addslashes($lang['BUYERU']).")');");
				
					message($lang['ENOUGH_MONEY'],$config,$lang);
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
			
			$query2 = "SELECT provider_id FROM `".$config['db']['pre']."transactions` WHERE buyer_id='" . $_SESSION['user']['id'] . "' AND transaction_method='transfer' GROUP BY provider_id";
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
		
			$page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/transfer.html');
			$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['TRANSFERM']));
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
			
			$query2 = "SELECT buyer_id FROM `".$config['db']['pre']."transactions` WHERE provider_id='" . $_SESSION['user']['id'] . "' AND transaction_method='transfer' GROUP BY buyer_id";
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
		
			$page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/transfer.html');
			$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['TRANSFERM']));
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