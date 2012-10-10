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
$settings = get_settings('transactions.php',$config);

if(checkloggedin())
{
	$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/transactions.html");
	$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['TRANSACTIONS']));
	
	$transactions = array();
	$count = 0;
	
	$query = "SELECT provider_id,buyer_id,transaction_type,transaction_status,transaction_time,transaction_amount,transaction_description,transaction_method FROM ".$config['db']['pre']."transactions WHERE " . $_SESSION['user']['type'] . "_id='" . $_SESSION['user']['id'] . "' ORDER BY transaction_id DESC";
	$query_result = mysql_query($query);
	while ($info = @mysql_fetch_array($query_result))
	{
		if($info['transaction_method'] == 'transfer')
		{
			if($_SESSION['user']['type'] == 'buyer')
			{
				if($info['transaction_type'] == 'web')
				{
					$transactions[$count]['sign'] = '-';
				} 
				elseif($info['transaction_type'] == 'fre')
				{
					$transactions[$count]['sign'] = '+';
				}
				if($info['transaction_type'] == 'buy')
				{
					$transactions[$count]['sign'] = '-';
				} 
				elseif($info['transaction_type'] == 'pri')
				{
					$transactions[$count]['sign'] = '+';
				}
				else
				{
					$transactions[$count]['sign'] = '+';
				}
			}
			else
			{
				if($info['transaction_type'] == 'web')
				{
					$transactions[$count]['sign'] = '+';
				} 
				elseif($info['transaction_type'] == 'fre')
				{
					$transactions[$count]['sign'] = '-';
				}
				elseif($info['transaction_type'] == 'buy')
				{
					$transactions[$count]['sign'] = '+';
				} 
				elseif($info['transaction_type'] == 'pri')
				{
					$transactions[$count]['sign'] = '-';
				}
				else
				{
					$transactions[$count]['sign'] = '+';
				}
			}
		}
		elseif($info['transaction_method'] == 'escrow')
		{
			if($_SESSION['user']['type'] == 'buyer')
			{
				if($info['transaction_type'] == 'web')
				{
					$transactions[$count]['sign'] = '-';
				} 
				elseif($info['transaction_type'] == 'fre')
				{
					$transactions[$count]['sign'] = '+';
				}
				if($info['transaction_type'] == 'buy')
				{
					$transactions[$count]['sign'] = '-';
				} 
				elseif($info['transaction_type'] == 'pri')
				{
					$transactions[$count]['sign'] = '+';
				}
				else
				{
					$transactions[$count]['sign'] = '+';
				}
			}
			else
			{
				if($info['transaction_type'] == 'web')
				{
					$transactions[$count]['sign'] = '+';
				} 
				elseif($info['transaction_type'] == 'fre')
				{
					$transactions[$count]['sign'] = '-';
				}
				elseif($info['transaction_type'] == 'buy')
				{
					$transactions[$count]['sign'] = '+';
				} 
				elseif($info['transaction_type'] == 'pri')
				{
					$transactions[$count]['sign'] = '-';
				}
				else
				{
					$transactions[$count]['sign'] = '+';
				}
			}
		}
		elseif($info['transaction_method'] == 'deposit')
		{
			$transactions[$count]['sign'] = '+';		
		}
		elseif($info['transaction_method'] == 'withdraw')
		{
			$transactions[$count]['sign'] = '-';		
		}
		
		$transactions[$count]['date'] = date("n/j/Y", $info['transaction_time']);
		
		if($info['transaction_status'] == 1)
		{
			$transactions[$count]['status'] = $lang['PENDING'];
		}
		else
		{
			$transactions[$count]['status'] = $lang['COMPLETED'];
		}
		
		$transactions[$count]['amount'] = $info['transaction_amount'];
		$transactions[$count]['description'] = $info['transaction_description'];
		
		$count++;
	}
	
	$page->SetLoop ('TRANSACTIONS', $transactions);
	
	$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
	$page->CreatePageEcho($lang,$config);
}
else
{
	header("Location: login.php");
	exit;
}
?>