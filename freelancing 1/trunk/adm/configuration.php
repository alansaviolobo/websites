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
		// Content that will be written to the config file
		$content = "<?php\n";
		$content.= "\$config['db']['host'] = '".addslashes($_POST['DBHost'])."';\n";
		$content.= "\$config['db']['name'] = '".addslashes($_POST['DBName'])."';\n";
		$content.= "\$config['db']['user'] = '".addslashes($_POST['DBUser'])."';\n";
		$content.= "\$config['db']['pass'] = '".addslashes($_POST['DBPass'])."';\n";
		$content.= "\$config['db']['pre'] = '".addslashes($_POST['DBPre'])."';\n";
		$content.= "\n";
		$content.= "\$config['site_title'] = '".addslashes($_POST['site_title'])."';\n";
		$content.= "\$config['site_url'] = '".addslashes($_POST['site_url'])."';\n";
		$content.= "\$config['admin_email'] = '".addslashes($_POST['admin_email'])."';\n";
		$content.= "\$config['cron_time'] = '".addslashes(stripslashes($config['cron_time']))."';\n";
		$content.= "\$config['pay_type'] = '".addslashes(stripslashes($config['pay_type']))."';\n";
		$content.= "\$config['security'] = '".addslashes(stripslashes($config['security']))."';\n";
		$content.= "\$config['mailbox_en'] = '".addslashes($_POST['mailbox_en'])."';\n";
		$content.= "\n";
		$content.= "\$config['currency_sign'] = '".addslashes(stripslashes($config['currency_sign']))."';\n";
		$content.= "\$config['currency_code'] = '".addslashes(stripslashes($config['currency_code']))."';\n";
		$content.= "\$config['transfer_en'] = '".addslashes(stripslashes($config['transfer_en']))."';\n";
		$content.= "\$config['transfer_min'] = '".addslashes(stripslashes($config['transfer_min']))."';\n";
		$content.= "\$config['escrow_en'] = '".addslashes(stripslashes($config['escrow_en']))."';\n";
		$content.= "\n";
		$content.= "\$config['start_amount_provider'] = '".addslashes(stripslashes($config['start_amount_provider']))."';\n";
		$content.= "\$config['start_amount_buyer'] = '".addslashes(stripslashes($config['start_amount_buyer']))."';\n";
		$content.= "\$config['post_project_amount'] = '".addslashes(stripslashes($config['post_project_amount']))."';\n";
		$content.= "\$config['post_featured_amount'] = '".addslashes(stripslashes($config['post_featured_amount']))."';\n";
		$content.= "\$config['post_job_amount'] = '".addslashes(stripslashes($config['post_job_amount']))."';\n";
		$content.= "\$config['provider_com'] = '".addslashes(stripslashes($config['provider_com']))."';\n";
		$content.= "\$config['buyer_com'] = '".addslashes(stripslashes($config['buyer_com']))."';\n";
		$content.= "\$config['provider_fee'] = '".addslashes(stripslashes($config['provider_fee']))."';\n";
		$content.= "\$config['buyer_fee'] = '".addslashes(stripslashes($config['buyer_fee']))."';\n";
		$content.= "\$config['bid_fee'] = '".addslashes(stripslashes($config['bid_fee']))."';\n";
		$content.= "\$config['enable_quotes'] = '".addslashes($_POST['enable_quotes'])."';\n";
		$content.= "\$config['multiple_accounts'] = '".addslashes($_POST['multiple_accounts'])."';\n";
		$content.= "\$config['latest_project_limit'] = '".addslashes($_POST['latest_project_limit'])."';\n";
		$content.= "\$config['featured_project_limit'] = '".addslashes($_POST['featured_project_limit'])."';\n";
		$content.= "\$config['job_listings_limit'] = '".addslashes($_POST['job_listings_limit'])."';\n";
		$content.= "\$config['mod_rewrite'] = '".addslashes($_POST['mod_rewrite'])."';\n";
		$content.= "\$config['rows_per_page'] = '".addslashes(stripslashes($config['rows_per_page']))."';\n";
		$content.= "\$config['transfer_filter'] = '".addslashes($_POST['transfer_filter'])."';\n";
		$content.= "\$config['provider_public_post'] = '".addslashes($_POST['provider_public_post'])."';\n";
		$content.= "\$config['email_validation'] = '".addslashes(stripslashes($config['email_validation']))."';\n";
		$content.= "\$config['userlangsel'] = '".addslashes($_POST['userlangsel'])."';\n";
		$content.= "\n";
		$content.= "\$config['email']['type'] = '".addslashes($_POST['email_type'])."';\n";
		$content.= "\$config['email']['smtp']['host'] = '".addslashes($_POST['smtp_host'])."';\n";
		$content.= "\$config['email']['smtp']['user'] = '".addslashes($_POST['smtp_username'])."';\n";
		$content.= "\$config['email']['smtp']['pass'] = '".addslashes($_POST['smtp_password'])."';\n";
		$content.= "\n";
		$content.= "\$config['xml']['latest'] = '".$config['xml']['latest']."';\n";
		$content.= "\$config['xml']['featured'] = '".$config['xml']['featured']."';\n";
		$content.= "\n";
		$content.= "\$config['images']['max_size'] = '".addslashes($_POST['image_max_size'])."';\n";
		$content.= "\$config['images']['max_height'] = '".addslashes($_POST['image_max_height'])."';\n";
		$content.= "\$config['images']['max_width'] = '".addslashes($_POST['image_max_width'])."';\n";
		$content.= "\n";
		$content.= "\$config['cookie_time'] = '".addslashes(stripslashes($config['cookie_time']))."';\n";
		$content.= "\$config['cookie_name'] = '".addslashes(stripslashes($config['cookie_name']))."';\n";
		$content.= "\n";
		$content.= "\$config['tpl_name'] = '".addslashes(stripslashes($config['tpl_name']))."';\n";
		$content.= "\$config['version'] = '".$config['version']."';\n";
		$content.= "\$config['lang'] = '".addslashes($_POST['lang'])."';\n";
		$content.= "\$config['temp_php'] = '".addslashes($_POST['temp_php'])."';\n";
		$content.= "\$config['installed'] = '1';\n";
		$content.= "?>";
		
		// Open the includes/config.php for writting
		$handle = fopen('../includes/config.php', 'w');
		// Write the config file
		fwrite($handle, $content);
		// Close the file
		fclose($handle);
		
		transfer($config,'configuration.php','Configuration Saved');
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
<td class="heading"><img src="images/icon_configuration.gif" width="21" height="22" alt="" align="absmiddle" hspace="5">Configuration</td>
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
      <td width="270"><strong>Site Title (<a href="#" onClick="MM_openBrWindow('help.php?id=0','help','width=500,height=200')">?</a>)</strong></td>
      <td>:
          <input name="site_title" type="Text" class="textbox" id="site_title" style="width:60%" value="<?php echo stripslashes($config['site_title']);?>"></td>
    </tr>
    <tr>
      <td><strong>Site Url  (<a href="#" onClick="MM_openBrWindow('help.php?id=1','help','width=500,height=200')">?</a>)</strong></td>
      <td>:
          <input name="site_url" type="Text" class="textbox" id="site_url" style="width:60%" value="<?php echo stripslashes($config['site_url']);?>"></td>
    </tr>
    <tr>
      <td><strong>Language (<a href="#" onClick="MM_openBrWindow('help.php?id=4','help','width=500,height=200')">?</a>)</strong></td>
      <td>:
                          <select name="lang" id="lang" class="textbox" style="width:60%">
