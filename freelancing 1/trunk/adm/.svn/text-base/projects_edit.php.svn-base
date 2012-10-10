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
				mysql_query("UPDATE `".$config['db']['pre']."projects` SET `project_status` = '" . addslashes($_POST['status'][$value]) . "',`project_title` = '" . addslashes($_POST['title'][$value]) . "',`project_desc` = '" . addslashes($_POST['description'][$value]) . "',`project_db` = '" . addslashes($_POST['db'][$value]) . "',`project_os` = '" . addslashes($_POST['os'][$value]) . "',`project_budget_min` = '" . addslashes($_POST['budget_min'][$value]) . "',`project_budget_max` = '" . addslashes($_POST['budget_max'][$value]) . "',`project_featured` = '" . addslashes($_POST['featured'][$value]) . "' WHERE `project_id` = '" . addslashes($value) . "' LIMIT 1 ;");
			}
			
			transfer($config,'projects_view.php','Projects Edited');
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
	<title><?php echo $config['site_title']; ?> Admin - Edit Projects</title>
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
<td class="heading"><img src="images/icon_editrule.gif" width="19" height="24" alt="" align="absmiddle" hspace="5">Edit Projects</td>
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
$sql = "SELECT project_id,project_title,project_desc,project_db,project_os,project_budget_min,project_budget_max,project_featured,project_status FROM ".$config['db']['pre']."projects ";

foreach ($_POST['list'] as $value) 
{
	if($count == 0)
	{
		$sql.= "WHERE project_id='" . $value . "'";
	}
	ELSE
	{
		$sql.= " OR project_id='" . $value . "'";
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
    <td width="35%"><strong>Project ID</strong></td>
    <td>:
        <input name="id[<?php echo $info['project_id'];?>]" type="Text" class="textbox" style="width:316px" value="<?php echo $info['project_id'];?>" disabled></td>
        <input name="id[<?php echo $info['project_id'];?>]" type="hidden" class="textbox" style="width:316px" value="<?php echo $info['project_id'];?>"></td>
  </tr>
  <tr>
    <td valign="top"><strong>Project Status</strong></td>
    <td>:
        <select name="status[<?php echo $info['project_id'];?>]" style="width:316px">
          	<option value="0" <?php if($info['project_status'] == 0){ echo "selected"; } ?>>Open</option>
            <option value="2" <?php if($info['project_status'] == 2){ echo "selected"; } ?>>Frozen</option>
            <option value="1" <?php if($info['project_status'] == 1){ echo "selected"; } ?>>Closed</option>
        </select></td>
  </tr>
  <tr>
    <td width="35%"><strong>Project Title</strong></td>
    <td>:
        <input name="title[<?php echo $info['project_id'];?>]" type="Text" class="textbox" style="width:316px" value="<?php echo stripslashes($info['project_title']);?>"></td>
  </tr>
  <tr>
    <td valign="top"><strong>Project Description</strong></td>
    <td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="7" valign="top">:</td>
          <td><textarea name="description[<?php echo $info['project_id'];?>]" class="textbox" style="width:317px;height:100px;"><?php echo stripslashes($info['project_desc']);?></textarea></td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td><strong>Project DB</strong></td>
    <td>:
        <select name="db[<?php echo $info['project_id']; ?>]" style="width:316px">
          <option value="(None)" <?php if($info['project_db'] == '(None)'){ echo "selected"; } ?>>(None)</option>
            <option <?php if($info['project_db'] == '(Please Suggest One)'){ echo "selected"; } ?> value="(Please Suggest One)">(Please Suggest One)</option>
            <option <?php if($info['project_db'] == 'MySQL'){ echo "selected"; } ?> value="MySQL">MySQL</option>
            <option <?php if($info['project_db'] == 'SQL'){ echo "selected"; } ?> value="SQL">SQL</option>
            <option <?php if($info['project_db'] == 'MSQL'){ echo "selected"; } ?> value="MSQL">MSQL</option>
            <option <?php if($info['project_db'] == 'MS SQL'){ echo "selected"; } ?> value="MS SQL">MS SQL</option>
            <option <?php if($info['project_db'] == 'PostgreSQL'){ echo "selected"; } ?> value="PostgreSQL">PostgreSQL</option>
            <option <?php if($info['project_db'] == 'Oracle'){ echo "selected"; } ?> value="Oracle">Oracle</option>
            <option <?php if($info['project_db'] == 'LDAP'){ echo "selected"; } ?> value="LDAP">LDAP</option>
            <option <?php if($info['project_db'] == 'DBM'){ echo "selected"; } ?> value="DBM">DBM</option>
        </select></td>
  </tr>
  <tr>
    <td valign="top"><strong>Project OS</strong></td>
    <td>:
        <select name="os[<?php echo $info['project_id']; ?>]" style="width:316px">
          	<option <?php if($info['project_os'] == '(I don\'t know)'){ echo "selected"; } ?>>(I don't know)</option>
            <option <?php if($info['project_os'] == 'Unix'){ echo "selected"; } ?>>Unix</option>
            <option <?php if($info['project_os'] == 'Linux'){ echo "selected"; } ?>>Linux</option>
            <option <?php if($info['project_os'] == 'Solaris'){ echo "selected"; } ?>>Solaris</option>
            <option <?php if($info['project_os'] == 'Windows'){ echo "selected"; } ?>>Windows</option>
            <option <?php if($info['project_os'] == '(Other)'){ echo "selected"; } ?>>(Other)</option>
        </select></td>
  </tr>
  <tr>
    <td valign="top"><strong>Project Budget Min</strong></td>
    <td>:
        <input name="budget_min[<?php echo $info['project_id'];?>]" type="Text" class="textbox" style="width:316px" value="<?php echo $info['project_budget_min'];?>"></td>
  </tr>
  <tr>
    <td valign="top"><strong>Project Budget Max</strong></td>
    <td>:
        <input name="budget_max[<?php echo $info['project_id'];?>]" type="Text" class="textbox" style="width:316px" value="<?php echo $info['project_budget_max'];?>"></td>
  </tr>
  <tr>
    <td valign="top"><strong>Project Featured</strong></td>
    <td>:
        <input name="featured[<?php echo $info['project_id'];?>]" type="radio" value="1" <?php if($info['project_featured'] == 1){ echo "checked"; } ?>>Yes <input name="featured[<?php echo $info['project_id'];?>]" type="radio" value="0" <?php if($info['project_featured'] == 0){ echo "checked"; } ?>>No</td>
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
