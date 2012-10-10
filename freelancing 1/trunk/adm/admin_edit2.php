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
		if(isset($_POST['Submit']))
		{
			foreach ($_POST['id'] as $value) 
			{
		
				$query = "SELECT admin_id FROM admins WHERE admin_username='" . $_POST['username'][$value] . "' AND admin_password='" . md5($_POST['oldpassword'][$value]) . "' LIMIT 1";
				$query_result = mysql_query($query);
				while ($info = mysql_fetch_array($query_result))
				{
					$admin_id = $info['admin_id'];
				}
				if(isset($admin_id)){
					mysql_query("UPDATE `admins` SET `admin_username` = '" . $_POST['username'][$value] . "',`admin_password` = '" . md5($_POST['newpassword'][$value]) . "' WHERE `admin_id` = '" . $value . "' LIMIT 1 ;");
				}
				ELSE{
					echo '<link rel="stylesheet" type="text/css" href="images/style.css">';
					echo "\n<table align='left' cellpadding=5><tr><td><br>The wrong old password was entered.";
					echo "<br><br><a href='#' onclick='history.back()'>Back</a></td></tr></table>";
					die();
				}
			
			
			}
			
			transfer($config,'admin_edit.php','Admin Edited');
			exit;
		}
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
  <td bgcolor="#F0F0F0" height="25" style="padding-left:20px;" id="menu"></td>
  <SCRIPT language="JavaScript" type="text/javascript">
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
<td class="heading"><img src="images/icon_editrule.gif" width="19" height="24" alt="" align="absmiddle" hspace="5"><span class="heading">Edit Admin </span></td>
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
$sql = "SELECT * FROM admins ";

foreach ($_POST['list'] as $value) 
{
	if($count == 0)
	{
		$sql.= "WHERE admin_id='" . $value . "'";
	}
	ELSE
	{
		$sql.= " OR admin_id='" . $value . "'";
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
    <td width="35%"><strong>Admin ID:</strong></td>
    <td>
        <input name="id[<?php echo $info['admin_id']; ?>]" type="Text" class="textbox" style="width:316px" value="<?php echo $info['admin_id']; ?>" disabled></td>
        <input name="id[<?php echo $info['admin_id']; ?>]" type="hidden" class="textbox" style="width:316px" value="<?php echo $info['admin_id']; ?>"></td>
  </tr>
  <tr>
    <td width="35%"><strong>Admin Username:</strong></td>
    <td>
        <input <?php if($info['admin_username']=="admin"){ echo "disabled "; } ?>name="username[<?php echo $info['admin_id']; ?>]" type="Text" class="textbox" style="width:316px" value="<?php echo $info['admin_username']; ?>"></td>
  </tr>
    <tr>
    <td width="35%"><strong>Old Admin Password:</strong></td>
    <td>
        <input name="oldpassword[<?php echo $info['admin_id']; ?>]" type="password" class="textbox" style="width:316px"></td>
  </tr>
  <tr>
    <td width="35%"><strong>New Admin Password:</strong></td>
    <td>
        <input name="newpassword[<?php echo $info['admin_id']; ?>]" type="password" class="textbox" style="width:316px"></td>
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
