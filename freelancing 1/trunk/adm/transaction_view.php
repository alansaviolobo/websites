<?php 
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.admin.php');
require_once('class.menu.php');

$obj = new Menu();
$nav = $obj->get_js_menu(0,$config);

if(isset($_GET['id']))
{
	$_POST['list'][$_GET['id']] = $_GET['id'];
}

if(isset($_POST))
{
	if(count($_POST) > 1)
	{
		if(isset($_POST['reverse']))
		{
			foreach ($_POST['reverse'] as $key => $value) 
			{
				if($value == 1)
				{
					$query = "SELECT transaction_description,transaction_type,buyer_id,provider_id,transaction_amount,transaction_method FROM `".$config['db']['pre']."transactions` WHERE transaction_id='" . $key . "' LIMIT 1";
					$query_result = @mysql_query ($query) OR error(mysql_error());
					while ($info = @mysql_fetch_array($query_result))
					{
						$type = $info['transaction_type'];
						$buy_id = $info['buyer_id'];
						$pro_id = $info['provider_id'];
						$amount = $info['transaction_amount'];
						$method = $info['transaction_method'];
						$description = $info['transaction_description'];
					}
				
					if($type == 'buy')
					{
						$query2 = "SELECT balance_amount FROM `".$config['db']['pre']."providers_balance` WHERE provider_id='" . $pro_id . "' LIMIT 1";
						$query_result2 = @mysql_query ($query2) OR error(mysql_error());
						while ($info2 = @mysql_fetch_array($query_result2))
						{
							$pro_balance = $info2['balance_amount'];
						}
						
						$query2 = "SELECT balance_amount FROM `".$config['db']['pre']."buyers_balance` WHERE buyer_id='" . $buy_id . "' LIMIT 1";
						$query_result2 = @mysql_query ($query2) OR error(mysql_error());
						while ($info2 = @mysql_fetch_array($query_result2))
						{
							$buy_balance = $info2['balance_amount'];
						}
						
						if($pro_balance<$amount)
						{
							echo '<link rel="stylesheet" type="text/css" href="images/style.css">';
							echo "\n<table align='left' cellpadding=5><tr><td><br>There are insufficient funds to be able to ".$method." the funds.";
							echo "<br><br><a href='#' onclick='history.back()'>Back</a></td></tr></table>";
							die();
						}						
						
						$deducted = $pro_balance - $amount;
						$added = $buy_balance + $amount;
						
						mysql_query("UPDATE `".$config['db']['pre']."providers_balance` SET `balance_amount` = '" . $deducted . "' WHERE `provider_id` = '" . $pro_id . "' LIMIT 1 ;");
						mysql_query("UPDATE `".$config['db']['pre']."buyers_balance` SET `balance_amount` = '" . $added . "' WHERE `buyer_id` = '" . $buy_id . "' LIMIT 1 ;");
					}
					elseif($type == 'pro')
					{
						$query2 = "SELECT balance_amount FROM `".$config['db']['pre']."providers_balance` WHERE provider_id='" . $pro_id . "' LIMIT 1";
						$query_result2 = @mysql_query ($query2) OR error(mysql_error());
						while ($info2 = @mysql_fetch_array($query_result2))
						{
							$pro_balance = $info2['balance_amount'];
						}
						
						$query2 = "SELECT balance_amount FROM `".$config['db']['pre']."buyers_balance` WHERE buyer_id='" . $buy_id . "' LIMIT 1";
						$query_result2 = @mysql_query ($query2) OR error(mysql_error());
						while ($info2 = @mysql_fetch_array($query_result2))
						{
							$buy_balance = $info2['balance_amount'];
						}
						
						if($buy_balance<$amount)
						{
							echo '<link rel="stylesheet" type="text/css" href="images/style.css">';
							echo "\n<table align='left' cellpadding=5><tr><td><br>There are insufficient funds to be able to ".$method." the funds.";
							echo "<br><br><a href='#' onclick='history.back()'>Back</a></td></tr></table>";
							die();
						}
						
						
						$added = $pro_balance + $amount;
						$deducted = $buy_balance - $amount;
						
						mysql_query("UPDATE `".$config['db']['pre']."providers_balance` SET `balance_amount` = '" . $added . "' WHERE `provider_id` = '" . $pro_id . "' LIMIT 1 ;");
						mysql_query("UPDATE `".$config['db']['pre']."buyers_balance` SET `balance_amount` = '" . $deducted . "' WHERE `buyer_id` = '" . $buy_id . "' LIMIT 1 ;");
					}
					elseif($type == 'adm')
					{
						if($buy_id=='0')
						{
							$user_type='provider';
							$user_new_id=$pro_id;
						}
						else
						{
							$user_type='buyer';
							$user_new_id=$buy_id;
						}
						$query2 = "SELECT balance_amount FROM `".$config['db']['pre'].$user_type."s_balance` WHERE ".$user_type."_id='" . $user_new_id . "' LIMIT 1";
						$query_result2 = @mysql_query ($query2) OR error(mysql_error());
						while ($info2 = @mysql_fetch_array($query_result2))
						{
							$balance = $info2['balance_amount'];
						}
						
						if($balance<$amount)
						{
							echo '<link rel="stylesheet" type="text/css" href="images/style.css">';
							echo "\n<table align='left' cellpadding=5><tr><td><br>There are insufficient funds to be able to ".$method." the funds.";
							echo "<br><br><a href='#' onclick='history.back()'>Back</a></td></tr></table>";
							die();
						}						
						
						if($method=='deposit')
						{
							$final_balance = $balance - $amount;
						}
						elseif($method=='withdraw')
						{
							$final_balance = $balance + $amount;
						}
						
						mysql_query("UPDATE `".$config['db']['pre'].$user_type."s_balance` SET `balance_amount` = '" . $final_balance . "' WHERE `".$user_type."_id` = '" . $user_new_id . "' LIMIT 1 ;") OR error(mysql_error());					
				
					}
				
					if($method=="deposit")
					{
						$final_method = "withdraw";
					}
					elseif($method=="withdraw")
					{
						$final_method = "deposit";
					}
					
					mysql_query("UPDATE `".$config['db']['pre']."transactions` SET `transaction_proc` = '4' WHERE transaction_id='" . $key . "' ;") OR error(mysql_error());					
					mysql_query("INSERT INTO `".$config['db']['pre']."transactions` ( `transaction_id` , `transaction_type` , `transaction_method` , `buyer_id` , `provider_id` , `transaction_ip` , `transaction_time` , `transaction_amount` , `transaction_description` , `transaction_proc` ) VALUES ('', '".$type."', '".$final_method."', '" . $buy_id . "', '" . $pro_id . "', '" . encode_ip($_SERVER,$_ENV) . "', '" . time() . "', '" . $amount . "', 'Transaction Reversal ID: ".$key." (\"".$description."\")','3');") OR error(mysql_error());	
				}
			}
			
			transfer($config,'transactions_view.php','Transactions Reversed');
			exit;
		}
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title><?php echo $config['site_title']; ?> Admin - View Transaction</title>
<link rel="stylesheet" type="text/css" href="images/style.css">
<SCRIPT LANGUAGE="JavaScript" SRC="menu/JSCookMenu.js"></SCRIPT>
<LINK REL="stylesheet" HREF="menu/themes/Office/theme.css" TYPE="text/css">
<SCRIPT LANGUAGE="JavaScript" SRC="menu/themes/Office/theme.js"></SCRIPT>
<style type="text/css">
<!--
.style2 {	color: #FFFFFF;
	font-size: 12px;
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
}
.style5 {font-size: 12px; font-family: Arial, Helvetica, sans-serif; }
-->
</style>
</head>
<body>
<!--Start top-->
<table width="850" cellpadding="0" cellspacing="0" border="0" align="center">
<tr>
<td width="100%" background="images/bg_top.gif" height="42"><a href="index.php"><img src="images/logo.gif" width="147" height="31" hspace="10" border="0"></a></td>
</tr>
<tr>
<td><img src="images/dot.gif" width="1" height="1" alt=""></td>
</tr>
</table>
<!--End top-->
<!--Start topmenu-->
<table width="850" cellpadding="0" cellspacing="0" border="0" align="center">
<tr>
<td bgcolor="#F0F0F0" height="25" style="padding-left:20px;" id="menu">
</td><SCRIPT language="JavaScript" type="text/javascript">
			var myMenu =
				
			// Start the menu
[
<?php echo $nav; ?>
];				

			// Output the menu
			cmDraw ('menu', myMenu, 'hbr', cmThemeOffice, 'ThemeOffice');
			</SCRIPT>
</tr>
<tr>
<td bgcolor="#333333"><img src="images/dot.gif" width="1" height="1" alt=""></td>
</tr>
</table>
<!--End topmenu-->
<br>
<!--Start heading page-->
<table width="850" cellpadding="0" cellspacing="0" border="0" align="center">
<tr>
<td class="heading"><img src="images/icon_viewall.gif" width="26" height="26" alt="" align="absmiddle" hspace="5">View Transactions </td>
</tr>
<tr>
<td bgcolor="#333333"><img src="images/dot.gif" width="1" height="1" alt=""></td>
</tr>
</table>
<!--End heading page-->

<!--Start form-->
<br>
<table width="850" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #CCCCCC;" align="center">
<tr>
<td align="center" bgcolor="#F6F6F6" style="padding:15px;">
  <form action="" method="post" name="f1" id="f1">
<?
$count = 0;
$sql = "SELECT transaction_id,transaction_status,transaction_type,transaction_method,transaction_time,transaction_amount,buyer_id,provider_id,transaction_ip,transaction_description,transaction_proc FROM ".$config['db']['pre']."transactions ";

foreach ($_POST['list'] as $value) 
{
	if($count == 0)
	{
		$sql.= "WHERE transaction_id='" . $value . "'";
	}
	ELSE
	{
		$sql.= " OR transaction_id='" . $value . "'";
	}
	
	$count++;
} 
$sql.= " LIMIT " . count($_POST['list']);

$query_result = mysql_query($sql);
while ($info = @mysql_fetch_array($query_result))
{

	if($info['transaction_status'] == 1)
	{
		$status = 'Pending';
	}
	ELSE
	{
		$status = 'Complete';
	}

	$query2 = "SELECT buyer_username FROM `".$config['db']['pre']."buyers` WHERE buyer_id='" . $info['buyer_id'] . "' LIMIT 1";
	$query_result2 = @mysql_query ($query2) OR error(mysql_error());
	while ($info2 = @mysql_fetch_array($query_result2))
	{
		$buyer_username = $info2['buyer_username'];
	}

	$query2 = "SELECT provider_username FROM `".$config['db']['pre']."providers` WHERE provider_id='" . $info['provider_id'] . "' LIMIT 1";
	$query_result2 = @mysql_query ($query2) OR error(mysql_error());
	while ($info2 = @mysql_fetch_array($query_result2))
	{
		$provider_username = $info2['provider_username'];
	}
?>
<table width="70%" cellpadding="0" cellspacing="2" border="0">
  <tr>
    <td width="35%"><strong>Transaction ID</strong></td>
    <td>:
        <input name="id[<?php echo $info['transaction_id']; ?>]" type="Text" class="textbox" style="width:316px" value="<?php echo $info['transaction_id']; ?>" disabled></td>
        <input name="id[<?php echo $info['transaction_id']; ?>]" type="hidden" class="textbox" style="width:316px" value="<?php echo $info['transaction_id']; ?>"></td>
  </tr>
  <tr>
    <td width="35%"><strong>Transaction Status</strong></td>
    <td>:
        <input name="status[<?php echo $info['transaction_id']; ?>]" type="Text" class="textbox" style="width:316px" value="<?php echo $status; ?>" disabled></td>
  </tr>
  <tr>
    <td width="35%"><strong>Transaction Method</strong></td>
    <td>:
        <input name="title[<?php echo $info['transaction_id']; ?>]" type="Text" class="textbox" style="width:316px" value="<?php echo $info['transaction_method']; ?>" disabled></td>
  </tr>
  <tr>
    <td><strong>Transaction Time </strong></td>
    <td>:
        <input name="title[<?php echo $info['transaction_id']; ?>]" type="Text" class="textbox" style="width:316px" value="<?php echo date('m/d/y \a\t G:i:s', $info['transaction_time']); ?>" disabled></td>
  </tr>
  <tr>
    <td><strong>Transaction Amount</strong></td>
    <td>:
        <input name="title[<?php echo $info['transaction_id']; ?>]" type="Text" class="textbox" style="width:316px" value="<?php echo $config['currency_sign'].$info['transaction_amount']; ?>" disabled></td>
  </tr>
  <tr>
    <td><strong>Transaction IP </strong></td>
    <td>:
        <input name="title[<?php echo $info['transaction_id']; ?>]" type="Text" class="textbox" style="width:316px" value="<?php echo $info['transaction_ip']; ?>" disabled></td>
  </tr>
   <tr>
    <td><strong>Transaction Description</strong></td>
    <td>:
        <input name="title[<?php echo $info['transaction_id']; ?>]" type="Text" class="textbox" style="width:316px" value="<?php echo $info['transaction_description']; ?>" disabled></td>
  </tr>
   <tr>
    <td><strong>Transaction From</strong></td>
    <td>:
        <input name="title[<?php echo $info['transaction_id']; ?>]" type="Text" class="textbox" style="width:316px" value="<?php if($info['transaction_type'] == 'adm'){ echo "Admin"; } ELSEIF ($info['transaction_type'] == 'fre'){ echo $provider_username; } ELSE { echo $buyer_username; } ?>" disabled></td>
  </tr>
   <tr>
    <td><strong>Transaction To</strong></td>
    <td>:
        <input name="title[<?php echo $info['transaction_id']; ?>]" type="Text" class="textbox" style="width:316px" value="<?php if($info['transaction_type'] == 'adm') { if($info['buyer_id'] == 0) { echo $provider_username; }ELSE{ echo $buyer_username; } } elseif($info['transaction_type'] == 'fre'){ echo $buyer_username; }ELSE{ echo $provider_username; } ?>" disabled></td>
  </tr>
   <tr>
    <td><strong>Reverse Transaction?</strong></td>
    <td>:
        <input <?php if($info['transaction_proc'] == "4" OR $info['transaction_proc'] == "3"){ echo "disabled ";} ?>name="reverse[<?php echo $info['transaction_id']; ?>]" type="radio" value="1">Yes <input <?php if($info['transaction_proc'] == "4" OR $info['transaction_proc'] == "3"){ echo "disabled ";} ?>name="reverse[<?php echo $info['transaction_id']; ?>]" type="radio" value="0" checked> No <?php if($info['transaction_proc'] == "4" OR $info['transaction_proc'] == "3"){ echo "(Cannot Reverse This Transaction)"; } ?></td>
  </tr>
</table>
<br><br>
<?
}
?>
<br>
<br>
<table width="70%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="35%">&nbsp;</td>
    <td>&nbsp;
      <input name="Submit" type="submit" class="button" value="Submit">
&nbsp;
<input name="Reset" type="reset" class="button" value="Reset"></td>
  </tr>
</table>
<br>
        </form>
</td>
</tr>
</table>
<!--End form-->
<br><br>
<!--Start bottom-->
<table width="850" cellpadding="0" cellspacing="0" border="0" align="center">
<tr>
<td bgcolor="#333333"><img src="images/dot.gif" width="1" height="1" alt=""></td>
</tr>
<tr>
<td style="padding:15px;" align="center">
<span class="copyright">Copyright &copy; 2008 <a href="http://www.technotrix.co.in" class="copyright" target="_blank">Technotrix</a> All Rights Reserved.</span></td>
</tr>
</table>
<!--End bottom-->
</body>
</html>
