<?php 
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.admin.php');
require_once('class.menu.php');

db_connect($config);

if(isset($_POST))
{
	if(count($_POST) > 1)
	{	
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
		$content.= "\$config['xml']['latest'] = '".addslashes($_POST['latest_projects'])."';\n";
		$content.= "\$config['xml']['featured'] = '".addslashes($_POST['featured_projects'])."';\n";
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
	
		transfer($config,'xml_manage.php','XML Options Updated');
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
<style type="text/css">
<!--
.style3 {font-size: 12}
.style5 {font-size: 11px}
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
<td class="heading"><img src="images/icon_manage.gif" width="26" height="25" alt="" align="absmiddle" hspace="5">Manage XML Feeds </td>
</tr>
<tr>
<td bgcolor="#333333"><img src="images/dot.gif" width="1" height="1" alt=""></td>
</tr>
</table>
<!--End heading page-->
<br>
<!--Start form-->
<table width="850" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #CCCCCC;" align="center">
<tr>
<td align="center" bgcolor="#F6F6F6" style="padding:15px;">  <form name="form1" method="post" action="">
  <table width="70%" cellpadding="0" cellspacing="2" border="0">
    <tr>
      <td width="25%"><strong>Latest Projects </strong></td>
      <td width="55%">: 
        <input name="latest_projects" type="radio" value="1" <?php if($config['xml']['latest'] == 1){ echo "checked"; } ?>>
        Enabled 
        <input name="latest_projects" type="radio" value="0" <?php if($config['xml']['latest'] == 0){ echo "checked"; } ?>>
        Disabled</td>
    </tr>
    <tr>
      <td><strong>Featured Projects </strong></td>
      <td>: 
        <input name="featured_projects" type="radio" value="1" <?php if($config['xml']['featured'] == 1){ echo "checked"; } ?>>
Enabled
<input name="featured_projects" type="radio" value="0" <?php if($config['xml']['featured'] == 0){ echo "checked"; } ?>>
Disabled </td>
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
