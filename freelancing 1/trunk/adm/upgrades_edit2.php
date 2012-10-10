<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.admin.php');
require_once('class.menu.php');

// Connect to database
db_connect($config);

if(isset($_POST['Submit']))
{
	foreach ($_POST['id'] as $value) 
	{
		mysql_query("UPDATE `".$config['db']['pre']."subscriptions` SET `sub_title` = '" . validate_input($_POST['name'][$value]) . "', `sub_term` = '" . validate_input($_POST['term'][$value]) . "', `sub_amount` = '" . validate_input($_POST['amount'][$value]) . "', `sub_type` = '" . validate_input($_POST['type'][$value]) . "', `group_id` = '" . validate_input($_POST['group'][$value]) . "' WHERE `sub_id` = '" . validate_input($value) . "' LIMIT 1 ;");
	}
	
	header("Location: upgrades_edit.php");
	exit;
}

if(isset($_GET['id']))
{
	$_POST['list'][] = $_GET['id'];
}

$obj = new Menu();
$nav = $obj->get_js_menu(0,$config);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $config['site_title']; ?> Admin - Edit Upgrade Plans</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="images/style.css">
<SCRIPT LANGUAGE="JavaScript" SRC="menu/JSCookMenu.js"></SCRIPT>
<LINK REL="stylesheet" HREF="menu/themes/Office/theme.css" TYPE="text/css">
<SCRIPT LANGUAGE="JavaScript" SRC="menu/themes/Office/theme.js"></SCRIPT>
<LINK REL="stylesheet" HREF="menu/themes/Office/theme.css" TYPE="text/css">
<SCRIPT LANGUAGE="JavaScript" SRC="menu/themes/Office/theme.js"></SCRIPT>
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
<body>
<!--Start top-->
<table width="850" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="100%" height="42" align="left" background="images/bg_top.gif"><a href="index.php"><img src="images/logo.gif" width="147" height="31" hspace="10" border="0"></a></td>
  </tr>
  <tr>
    <td><img src="images/dot.gif" width="1" height="1" alt=""></td>
  </tr>
</table>
<!--End top-->
<!--Start topmenu-->
<table width="850" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td bgcolor="#F0F0F0" height="25" style="padding-left:20px;" id="menu"><SCRIPT language="JavaScript" type="text/javascript">
			var myMenu =
				
			// Start the menu
[
<?php echo $nav; ?>
];				

			// Output the menu
			cmDraw ('menu', myMenu, 'hbr', cmThemeOffice, 'ThemeOffice');
			</SCRIPT></td>
  </tr>
  <tr>
    <td bgcolor="#333333"><img src="images/dot.gif" width="1" height="1" alt=""></td>
  </tr>
</table>
<br>

<!--End topmenu-->
<!--Start heading page-->
<table width="850" cellpadding="0" cellspacing="0" border="0" align="center">
<tr>
<td class="heading"><img src="images/icon_editrule.gif" width="19" height="24" alt="" align="absmiddle" hspace="5">Edit Upgrade Plans</td>
</tr>
<tr>
<td bgcolor="#333333"><img src="images/dot.gif" width="1" height="1" alt=""></td>
</tr>
</table>
<!--End heading page-->
<!--Start form-->
<br>
<table width="850" border="0" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #CCCCCC;">
<tr>
<td align="center" bgcolor="#F6F6F6" style="padding:15px;">
  <form action="" method="post" name="f1" id="f1">
<?php
$count = 0;
$sql = "SELECT * FROM ".$config['db']['pre']."subscriptions ";

foreach ($_POST['list'] as $value) 
{
	if($count == 0)
	{
		$sql.= "WHERE sub_id='" . $value . "'";
	}
	ELSE
	{
		$sql.= " OR sub_id='" . $value . "'";
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
    <td width="35%"><strong>Upgrade ID</strong></td>
    <td>:
        <input name="id[<?php echo $info['sub_id']; ?>]" type="Text" class="textbox" style="width:316px" value="<?php echo $info['sub_id']; ?>" disabled>
        <input name="id[<?php echo $info['sub_id']; ?>]" type="hidden" class="textbox" style="width:316px" value="<?php echo $info['sub_id']; ?>"></td>
  </tr>
  <tr>
    <td width="35%"><strong>Upgrade Name</strong></td>
    <td>:
        <input name="name[<?php echo $info['sub_id']; ?>]" type="Text" class="textbox" style="width:316px" value="<?php echo $info['sub_title']; ?>"></td>
  </tr>
          <tr>
            <td width="35%"><strong>Upgrade Account Type</strong></td>
            <td>:
                <select name="type[<?php echo $info['sub_id']; ?>]" id="type[<?php echo $info['sub_id']; ?>]" style="width:316px">
                <option value="buy" <?php if($info['sub_type'] == 'buy'){ echo 'selected'; } ?>>Buyer</option>
                <option value="pro" <?php if($info['sub_type'] == 'pro'){ echo 'selected'; } ?>>Provider</option>
                </select>
                </td>
          </tr>
          <tr>
            <td width="25%"><strong>Allow Upgrade from balance</strong></td>
            <td width="55%">:
                <select name="account[<?php echo $info['sub_id']; ?>]" id="account[<?php echo $info['sub_id']; ?>]" style="width:316px" disabled>
                <option value="0" <?php if($info['sub_account'] == '0'){ echo 'selected'; } ?>>No</option>
                <option value="1" <?php if($info['sub_account'] == '0'){ echo 'selected'; } ?>>Yes</option>
                </select>
                </td>
          </tr>
          <tr>
            <td width="35%"><strong>Upgrade Term</strong></td>
            <td>:
                <select name="term[<?php echo $info['sub_id']; ?>]" id="term[<?php echo $info['sub_id']; ?>]" style="width:316px">
                <option value="DAILY" <?php if($info['sub_term'] == 'DAILY'){ echo 'selected'; } ?>>Daily</option>
                <option value="MONTHLY" <?php if($info['sub_term'] == 'MONTHLY'){ echo 'selected'; } ?>>Monthly</option>
                <option value="YEARLY" <?php if($info['sub_term'] == 'YEARLY'){ echo 'selected'; } ?>>Yearly</option>
                </select>
                </td>
          </tr>
          <tr>
            <td width="35%"><strong>Upgrade Amount</strong></td>
            <td>:
                <input name="amount[<?php echo $info['sub_id']; ?>]" type="Text" class="textbox" id="amount[<?php echo $info['sub_id']; ?>]" style="width:316px" value="<?=$info['sub_amount'];?>"></td>
          </tr>
          <tr>
            <td width="35%"><strong>Usergroup</strong></td>
            <td>:
                <select name="group[<?php echo $info['sub_id']; ?>]" id="group[<?php echo $info['sub_id']; ?>]" style="width:316px">
<?
$query2 = "SELECT * FROM ".$config['db']['pre']."usergroups ORDER BY group_name ASC";
$query_result2 = mysql_query($query2);
while ($info2 = @mysql_fetch_array($query_result2))
{
	if($info['group_id'] == $info2['group_id'])
	{
?>
                <option value="<?=$info2['group_id'];?>" selected><?=stripslashes($info2['group_name']);?></option>
<?
	}
	else
	{
?>
                <option value="<?=$info2['group_id'];?>"><?=stripslashes($info2['group_name']);?></option>
<?
	}
}
?>
                </select>
                </td>
          </tr>
</table>
<br><br>
<?php
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
<table width="850" border="0" align="center" cellpadding="0" cellspacing="0">
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