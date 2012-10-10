<?php 
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.admin.php');
require_once('class.menu.php');

db_connect($config);

if(isset($_GET['id']))
{
	$_POST['list'][$_GET['id']] = $_GET['id'];
}

if(isset($_POST))
{
	if(count($_POST) > 1)
	{
		if(isset($_POST['Submit']))
		{
			foreach ($_POST['id'] as $value) 
			{
				mysql_query("UPDATE `".$config['db']['pre']."jobs` SET `job_title` = '" . addslashes($_POST['title'][$value]) . "',`job_company` = '" . addslashes($_POST['company'][$value]) . "',`job_category` = '" . addslashes($_POST['cat'][$value]) . "',`job_location` = '" . addslashes($_POST['location'][$value]) . "',`job_country` = '" . addslashes($_POST['country'][$value]) . "',`job_type` = '" . addslashes($_POST['type'][$value]) . "',`job_salary` = '" . addslashes($_POST['salary'][$value]) . "',`job_desc` = '" . addslashes($_POST['description'][$value]) .  "' WHERE `job_id` = '" . addslashes($value) . "' LIMIT 1 ;");
			}
			
			transfer($config,'jobs_view.php','Jobs Edited');
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
	<title><?php echo $config['site_title']; ?> Admin - Edit Job</title>
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
<td class="heading"><img src="images/icon_viewall.gif" width="26" height="26" alt="" align="absmiddle" hspace="5">Edit Jobs </td>
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
$sql = "SELECT job_id,job_title,job_company,job_category,job_location,job_country,job_type,job_salary,job_desc FROM ".$config['db']['pre']."jobs ";

foreach ($_POST['list'] as $value) 
{
	if($count == 0)
	{
		$sql.= "WHERE job_id='" . $value . "'";
	}
	ELSE
	{
		$sql.= " OR job_id='" . $value . "'";
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
    <td width="35%"><strong>Job ID</strong></td>
    <td>:
        <input name="id[<?php echo $info['job_id'];?>]" type="Text" class="textbox" style="width:316px" value="<?php echo $info['job_id'];?>" disabled></td>
        <input name="id[<?php echo $info['job_id'];?>]" type="hidden" class="textbox" style="width:316px" value="<?php echo $info['job_id'];?>"></td>
  </tr>
  <tr>
    <td valign="top"><strong>Job Title</strong></td>
    <td>:
        <input name="title[<?php echo $info['job_id'];?>]" type="Text" class="textbox" style="width:316px" value="<?php echo stripslashes($info['job_title']);?>"></td>
  </tr>
  <tr>
    <td width="35%"><strong>Job Company</strong></td>
    <td>:
        <input name="company[<?php echo $info['job_id'];?>]" type="Text" class="textbox" style="width:316px" value="<?php echo stripslashes($info['job_company']);?>"></td>
  </tr>
  <tr>
    <td><strong>Job Category</strong></td>
    <td>:
        <select name="cat[<?php echo $info['job_id'];?>]" style="width:316px">
<?
	$query2 = "SELECT cat_id,cat_title FROM ".$config['db']['pre']."jobs_categories ORDER BY cat_title";
	$query_result2 = mysql_query($query2);
	while ($info2 = @mysql_fetch_array($query_result2))
	{
?>
            <option <?php if($info['job_category'] == $info2['cat_id']){ echo "selected"; } ?> value="<?php echo $info2['cat_id'] ?>"><?php echo stripslashes($info2['cat_title']);?></option>
<?
	}
?>
        </select></td>
  </tr>
  <tr>
    <td valign="top"><strong>Job Location</strong></td>
    <td>:
        <input name="location[<?php echo $info['job_id'];?>]" type="radio" value="0" <?php if($info['job_location'] == 0){ echo "checked"; } ?>>Online <input name="location[<?php echo $info['job_id'];?>]" type="radio" value="1" <?php if($info['job_location'] == 1){ echo "checked"; } ?>>On-Site</td>
  </tr>
  <tr>
    <td><strong>Job Country</strong></td>
    <td>:
        <select name="country[<?php echo $info['job_id'];?>]" style="width:316px">
<?
	$query2 = "SELECT printable_name FROM ".$config['db']['pre']."countries ORDER BY printable_name";
	$query_result2 = mysql_query($query2);
	while ($info2 = @mysql_fetch_array($query_result2))
	{
?>
            <option <?php if($info['job_country'] == $info2['printable_name']){ echo "selected"; } ?>><?php echo stripslashes($info2['printable_name']);?></option>
<?
	}
?>
        </select></td>
  </tr>
  <tr>
    <td><strong>Job Type</strong></td>
    <td>:
        <select name="type[<?php echo $info['job_id'];?>]" style="width:316px">
<?
	$query2 = "SELECT type_id,type_title FROM ".$config['db']['pre']."jobs_types ORDER BY type_title";
	$query_result2 = mysql_query($query2);
	while ($info2 = @mysql_fetch_array($query_result2))
	{
?>
            <option <?php if($info['job_type'] == $info2['type_id']){ echo "selected"; } ?> value="<?php echo $info2['type_id'] ?>"><?php echo stripslashes($info2['type_title']);?></option>
<?
	}
?>
        </select></td>
  </tr>
  <tr>
    <td width="35%"><strong>Job Salary</strong></td>
    <td>:
        <input name="salary[<?php echo $info['job_id'];?>]" type="Text" class="textbox" style="width:316px" value="<?php echo stripslashes($info['job_salary']);?>"></td>
  </tr>
  <tr>
    <td valign="top"><strong>Job Description</strong></td>
    <td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="7" valign="top">:</td>
          <td><textarea name="description[<?php echo $info['job_id'];?>]" class="textbox" style="width:317px;height:100px;"><?php echo stripslashes($info['job_desc']);?></textarea></td>
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