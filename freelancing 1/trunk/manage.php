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

// Update Last Active Time
update_lastactive($config);

if(checkloggedin())
{
	check_negative_balance($config);

	if(check_account_frozen($_SESSION['user']['id'], $_SESSION['user']['type'],$config))
	{
		message($lang['ACCOUNTFROZEN'],$config,$lang);
	}
	else
	{
		if($_SESSION['user']['type'] == 'buyer')
		{
			$projects = array();
			
			$query = "SELECT project_id,project_title,project_bids,project_status FROM `".$config['db']['pre']."projects` WHERE (project_status='0' OR project_status='1') AND buyer_id='" . $_SESSION['user']['id'] . "' ORDER BY project_id";
			$query_result = @mysql_query ($query) OR error(mysql_error());
			while ($info = @mysql_fetch_array($query_result))
			{
				$projects[$info['project_id']]['id'] = $info['project_id'];
				$projects[$info['project_id']]['title'] = strip_tags(stripslashes($info['project_title']));
				$projects[$info['project_id']]['bids'] = $info['project_bids'];
				
				if($info['project_status'] == 0)
				{
					$projects[$info['project_id']]['status'] = $lang['OPEN'];
				}
				else
				{
					$projects[$info['project_id']]['status'] = $lang['FROZEN'];
				}
			}
			
			$escrow = array();
			
			$query = "SELECT escrow_id,escrow_amount,escrow_from,provider_id FROM `".$config['db']['pre']."escrow` WHERE buyer_id='" . $_SESSION['user']['id'] . "' AND escrow_status='0'";
			$query_result = @mysql_query ($query) OR error(mysql_error());
			while ($info = @mysql_fetch_array($query_result))
			{
				$escrow[$info['escrow_id']]['id'] = $info['escrow_id'];
				$escrow[$info['escrow_id']]['amount'] = $info['escrow_amount'];
				$escrow[$info['escrow_id']]['from'] = $info['escrow_from'];
				
				$from_nick = mysql_fetch_row(mysql_query("SELECT provider_username FROM `".$config['db']['pre']."providers` WHERE provider_id='" . $info['provider_id'] . "' LIMIT 1"));
				
				$escrow[$info['escrow_id']]['from_nick'] = $from_nick[0];
			}
			
			if(isset($_GET['b']))
			{
				print_r($escrow);
			}
			
			$rate = array();
			
			$query = "SELECT project_id,provider_id,project_title,provider_rated,project_paid FROM `".$config['db']['pre']."projects` WHERE project_status='2' AND buyer_id='" . $_SESSION['user']['id'] . "'";
			$query_result = @mysql_query ($query) OR error(mysql_error());
			while ($info = @mysql_fetch_array($query_result))
			{
				$rate[$info['project_id']]['project_id'] = $info['project_id'];
				$rate[$info['project_id']]['project_title'] = strip_tags(stripslashes($info['project_title']));
				if($info['provider_id'])
				{
					$rate[$info['project_id']]['provider_rated'] = $info['provider_rated'];
				}
				else
				{
					$rate[$info['project_id']]['provider_rated'] = 1;
					$rate[$info['project_id']]['username'] = '';
				}
				$rate[$info['project_id']]['provider_id'] = $info['provider_id'];
				
				$bid = mysql_fetch_row(mysql_query("SELECT bid_amount FROM `".$config['db']['pre']."bids` WHERE project_id='".validate_input($info['project_id'])."' AND user_id='".$info['provider_id']."' LIMIT 1"));

				$rate[$info['project_id']]['winbid'] = $bid[0];

				if($config['pay_type'])
				{
					if($info['project_paid'])
					{
						$rate[$info['project_id']]['paid'] = '1';
					}
					else
					{
						$rate[$info['project_id']]['paid'] = '0';
					}
				}
				else
				{
					$rate[$info['project_id']]['paid'] = '3';
				}
				
				$query2 = "SELECT provider_username FROM `".$config['db']['pre']."providers` WHERE provider_id='" . $info['provider_id'] . "' LIMIT 1";
				$query_result2 = @mysql_query ($query2) OR error(mysql_error());
				while ($info2 = @mysql_fetch_array($query_result2))
				{
					$rate[$info['project_id']]['username'] = $info2['provider_username'];
				}
			}

			$query2 = "SELECT balance_amount FROM `".$config['db']['pre']."buyers_balance` WHERE buyer_id='" . $_SESSION['user']['id'] . "' LIMIT 1";
			$query_result2 = @mysql_query ($query2) OR error(mysql_error());
			while ($info2 = @mysql_fetch_array($query_result2))
			{
				$balance = $info2['balance_amount'];
			}
			
			$amount = $balance;

			if(substr($amount, 0, 1) == '-')
			{
				$balance =  '-'.$config['currency_sign'] . substr($amount, 1);
			}
			else
			{
				$balance = $config['currency_sign']  . $amount;
			}
			
			$query2 = "SELECT buyer_reviews,buyer_rating FROM `".$config['db']['pre']."buyers` WHERE buyer_id='" . $_SESSION['user']['id'] . "' LIMIT 1";
			$query_result2 = @mysql_query ($query2) OR error(mysql_error());
			while ($info2 = @mysql_fetch_array($query_result2))
			{
				$reviews = $info2['buyer_reviews'];
				$rating = $info2['buyer_rating'];
			}
			
			$counta = 0;
			$countb = 0;
			
			$jobs = array();
			$pastjobs = array();
			
			$query = "SELECT cat_id,cat_title FROM `".$config['db']['pre']."jobs_categories`";
			$query_result = @mysql_query ($query) OR error(mysql_error());
			while ($info = @mysql_fetch_array($query_result))
			{
				$job_cats[$info['cat_id']] = $info['cat_title'];
			}
			
			$query = "SELECT job_id,job_title,job_company,job_category,job_status FROM `".$config['db']['pre']."jobs` WHERE buyer_id='".$_SESSION['user']['id']."' ORDER BY job_id DESC LIMIT " . $config['job_listings_limit'];
			$query_result = @mysql_query ($query) OR error(mysql_error());
			while ($info = @mysql_fetch_array($query_result))
			{
				if($info['job_status']=='0')
				{
					$jobs[$countb]['title'] = strip_tags(stripslashes($info['job_title']));
					$jobs[$countb]['id'] = $info['job_id'];
					$countb++;
				}
				elseif($info['job_status']=='1')
				{
					$pastjobs[$counta]['title'] = strip_tags(stripslashes($info['job_title']));
					$pastjobs[$counta]['id'] = $info['job_id'];
					$counta++;
				}
			}
			
			$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/manage_buyer.html");
			$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['MANAGEACC']));
			$page->SetLoop ('PROJECTS', $projects);
			$page->SetLoop ('JOBS', $jobs);
			$page->SetLoop ('PASTJOBS', $pastjobs);	
			$page->SetLoop ('RATE', $rate);
			$page->SetLoop ('ESCROW', $escrow);
			$page->SetParameter ('ESCROW_EN', $config['escrow_en']);
			$page->SetParameter ('RATING', $rating);
			$page->SetParameter ('REVIEWS', $reviews);
			$page->SetParameter ('USERNAME', $_SESSION['user']['name']);
			$page->SetParameter ('USER_ID', $_SESSION['user']['id']);
			$page->SetParameter ('BALANCE', $balance);
			$page->SetParameter ('PAY_TYPE', $config['pay_type']);
			$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
			$page->CreatePageEcho($lang,$config);
		}
		else
		{
			$bids = array();
			$count = 0;
			
			$query = "SELECT bid_id,project_id,bid_amount FROM `".$config['db']['pre']."bids` WHERE user_id='" . $_SESSION['user']['id'] . "' ORDER BY bid_id";
			$query_result = @mysql_query ($query) OR error(mysql_error());
			while ($info = @mysql_fetch_array($query_result))
			{	
				$query2 = "SELECT bid_amount FROM `".$config['db']['pre']."bids` WHERE project_id='" . $info['project_id'] . "' ORDER BY bid_amount LIMIT 1";
				$query_result2 = @mysql_query ($query2) OR error(mysql_error());
				while ($info2 = @mysql_fetch_array($query_result2))
				{	
					$lowest_bid = $info2['bid_amount'];
				}
			
				$query2 = "SELECT * FROM `".$config['db']['pre']."projects` WHERE project_id='" . $info['project_id'] . "' AND project_status!='2' LIMIT 1";
				$query_result2 = @mysql_query ($query2) OR error(mysql_error());
				while ($info2 = @mysql_fetch_array($query_result2))
				{
					$count++;
					
					$bids[$count]['count'] = $count;
					
					$bids[$count]['user_id'] = $_SESSION['user']['id'];
					$bids[$count]['bid_amount'] = $info['bid_amount'];
					$bids[$count]['lowest_bid'] = $lowest_bid;
					$bids[$count]['project_title'] = strip_tags(stripslashes($info2['project_title']));
					$bids[$count]['project_id'] = $info['project_id'];
					
					if($_SESSION['user']['id'] == $info2['provider_id'])
					{
						$bids[$count]['options'] = $lang['ACCEPTDENYB'];
						$bids[$count]['options_link'] = 'accept.php?id=' . $info['project_id'] . "&check=".$info2['checkstamp'];
					}
					else
					{
						$bids[$count]['options'] = $lang['RETRACTB'];
						$bids[$count]['options_link'] = 'retract.php?id=' . $info['bid_id'];
					}
					
					if($info2['project_status'] == 0)
					{
						$bids[$count]['status'] = $lang['OPEN'];
					}
					else
					{
						$bids[$count]['status'] = $lang['FROZEN'];
					}
					
				}
			}
			
			$escrow = array();
			
			$query = "SELECT escrow_id,escrow_amount,escrow_from,buyer_id FROM `".$config['db']['pre']."escrow` WHERE provider_id='" . $_SESSION['user']['id'] . "' AND escrow_status='0'";
			$query_result = @mysql_query ($query) OR error(mysql_error());
			while ($info = @mysql_fetch_array($query_result))
			{
				$escrow[$info['escrow_id']]['id'] = $info['escrow_id'];
				$escrow[$info['escrow_id']]['amount'] = $info['escrow_amount'];
				$escrow[$info['escrow_id']]['from'] = $info['escrow_from'];
				
				$from_nick = mysql_fetch_row(mysql_query("SELECT buyer_username FROM `".$config['db']['pre']."buyers` WHERE buyer_id='" . $info['buyer_id'] . "' LIMIT 1"));
				
				$escrow[$info['escrow_id']]['from_nick'] = $from_nick[0];
			}
			
			$rate = array();
			
			$query = "SELECT project_id,buyer_id,project_title,buyer_rated,project_paid FROM `".$config['db']['pre']."projects` WHERE project_status='2' AND provider_id='".$_SESSION['user']['id']."'";
			$query_result = @mysql_query ($query) OR error(mysql_error());
			while ($info = @mysql_fetch_array($query_result))
			{
				$rate[$info['project_id']]['project_title'] = strip_tags(stripslashes($info['project_title']));
				$rate[$info['project_id']]['project_id'] = $info['project_id'];
				$rate[$info['project_id']]['buyer_rated'] = $info['buyer_rated'];
				$rate[$info['project_id']]['buyer_id'] = $info['buyer_id'];

				$query2 = "SELECT buyer_username FROM `".$config['db']['pre']."buyers` WHERE buyer_id='" . $info['buyer_id'] . "' LIMIT 1";
				$query_result2 = @mysql_query ($query2) OR error(mysql_error());
				while ($info2 = @mysql_fetch_array($query_result2))
				{
					$rate[$info['project_id']]['username'] = $info2['buyer_username'];
				}
				
				if($config['pay_type'])
				{
					if($info['project_paid'])
					{
						$rate[$info['project_id']]['paid'] = '1';
					}
					else
					{
						$rate[$info['project_id']]['paid'] = '0';
					}
				}
				else
				{
					$rate[$info['project_id']]['paid'] = '3';
				}				
			}
			
			$query2 = "SELECT balance_amount FROM `".$config['db']['pre']."providers_balance` WHERE provider_id='" . $_SESSION['user']['id'] . "' LIMIT 1";
			$query_result2 = @mysql_query ($query2) OR error(mysql_error());
			while ($info2 = @mysql_fetch_array($query_result2))
			{
				$balance = $info2['balance_amount'];
			}
			
			$amount = $balance;
			
			if(substr($amount, 0, 1) == '-')
			{
				$balance =  '-'.$config['currency_sign'] . substr($amount, 1);
			}
			else
			{
				$balance = $config['currency_sign']  . $amount;
			}
			
			$query2 = "SELECT provider_reviews,provider_rating FROM `".$config['db']['pre']."providers` WHERE provider_id='" . $_SESSION['user']['id'] . "' LIMIT 1";
			$query_result2 = @mysql_query ($query2) OR error(mysql_error());
			while ($info2 = @mysql_fetch_array($query_result2))
			{
				$reviews = $info2['provider_reviews'];
				$rating = $info2['provider_rating'];
			}
			
			$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/manage_provider.html");
			$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$lang['MANAGEACC']));
			$page->SetLoop ('BIDS', $bids);
			$page->SetLoop ('RATE', $rate);
			$page->SetLoop ('ESCROW', $escrow);
			$page->SetParameter ('ESCROW_EN', $config['escrow_en']);
			$page->SetParameter ('RATING', $rating);
			$page->SetParameter ('REVIEWS', $reviews);
			$page->SetParameter ('USERNAME', $_SESSION['user']['name']);
			$page->SetParameter ('USER_ID', $_SESSION['user']['id']);
			$page->SetParameter ('ACCOUNT_TYPE', $_SESSION['user']['type']);
			$page->SetParameter ('BALANCE', $balance);
			$page->SetParameter ('PAY_TYPE', $config['pay_type']);
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