<?php
$langs = array();

if ($handle = opendir('../includes/lang/')) 
{
	while (false !== ($file = readdir($handle))) 
	{
		if ($file != "." && $file != "..") 
		{
			$lang2 = str_replace('.php','',$file);
			$lang2 = str_replace('lang_','',$lang2);	
			
			$langs[] = $lang2;
		}
	}
	closedir($handle);
}

sort($langs);

foreach ($langs as $key => $lang2)
{
	if($config['lang'] == $lang2)
	{
		echo '<option value="'.$lang2.'" selected>'.ucwords($lang2).'</option>';
	}
	else
	{
		echo '<option value="'.$lang2.'">'.ucwords($lang2).'</option>';
	}
}
?>
				</select></td>
    </tr>
    <tr>
      <td><strong>Enable Quotes  (<a href="#" onClick="MM_openBrWindow('help.php?id=5','help','width=500,height=200')">?</a>)</strong></td>
      <td>:
          <select name="enable_quotes" class="textbox" style="width:60%;">
            <option value="1" <?php if($config['enable_quotes'] == 1){ echo "selected"; } ?>>Yes</option>
            <option value="0" <?php if($config['enable_quotes'] == 0){ echo "selected"; } ?>>No</option>
          </select></td>
    </tr>
    <tr>
      <td><strong>Enable Mod_rewrite</strong></td>
      <td>:
          <select name="mod_rewrite" class="textbox" style="width:60%;">
            <option value="1" <?php if($config['mod_rewrite'] == 1){ echo "selected"; } ?>>Yes</option>
            <option value="0" <?php if($config['mod_rewrite'] == 0){ echo "selected"; } ?>>No</option>
          </select></td>
    </tr>
    <tr>
      <td><strong>Enable Private Messaging</strong></td>
      <td>:
          <select name="mailbox_en" class="textbox" style="width:60%;">
            <option value="1" <?php if($config['mailbox_en'] == 1){ echo "selected"; } ?>>Yes</option>
            <option value="0" <?php if($config['mailbox_en'] == 0){ echo "selected"; } ?>>No</option>
          </select></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><strong>Admin Email  (<a href="#" onClick="MM_openBrWindow('help.php?id=6','help','width=500,height=200')">?</a>)</strong></td>
      <td>:
          <input name="admin_email" type="Text" class="textbox" id="admin_email" style="width:60%" value="<?php echo stripslashes($config['admin_email']);?>"></td>
    </tr>
    <tr>
      <td><strong>Email Send Type  (<a href="#" onClick="MM_openBrWindow('help.php?id=8','help','width=500,height=200')">?</a>)</strong></td>
      <td>:
          <select name="email_type" id="email_type" style="width:60%">
            <option <?php if($config['email']['type'] == 'mail'){ echo "selected"; } ?> value="mail">Mail</option>
            <option <?php if($config['email']['type'] == 'sendmail'){ echo "selected"; } ?> value="sendmail">SendMail</option>
            <option <?php if($config['email']['type'] == 'smtp'){ echo "selected"; } ?> value="smtp">SMTP</option>
        </select></td>
    </tr>
    <tr>
      <td><strong>SMTP Host  (<a href="#" onClick="MM_openBrWindow('help.php?id=9','help','width=500,height=200')">?</a>)</strong></td>
      <td>:
          <input name="smtp_host" type="Text" class="textbox" id="smtp_host" style="width:60%" value="<?php echo stripslashes($config['email']['smtp']['host']);?>"></td>
    </tr>
    <tr>
      <td><strong>SMTP Username (<a href="#" onClick="MM_openBrWindow('help.php?id=10','help','width=500,height=200')">?</a>)</strong></td>
      <td>:
          <input name="smtp_username" type="Text" class="textbox" id="smtp_username" style="width:60%" value="<?php echo stripslashes($config['email']['smtp']['user']);?>"></td>
    </tr>
    <tr>
      <td><strong>SMTP Password (<a href="#" onClick="MM_openBrWindow('help.php?id=11','help','width=500,height=200')">?</a>)</strong></td>
      <td>:
          <input name="smtp_password" type="password" class="textbox" id="smtp_password" style="width:60%" value="<?php echo stripslashes($config['email']['smtp']['pass']);?>"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><strong>Database Host  (<a href="#" onClick="MM_openBrWindow('help.php?id=12','help','width=500,height=200')">?</a>)</strong></td>
      <td>:
          <input name="DBHost" type="Text" class="textbox" id="DBHost" style="width:60%" value="<?php echo stripslashes($config['db']['host']);?>"></td>
    </tr>
    <tr>
      <td><strong>Database Name  (<a href="#" onClick="MM_openBrWindow('help.php?id=13','help','width=500,height=200')">?</a>)</strong></td>
      <td>:
          <input name="DBName" type="Text" class="textbox" id="DBName" style="width:60%" value="<?php echo stripslashes($config['db']['name']);?>"></td>
    </tr>
    <tr>
      <td><strong>Database Username  (<a href="#" onClick="MM_openBrWindow('help.php?id=14','help','width=500,height=200')">?</a>)</strong></td>
      <td>:
          <input name="DBUser" type="Text" class="textbox" id="DBUser" style="width:60%" value="<?php echo stripslashes($config['db']['user']);?>"></td>
    </tr>
    <tr>
      <td><strong>Database Password  (<a href="#" onClick="MM_openBrWindow('help.php?id=15','help','width=500,height=200')">?</a>)</strong></td>
      <td>:
          <input name="DBPass" type="password" class="textbox" id="DBPass" style="width:60%" value="<?php echo stripslashes($config['db']['pass']);?>"></td>
    </tr>
    <tr>
      <td><strong>Database Prefix</strong></td>
      <td>:
          <input name="DBPre" type="Text" class="textbox" id="DBPre" style="width:60%" value="<?php echo stripslashes($config['db']['pre']);?>"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><strong>Transfer Filter  (<a href="#" onClick="MM_openBrWindow('help.php?id=22','help','width=500,height=200')">?</a>)</strong></td>
      <td>:
          
          <select name="transfer_filter" class="textbox" id="transfer_filter" style="width:60%;">
            <option value="1" <?php if($config['transfer_filter'] == 1){ echo "selected"; } ?>>Yes</option>
            <option value="0" <?php if($config['transfer_filter'] == 0){ echo "selected"; } ?>>No</option>
          </select></td>
    </tr>
    <tr>
      <td><p><strong>Allow <?php echo $lang['PROVIDERU']; ?>s to Post Public Messages</strong><strong> (<a href="#" onClick="MM_openBrWindow('help.php?id=21','help','width=500,height=200')">?</a>)</strong></p>        </td>
      <td>:
          <select name="provider_public_post" class="textbox" id="provider_public_post" style="width:60%;">
            <option value="1" <?php if($config['provider_public_post'] == 1){ echo "selected"; } ?>>Yes</option>
            <option value="0" <?php if($config['provider_public_post'] == 0){ echo "selected"; } ?>>No</option>
        </select></td>
    </tr>
    <tr>
      <td><strong>Allow <?php echo $lang['BUYERU']; ?> &amp; <?php echo $lang['PROVIDERU']; ?> Account (<a href="#" onClick="MM_openBrWindow('help.php?id=21','help','width=500,height=200')">?</a>)</strong></td>
      <td>: 
        <select name="multiple_accounts" class="textbox" id="multiple_accounts" style="width:60%;">
          <option value="1" <?php if($config['multiple_accounts'] == 1){ echo "selected"; } ?>>Yes</option>
          <option value="0" <?php if($config['multiple_accounts'] == 0){ echo "selected"; } ?>>No</option>
        </select></td>
    </tr>
    <tr>
      <td><strong>Allow User Language Selection</strong></td>
      <td>: 
        <select name="userlangsel" class="textbox" id="userlangsel" style="width:60%;">
          <option value="1" <?php if($config['userlangsel'] == 1){ echo "selected"; } ?>>Yes</option>
          <option value="0" <?php if($config['userlangsel'] == 0){ echo "selected"; } ?>>No</option>
        </select></td>
    </tr>
    <tr>
      <td><strong>Allow PHP in Templates</strong></td>
      <td>: 
        <select name="temp_php" class="textbox" id="temp_php" style="width:60%;">
          <option value="1" <?php if($config['temp_php'] == 1){ echo "selected"; } ?>>Yes</option>
          <option value="0" <?php if($config['temp_php'] == 0){ echo "selected"; } ?>>No</option>
        </select></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><strong>Image Max FileSize (bytes)  (<a href="#" onClick="MM_openBrWindow('help.php?id=24','help','width=500,height=200')">?</a>)</strong></td>
      <td>:
          <input name="image_max_size" type="Text" class="textbox" id="image_max_size" style="width:60%" value="<?php echo $config['images']['max_size']; ?>"></td>
    </tr>
    <tr>
      <td><strong>Image Max Height  (<a href="#" onClick="MM_openBrWindow('help.php?id=25','help','width=500,height=200')">?</a>)</strong></td>
      <td>:
          <input name="image_max_height" type="Text" class="textbox" id="image_max_height" style="width:60%" value="<?php echo $config['images']['max_height']; ?>"></td>
    </tr>
    <tr>
      <td><strong>Image Max Width  (<a href="#" onClick="MM_openBrWindow('help.php?id=26','help','width=500,height=200')">?</a>)</strong></td>
      <td>:
          <input name="image_max_width" type="Text" class="textbox" id="image_max_width" style="width:60%" value="<?php echo $config['images']['max_width']; ?>"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><strong>Latest Project Listings (Front-Page) (<a href="#" onClick="MM_openBrWindow('help.php?id=27','help','width=500,height=200')">?</a>)</strong></td>
      <td>:
          <input name="latest_project_limit" type="Text" class="textbox" id="latest_project_limit" style="width:60%" value="<?php echo $config['latest_project_limit']; ?>"></td>
    </tr>
    <tr>
      <td><strong>Featured Project Listings (Front-Page) (<a href="#" onClick="MM_openBrWindow('help.php?id=28','help','width=500,height=200')">?</a>)</strong></td>
      <td>:
          <input name="featured_project_limit" type="Text" class="textbox" id="featured_project_limit" style="width:60%" value="<?php echo $config['featured_project_limit']; ?>"></td>
    </tr>
    <tr>
      <td><strong>Latest Job Listings (Front-Page) (<a href="#" onClick="MM_openBrWindow('help.php?id=29','help','width=500,height=200')">?</a>)</strong></td>
      <td>:
          <input name="job_listings_limit" type="Text" class="textbox" id="job_listings_limit" style="width:60%" value="<?php echo $config['job_listings_limit']; ?>"></td>
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
