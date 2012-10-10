<?php 
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/lang/lang_'.$config['lang'].'.php');
require_once('class.menu.php');

db_connect($config);

if(isset($_POST))
{
	if(count($_POST) > 1)
	{
		if($_POST['currency'] == 'GBP')
		{
			$currency_sign = '&pound;';
			$currency_code = 'GBP';
		}
		elseif($_POST['currency'] == 'EUR')
		{
			$currency_sign = 'EUR ';
			$currency_code = 'EUR';
		}
		elseif($_POST['currency'] == 'AUD')
		{
			$currency_sign = 'A$';
			$currency_code = 'AUD';
		}
		elseif($_POST['currency'] == 'NZD')
		{
			$currency_sign = 'NZ$';
			$currency_code = 'NZD';
		}
		elseif($_POST['currency'] == 'JPY')
		{
			$currency_sign = '¥';
			$currency_code = 'JPY';
		}
		elseif($_POST['currency'] == 'CAD')
		{
			$currency_sign = 'CDN$';
			$currency_code = 'CAD';
		}
		elseif($_POST['currency'] == 'ZAR')
		{
			$currency_sign = 'R ';
			$currency_code = 'ZAR';
		}
		
		else
		{
			$currency_sign = '$';
			$currency_code = 'USD';
		}
	
		// Content that will be written to the config file
		$content = "<?php\n";
		$content.= "\$config['db']['host'] = '".addslashes(stripslashes($config['db']['host']))."';\n";
		$content.= "\$config['db']['name'] = '".addslashes(stripslashes($config['db']['name']))."';\n";
		$content.= "\$config['db']['user'] = '".addslashes(stripslashes($config['db']['user']))."';\n";
		$content.= "\$config['db']['pass'] = '".addslashes(stripslashes($config['db']['pass']))."';\n";
		$content.= "\$config['db']['pre'] = '".addslashes(stripslashes($config['db']['pre']))."';\n";
		$content.= "\n";
		$content.= "\$config['site_title'] = '".addslashes(stripslashes($config['site_title']))."';\n";
		$content.= "\$config['site_url'] = '".addslashes(stripslashes($config['site_url']))."';\n";
		$content.= "\$config['admin_email'] = '".addslashes(stripslashes($config['admin_email']))."';\n";
		$content.= "\$config['cron_time'] = '".addslashes(stripslashes($config['cron_time']))."';\n";
		$content.= "\$config['pay_type'] = '".addslashes(stripslashes($config['pay_type']))."';\n";
		$content.= "\$config['security'] = '".addslashes(stripslashes($config['security']))."';\n";
		$content.= "\$config['mailbox_en'] = '".addslashes(stripslashes($config['mailbox_en']))."';\n";
		$content.= "\n";
		$content.= "\$config['currency_sign'] = '".addslashes($currency_sign)."';\n";
		$content.= "\$config['currency_code'] = '".addslashes($currency_code)."';\n";
		$content.= "\$config['transfer_en'] = '".addslashes($_POST['transfer_en'])."';\n";
		$content.= "\$config['transfer_min'] = '".addslashes($_POST['transfer_min'])."';\n";
		$content.= "\$config['escrow_en'] = '".addslashes($_POST['escrow_en'])."';\n";
		$content.= "\n";
		$content.= "\$config['start_amount_provider'] = '".addslashes($_POST['start_amount_provider'])."';\n";
		$content.= "\$config['start_amount_buyer'] = '".addslashes($_POST['start_amount_buyer'])."';\n";
		$content.= "\$config['post_project_amount'] = '".addslashes($_POST['post_project_amount'])."';\n";
		$content.= "\$config['post_featured_amount'] = '".addslashes($_POST['post_featured_amount'])."';\n";
		$content.= "\$config['post_job_amount'] = '".addslashes($_POST['post_job_amount'])."';\n";
		$content.= "\$config['provider_com'] = '".addslashes($_POST['provider_com'])."';\n";
		$content.= "\$config['buyer_com'] = '".addslashes($_POST['buyer_com'])."';\n";
		$content.= "\$config['provider_fee'] = '".addslashes($_POST['provider_fee'])."';\n";
		$content.= "\$config['buyer_fee'] = '".addslashes($_POST['buyer_fee'])."';\n";
		$content.= "\$config['bid_fee'] = '".addslashes($_POST['bid_fee'])."';\n";
		$content.= "\$config['enable_quotes'] = '".addslashes(stripslashes($config['enable_quotes']))."';\n";
		$content.= "\$config['multiple_accounts'] = '".addslashes(stripslashes($config['multiple_accounts']))."';\n";
		$content.= "\$config['latest_project_limit'] = '".addslashes(stripslashes($config['latest_project_limit']))."';\n";
		$content.= "\$config['featured_project_limit'] = '".addslashes(stripslashes($config['featured_project_limit']))."';\n";
		$content.= "\$config['job_listings_limit'] = '".addslashes(stripslashes($config['job_listings_limit']))."';\n";
		$content.= "\$config['mod_rewrite'] = '".addslashes(stripslashes($config['mod_rewrite']))."';\n";
		$content.= "\$config['rows_per_page'] = '".addslashes(stripslashes($config['rows_per_page']))."';\n";
		$content.= "\$config['transfer_filter'] = '".addslashes(stripslashes($config['transfer_filter']))."';\n";
		$content.= "\$config['provider_public_post'] = '".addslashes(stripslashes($config['provider_public_post']))."';\n";
		$content.= "\$config['email_validation'] = '".addslashes(stripslashes($config['email_validation']))."';\n";
		$content.= "\$config['userlangsel'] = '".addslashes(stripslashes($config['userlangsel']))."';\n";
		$content.= "\n";
		$content.= "\$config['email']['type'] = '".addslashes(stripslashes($config['email']['type']))."';\n";
		$content.= "\$config['email']['smtp']['host'] = '".addslashes(stripslashes($config['email']['smtp']['host']))."';\n";
		$content.= "\$config['email']['smtp']['user'] = '".addslashes(stripslashes($config['email']['smtp']['user']))."';\n";
		$content.= "\$config['email']['smtp']['pass'] = '".addslashes(stripslashes($config['email']['smtp']['pass']))."';\n";
		$content.= "\n";
		$content.= "\$config['xml']['latest'] = '".$config['xml']['latest']."';\n";
		$content.= "\$config['xml']['featured'] = '".$config['xml']['featured']."';\n";
		$content.= "\n";
		$content.= "\$config['images']['max_size'] = '".addslashes(stripslashes($config['images']['max_size']))."';\n";
		$content.= "\$config['images']['max_height'] = '".addslashes(stripslashes($config['images']['max_height']))."';\n";
		$content.= "\$config['images']['max_width'] = '".addslashes(stripslashes($config['images']['max_width']))."';\n";
		$content.= "\n";
		$content.= "\$config['cookie_time'] = '".addslashes(stripslashes($config['cookie_time']))."';\n";
		$content.= "\$config['cookie_name'] = '".addslashes(stripslashes($config['cookie_name']))."';\n";
		$content.= "\n";
		$content.= "\$config['tpl_name'] = '".addslashes(stripslashes($config['tpl_name']))."';\n";
		$content.= "\$config['version'] = '".$config['version']."';\n";
		$content.= "\$config['lang'] = '".addslashes(stripslashes($config['lang']))."';\n";
		$content.= "\$config['temp_php'] = '".$config['temp_php']."';\n";
		$content.= "\$config['installed'] = '1';\n";
		$content.= "?>";
		
		// Open the includes/config.php for writting
		$handle = fopen('../includes/config.php', 'w');
		// Write the config file
		fwrite($handle, $content);
		// Close the file
		fclose($handle);
		
		transfer($config,'transaction_settings.php','Transaction Settings Saved');
		exit;
	}
}

