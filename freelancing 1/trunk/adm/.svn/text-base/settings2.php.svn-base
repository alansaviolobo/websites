<?php 
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/lang/lang_'.$config['lang'].'.php');
require_once('class.menu.php');

db_connect($config);

$obj = new Menu();
$nav = $obj->get_js_menu(0,$config);

if(isset($_POST['setting']))
{
	foreach($_POST['setting'] as $key=>$value)
	{
		mysql_query("UPDATE `".$config['db']['pre']."settings` SET `setting_value` = '".validate_input($value)."' WHERE `setting_id` = '".validate_input($key)."' LIMIT 1;");	
	}
}
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
<td class="heading"><img src="images/icon_configuration.gif" width="21" height="22" alt="" align="absmiddle" hspace="5">Settings</td>
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
<?php
$query = "SELECT * FROM ".$config['db']['pre']."settings WHERE group_id='".validate_input($_GET['group'])."' AND setting_display='1'";
$query_result = mysql_query($query);
while ($info = @mysql_fetch_array($query_result))
{
	if($info['setting_type'] == 'select')
	{
		$options = explode(',',$info['setting_options']);
?>
    <tr>
      <td width="270"><strong><?php echo stripslashes($info['setting_title']); ?></strong></td>
      <td>:
          <select name="setting[<?php echo $info['setting_id']; ?>]" class="textbox" id="setting[<?php echo $info['setting_id']; ?>]" style="width:60%">
          <?php
		  foreach($options as $key3=>$value3)
		  {
		  	$parts = explode('|',$value3);
		  	
			if($info['setting_value'] == $parts[0])
			{
		  		echo '<option value="'.$parts[0].'" selected>'.stripslashes($parts[1]).'</option>';
		  	}
			else
			{
		  		echo '<option value="'.$parts[0].'">'.stripslashes($parts[1]).'</option>';
		  	}
		  }
		  ?>
          </select></td>
    </tr>
<?
	}
	else
	{
?>
    <tr>
      <td width="270"><strong><?php echo stripslashes($info['setting_title']); ?></strong></td>
      <td>:
          <input name="setting[<?php echo $info['setting_id']; ?>]" type="Text" class="textbox" id="setting[<?php echo $info['setting_id']; ?>]" style="width:60%" value="<?php echo stripslashes($info['setting_value']);?>"></td>
    </tr>
<?
	}
}
?>
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
