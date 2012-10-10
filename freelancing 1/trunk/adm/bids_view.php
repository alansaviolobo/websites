<?php 
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.admin.php');
require_once('class.menu.php');

// Connect to database
db_connect($config);

$obj = new Menu();
$nav = $obj->get_js_menu(0,$config);

//Pagination Start
if (isset($_GET['pageno'])) 
{
   $pageno = $_GET['pageno'];
}
else 
{
   $pageno = 1;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title><?php echo $config['site_title']; ?> Admin - Edit Bids</title>
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
<td class="heading"><img src="images/icon_viewall.gif" width="26" height="26" alt="" align="absmiddle" hspace="5">Edit Bids</td>
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
<td align="center" bgcolor="#F6F6F6" style="padding:15px;"><div align="right">
  <form action="bids_delete.php" method="post" name="f1" id="f1">
    <div align="right">      <br>
    </div>
    <table width="99%" border="0" align="center" cellpadding="2" cellspacing="0">
      <tr bgcolor="#FF9900">
        <td width="50" height="30"><div align="center">
            <input type="checkbox" name="selall" value="checkbox" onClick="checkBox(this)">
        </div></td>
        <td width="80" height="30"><span class="rowheader">Bid ID</span></td>
        <td width="200" height="30" bgcolor="#FF9900"><span class="rowheader">Project Title</span></td>
		 <td width="100" height="30" bgcolor="#FF9900"><span class="rowheader">Posted By</span></td>
        <td height="30"><span class="rowheader">Bid Date</span></td>
        <td height="30"><span class="rowheader">Bid Amount</span></td>
      </tr>
      <tr bgcolor="#000000">
        <td height="1" colspan="6" style="padding:0px;"></td>
      </tr>      
<?php
$count = 0;
$counter = 0;

//Pagination Continued
if(isset($_GET['project']))
{
	$query = "SELECT 1 FROM ".$config['db']['pre']."bids WHERE project_id='".validate_input($_GET['project'])."'";
}
else
{
	$query = "SELECT 1 FROM ".$config['db']['pre']."bids";
}
$result = mysql_query($query);
$numrows = mysql_num_rows($result);
$lastpage = ceil($numrows/10);
if ($pageno < 1) 
{
	$pageno = 1;
} 
elseif($pageno > $lastpage) 
{
	$pageno = $lastpage;
}
$limit = 'LIMIT '.(($pageno-1)*10) .',10';

$bids = array();
$providers = array();
$projects = array();
$user_where = '';
$project_where = '';

if(isset($_GET['project']))
{
	$query = "SELECT bid_id,bid_desc,bid_time,bid_days,bid_amount,project_id,user_id FROM ".$config['db']['pre']."bids WHERE project_id='".validate_input($_GET['project'])."' ORDER BY bid_id ASC ".$limit;
}
else
{
	$query = "SELECT bid_id,bid_desc,bid_time,bid_days,bid_amount,project_id,user_id FROM ".$config['db']['pre']."bids ORDER BY bid_id ASC ".$limit;
}
$query_result = mysql_query($query);
while ($info = @mysql_fetch_array($query_result))
{
	$bids[$info['project_id']] = $info;
	
	if($user_where == '')
	{
		$user_where = "provider_id='".$info['user_id']."'";
	}
	else
	{
		$user_where.= " OR provider_id='".$info['user_id']."'";
	}
	
	if($project_where == '')
	{
		$project_where = "project_id='".$info['project_id']."'";
	}
	else
	{
		$project_where.= " OR project_id='".$info['project_id']."'";
	}
}

if($user_where != '')
{
	$query = "SELECT provider_id,provider_username FROM ".$config['db']['pre']."providers WHERE ".$user_where;
	$query_result = mysql_query($query);
	while ($info = @mysql_fetch_array($query_result))
	{
		$providers[$info['provider_id']] = $info['provider_username'];
	}
}

if($project_where != '')
{
	$query = "SELECT project_id,project_title FROM ".$config['db']['pre']."projects WHERE ".$project_where;
	$query_result = mysql_query($query);
	while ($info = @mysql_fetch_array($query_result))
	{
		$projects[$info['project_id']] = $info['project_title'];
	}
}

foreach($bids as $key2=>$info)
{
	$counter++;
	if($count == 0)
	{
		$colour = '#F7F7F7';
		$count = 1;
	}
	else
	{
		$colour = '#EFEFEF';
		$count = 0;
	}
?>
      <tr bgcolor="<?php echo $colour; ?>">
        <td width="50" height="25" align="center"><input type="hidden" name="titles[]" id="titles[]" value="<?php echo $info['bid_id']; ?>"><input type="checkbox" name="list[]" id="list[]" value="<?php echo $info['bid_id']; ?>"></td>
        <td width="80" height="25"><span class="style5"><?php echo $info['bid_id'];?></span></td>
        <td height="25"><span class="style5"><?php if(isset($projects[$info['project_id']])){ echo stripslashes($projects[$info['project_id']]); } else { echo 'Project Removed'; } ?></span></td>
		<td height="25"><span class="style5"><?php if(isset($providers[$info['user_id']])){ echo stripslashes($providers[$info['user_id']]); } else { echo 'User Removed'; } ?></span></td>
        <td height="25"><span class="style5"><?php echo date("m/d/Y \a\\t H:i", $info['bid_time']);?></span></td>
        <td height="25"><span class="style5"><?php echo $info['bid_amount']; ?></span></td>
      </tr>
      <?
}
?>
      <tr bgcolor="#000000">
        <td height="1" colspan="6" style="padding:0px;"></td>
      </tr>
    </table>
    <div align="left">
      <br>
      <table width="99%"  border="0" align="center" cellpadding="2" cellspacing="0">
        <tr>
          <td width="200" valign="middle">With Selected:&nbsp;<a href="#" onClick="document.f1.action='bids_edit.php'; document.f1.submit();"><img src="images/button_edit.gif" width="12" height="13" border="0"></a>
            <input name="imageField" type="image" src="images/button_empty.gif" width="11" height="13" border="0"></td><td valign="middle">
<?
if($numrows==0){
	$st=0;
	$en=0;
}elseif($lastpage==$pageno){
	$st=$numrows-$counter+1;
	$en=$numrows;
}else{
	$st=(($counter*$pageno)-10)+2;
	$en=(($counter*$pageno)+1);
}
?>
<div align="center">Showing <?php echo $st ?>-<?php echo $en; ?> of <?php echo $numrows; ?> result(s)</div>
</td><td width="200" valign="middle"><div align="right">
<?
if(isset($_GET['project']))
{
	if ($pageno != 1 AND $numrows!=0) 
	{
	   echo " <a href='{$_SERVER['PHP_SELF']}?pageno=1&project=".$_GET['project']."'>&lt;&lt;</a> ";
	   $prevpage = $pageno-1;
	   echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$prevpage&project=".$_GET['project']."'>&lt;</a> ";
	}
	echo " ( Page $pageno of $lastpage ) ";
	
	if ($pageno != $lastpage AND $numrows!=0) 
	{
	   $nextpage = $pageno+1;
	   echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$nextpage&project=".$_GET['project']."'>&gt;</a> ";   echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$lastpage&project=".$_GET['project']."'>&gt;&gt;</a> ";
	}
}
else
{
	if ($pageno != 1 AND $numrows!=0) 
	{
	   echo " <a href='{$_SERVER['PHP_SELF']}?pageno=1'>&lt;&lt;</a> ";
	   $prevpage = $pageno-1;
	   echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$prevpage'>&lt;</a> ";
	}
	echo " ( Page $pageno of $lastpage ) ";
	
	if ($pageno != $lastpage AND $numrows!=0) 
	{
	   $nextpage = $pageno+1;
	   echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$nextpage'>&gt;</a> ";   echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$lastpage'>&gt;&gt;</a> ";
	}
}
?></div>

          </td>
        </tr>
      </table>
      </div>
    </form>
</div></td>
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