$obj = new Menu();
$nav = $obj->get_js_menu(0,$config);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title><?php echo $config['site_title']; ?> Admin</title>
<link rel="stylesheet" type="text/css" href="images/style.css">
<SCRIPT LANGUAGE="JavaScript" SRC="menu/JSCookMenu.js"></SCRIPT>
<LINK REL="stylesheet" HREF="menu/themes/Office/theme.css" TYPE="text/css">
<SCRIPT LANGUAGE="JavaScript" SRC="menu/themes/Office/theme.js"></SCRIPT>
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
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
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
<td class="heading"><img src="images/icon_configuration.gif" width="21" height="22" alt="" align="absmiddle" hspace="5">Transaction Settings </td>
</tr>
<tr>
<td bgcolor="#333333"><img src="images/dot.gif" width="1" height="1" alt=""></td>
</tr>
</table>
<!--End heading page-->
<br>
<!--Start form-->
<table width="850" border="0" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #CCCCCC;">
<tr>
<td align="center" bgcolor="#F6F6F6" style="padding:15px;"><form name="form1" method="post" action="">
  <table cellpadding="0" cellspacing="2" border="0">
    <tr>
      <td><strong>Currency</strong></td>
      <td>:
                          <select name="currency" id="currency" class="textbox" style="width:60%">
				<option value="USD" <?php if($config['currency_code'] == 'USD'){ echo 'selected'; } ?>>US Dollars ($)</option>
				<option value="GBP" <?php if($config['currency_code'] == 'GBP'){ echo 'selected'; } ?>>UK Pounds (&pound;)</option>
				<option value="EUR" <?php if($config['currency_code'] == 'EUR'){ echo 'selected'; } ?>>Euros (EUR)</option>
				<option value="AUD" <?php if($config['currency_code'] == 'AUD'){ echo 'selected'; } ?>>Australian Dollars (A$)</option>
				<option value="NZD" <?php if($config['currency_code'] == 'NZD'){ echo 'selected'; } ?>>New Zealand Dollars (NZ$)</option>
				<option value="JPY" <?php if($config['currency_code'] == 'JPY'){ echo 'selected'; } ?>>Japanese Yen (¥)</option>
				<option value="CAD" <?php if($config['currency_code'] == 'CAD'){ echo 'selected'; } ?>>Canadian Dollar (CDN$)</option>
                <option value="ZAR" <?php if($config['currency_code'] == 'ZAR'){ echo 'selected'; } ?>>South African Rands (R)</option>
				</select></td>
    </tr>
    <tr>
      <td><strong>Allow Transfers</strong></td>
      <td>:
                          <select name="transfer_en" id="transfer_en" class="textbox" style="width:60%">
				<option value="0" <?php if($config['transfer_en'] == '0'){ echo 'selected'; } ?>>No</option>
				<option value="1" <?php if($config['transfer_en'] == '1'){ echo 'selected'; } ?>>Yes</option>
				</select></td>
    </tr>
     <tr>
      <td><strong>Allow Escrow</strong></td>
      <td>:
                          <select name="escrow_en" id="escrow_en" class="textbox" style="width:60%">
				<option value="0" <?php if($config['escrow_en'] == '0'){ echo 'selected'; } ?>>No</option>
				<option value="1" <?php if($config['escrow_en'] == '1'){ echo 'selected'; } ?>>Yes</option>
				</select></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="270"><strong><?php echo $lang['PROVIDERU']; ?> Start Amount  (<a href="#" onClick="MM_openBrWindow('help.php?id=16','help','width=500,height=200')">?</a>)</strong></td>
      <td>:
          <input name="start_amount_provider" type="Text" class="textbox" id="start_amount_provider" style="width:60%" value="<?php echo $config['start_amount_provider'];?>"></td>
    </tr>
    <tr>
      <td><strong><?php echo $lang['BUYERU']; ?> Start Amount (<a href="#" onClick="MM_openBrWindow('help.php?id=17','help','width=500,height=200')">?</a>)</strong></td>
      <td>:
          <input name="start_amount_buyer" type="Text" class="textbox" id="start_amount_buyer" style="width:60%" value="<?php echo $config['start_amount_buyer'];?>"></td>
    </tr>
    <tr>
      <td><strong>Post Project Amount  (<a href="#" onClick="MM_openBrWindow('help.php?id=18','help','width=500,height=200')">?</a>)</strong></td>
      <td>:
          <input name="post_project_amount" type="Text" class="textbox" id="post_project_amount" style="width:60%" value="<?php echo $config['post_project_amount']; ?>"></td>
    </tr>
    <tr>
      <td><strong>Featured Project Amount (<a href="#" onClick="MM_openBrWindow('help.php?id=19','help','width=500,height=200')">?</a>)</strong></td>
      <td>:
          <input name="post_featured_amount" type="Text" class="textbox" id="post_featured_amount" style="width:60%" value="<?php echo $config['post_featured_amount']; ?>"></td>
    </tr>
    <tr>
      <td><strong>Job Amount (<a href="#" onClick="MM_openBrWindow('help.php?id=20','help','width=500,height=200')">?</a>)</strong></td>
      <td>:
          <input name="post_job_amount" type="Text" class="textbox" id="post_job_amount" style="width:60%" value="<?php echo $config['post_job_amount']; ?>"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="270"><strong><?php echo $lang['PROVIDERU']; ?> Comission Percentage (<a href="#" onClick="MM_openBrWindow('help.php?id=24','help','width=500,height=200')">?</a>)</strong></td>
      <td>:
          <input name="provider_com" type="Text" class="textbox" id="provider_com" style="width:60%" value="<?php echo $config['provider_com'];?>"></td>
    </tr>
    <tr>
      <td width="270"><strong><?php echo $lang['PROVIDERU']; ?> Fee (<a href="#" onClick="MM_openBrWindow('help.php?id=25','help','width=500,height=200')">?</a>)</strong></td>
      <td>:
          <input name="provider_fee" type="Text" class="textbox" id="provider_fee" style="width:60%" value="<?php echo $config['provider_fee'];?>"></td>
    </tr>
    <tr>
      <td width="270"><strong><?php echo $lang['BUYERU']; ?> Comission Percentage (<a href="#" onClick="MM_openBrWindow('help.php?id=26','help','width=500,height=200')">?</a>)</strong></td>
      <td>:
          <input name="buyer_com" type="Text" class="textbox" id="buyer_com" style="width:60%" value="<?php echo $config['buyer_com'];?>"></td>
    </tr>
    <tr>
      <td width="270"><strong><?php echo $lang['BUYERU']; ?> Fee (<a href="#" onClick="MM_openBrWindow('help.php?id=27','help','width=500,height=200')">?</a>)</strong></td>
      <td>:
          <input name="buyer_fee" type="Text" class="textbox" id="buyer_fee" style="width:60%" value="<?php echo $config['buyer_fee'];?>"></td>
    </tr>
    <tr>
      <td width="270"><strong>Bid Fee (<a href="#" onClick="MM_openBrWindow('help.php?id=28','help','width=500,height=200')">?</a>)</strong></td>
      <td>:
          <input name="bid_fee" type="Text" class="textbox" id="bid_fee" style="width:60%" value="<?php echo $config['bid_fee'];?>"></td>
    </tr>
    <tr>
      <td width="270"><strong>Minimum Transfer Amount</strong></td>
      <td>:
          <input name="transfer_min" type="Text" class="textbox" id="transfer_min" style="width:60%" value="<?php echo $config['transfer_min'];?>"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td></td>
      <td height="30" style="padding-left:6px;">
        <input name="Submit" type="submit" class="button" value="Submit">
&nbsp;
      <input name="Reset" type="reset" class="button" value="Reset">
      </td>
    </tr>
  </table>
</form></td>
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