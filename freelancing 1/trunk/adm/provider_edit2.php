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
		if(isset($_POST['Submit']))
		{
			foreach ($_POST['id'] as $value) 
			{
				mysql_query("UPDATE `".$config['db']['pre']."providers` SET `provider_status` = '" . addslashes($_POST['status'][$value]) . "',`provider_username` = '" . addslashes($_POST['username'][$value]) . "',`provider_email` = '" . addslashes($_POST['email'][$value]) . "',`provider_name` = '" . addslashes($_POST['name'][$value]) . "',`provider_price` = '" . addslashes($_POST['price'][$value]) . "',`provider_reviews` = '" . addslashes($_POST['reviews'][$value]) . "',`provider_rating` = '" . addslashes($_POST['rating'][$value]) . "',`provider_profile` = '" . addslashes($_POST['profile'][$value]) . "' WHERE `provider_id` = '" . addslashes($value) . "' LIMIT 1 ;");
			}
			
			transfer($config,'provider_edit.php',$lang['PROVIDERU'].'s Edited');
			exit;
		}
	}
}

if(isset($_GET['id']))
{
	$_POST['list'][$_GET['id']] = $_GET['id'];
}

$obj = new Menu();
$nav = $obj->get_js_menu(0,$config);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title><?php echo $config['site_title']; ?> Admin - Edit <?php echo $lang['PROVIDERU']; ?>s</title>
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
<script language="JavaScript"><!--
function checkBox(theBox){
  var aBox = theBox.form["list[]"];
  var selAll = false;
  var i;
  for(i=0;i<aBox.length;i++){
    if(aBox[i].checked==false) selAll=true;
  }
  if(theBox.name=="selall"){
    for(i=0;i<aBox.length;i++){
      aBox[i].checked = selAll;
    }
    selAll = !selAll;
  }
  //theBox.form.selall.checked = selAll;
}
function init(){
  var theForm = document.f1;
  var aBox = theForm["list[]"];
  var selAll = false;
  var i;
  for(i=0;i<aBox.length;i++){
    if(aBox[i].checked==false) selAll=true;
    aBox[i].onclick = function(){checkBox(this)};
  }
  //theForm.selall.checked = selAll;
}
//--></script>
</head>
<body onLoad="init()">
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
<td class="heading"><img src="images/icon_editrule.gif" width="19" height="24" alt="" align="absmiddle" hspace="5">Edit <?php echo $lang['PROVIDERU']; ?>s</td>
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
$sql = "SELECT provider_status,provider_username,provider_id,provider_email,provider_price,provider_name,provider_rating,provider_reviews,provider_profile FROM ".$config['db']['pre']."providers ";

foreach ($_POST['list'] as $value) 
{
	if($count == 0)
	{
		$sql.= "WHERE provider_id='" . $value . "'";
	}
	ELSE
	{
		$sql.= " OR provider_id='" . $value . "'";
	}
	
	$count++;
} 
$sql.= " LIMIT " . count($_POST['list']);


$query_result = mysql_query($sql);
while ($info = @mysql_fetch_array($query_result))
{
?>
<table width="70%" cellpadding="0" cellspacing="2" border="0">
  <tr>
    <td width="35%"><strong><?php echo $lang['PROVIDERU']; ?> ID</strong></td>
    <td>:
        <input name="id[<?php echo $info['provider_id']; ?>]" type="Text" class="textbox" style="width:316px" value="<?php echo $info['provider_id']; ?>" disabled>
        <input name="id[<?php echo $info['provider_id']; ?>]" type="hidden" class="textbox" style="width:316px" value="<?php echo $info['provider_id']; ?>"></td>
  </tr>
  <tr>
    <td width="35%"><strong><?php echo $lang['PROVIDERU']; ?> Status</strong></td>
    <td>:
<select name="status[<?php echo $info['provider_id']; ?>]" style="width:316px">
          <option value="0" <?php if($info['provider_status'] == 0){ echo "selected"; } ?>>Unconfirmed</option>
          <option value="1" <?php if($info['provider_status'] == 1){ echo "selected"; } ?>>Normal</option>
          <option value="2" <?php if($info['provider_status'] == 2){ echo "selected"; } ?>>Banned</option>
        </select></td>
  </tr>
  <tr>
    <td width="35%"><strong><?php echo $lang['PROVIDERU']; ?> Username</strong></td>
    <td>:
        <input name="username[<?php echo $info['provider_id']; ?>]" type="Text" class="textbox" style="width:316px" value="<?php echo stripslashes($info['provider_username']);?>"></td>
  </tr>
  <tr>
    <td><strong><?php echo $lang['PROVIDERU']; ?> Email Address</strong></td>
    <td>:
        <input name="email[<?php echo $info['provider_id']; ?>]" type="Text" class="textbox" style="width:316px" value="<?php echo stripslashes($info['provider_email']);?>"></td>
  </tr>
  <tr>
    <td valign="top"><strong><?php echo $lang['PROVIDERU']; ?> Name</strong></td>
    <td>:
        <input name="name[<?php echo $info['provider_id']; ?>]" type="Text" class="textbox" style="width:316px" value="<?php echo stripslashes($info['provider_name']);?>"></td>
  </tr>
  <tr>
    <td valign="top"><strong><?php echo $lang['PROVIDERU']; ?> Avg Price</strong></td>
    <td>:
        <input name="price[<?php echo $info['provider_id']; ?>]" type="Text" class="textbox" style="width:316px" value="<?php echo stripslashes($info['provider_price']);?>"></td>
  </tr>
  <tr>
    <td valign="top"><strong><?php echo $lang['PROVIDERU']; ?> Rating</strong></td>
    <td>:
        <input name="rating[<?php echo $info['provider_id']; ?>]" type="Text" class="textbox" style="width:316px" value="<?php echo stripslashes($info['provider_rating']);?>"></td>
  </tr>
  <tr>
    <td valign="top"><strong><?php echo $lang['PROVIDERU']; ?> Reviews</strong></td>
    <td>:
        <input name="reviews[<?php echo $info['provider_id']; ?>]" type="Text" class="textbox" style="width:316px" value="<?php echo stripslashes($info['provider_reviews']);?>"></td>
  </tr>
  <tr>
    <td valign="top"><strong><?php echo $lang['PROVIDERU']; ?> Profile</strong></td>
    <td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="7" valign="top">:</td>
          <td><textarea name="profile[<?php echo $info['provider_id']; ?>]" class="textbox" style="width:317px"><?php echo stripslashes($info['provider_profile']);?></textarea></td>
        </tr>
    </table></td>
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