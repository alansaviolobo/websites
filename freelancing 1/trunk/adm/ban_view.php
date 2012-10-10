<?php 
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.admin.php');
require_once('class.menu.php');

db_connect($config);

$obj = new Menu();
$nav = $obj->get_js_menu(0,$config);

if(isset($_GET['pageno']))
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
	<title><?php echo $config['site_title']; ?> Admin - Edit Bans</title>
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
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
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
<td class="heading"><img src="images/icon_editrule.gif" width="19" height="24" alt="" align="absmiddle" hspace="5">Edit Bans </td>
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
  <form action="ban_delete.php" method="post" name="f1" id="f1">
    <div align="right">      <br>
    </div>
    <table width="99%" border="0" align="center" cellpadding="2" cellspacing="0">
      <tr bgcolor="#FF9900">
        <td width="50" height="30"><div align="center">
            <input type="checkbox" name="selall" value="checkbox" onClick="checkBox(this)">
        </div></td>
        <td height="30" bgcolor="#FF9900"><span class="rowheader">Ban Type</span></td>
        <td height="30" bgcolor="#FF9900"><span class="rowheader">Ban Value </span></td>
      	<td width="150" height="30" bgcolor="#FF9900"><span class="rowheader">Options</span></td>
	  </tr>
      <tr bgcolor="#000000">
        <td height="1" style="padding:0px;" colspan="4"></td>
      </tr>
<?php
$count = 0;
$counter = 0;

//Pagination Continued
$query = "SELECT 1 FROM ".$config['db']['pre']."bans";
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

$query = "SELECT ban_id,ban_type,ban_value FROM ".$config['db']['pre']."bans ".$limit;
$query_result = mysql_query($query);
while ($info = @mysql_fetch_array($query_result))
{
	$counter++;
	if($count == 0)
	{
		$colour = '#F7F7F7';
		$count = 1;
	}
	ELSE
	{
		$colour = '#EFEFEF';
		$count = 0;
	}
?>
      <tr bgcolor="<?php echo $colour; ?>">
        <td width="50" height="25" align="center"><input type="hidden" name="titles[]" id="titles[]" value="<?php echo $info['ban_id']; ?>"><input type="checkbox" name="list[]" id="list[]" value="<?php echo $info['ban_id']; ?>"></td>
        <td height="25"><span class="style5"><?php echo $info['ban_type']; ?></span></td>
        <td height="25"><span class="style5"><?php echo $info['ban_value']; ?></span></td>
			  <td height="25">
			  <table border="0" cellpadding="0" cellspacing="0"><tr><td>
                <select name="amenu<?php echo $info['ban_id'];?>" id="amenu<?php echo $info['ban_id'];?>" onChange="MM_jumpMenu('parent',this,0)" style="width:145px">
				  <option value="">Options</option>
                  <option value="ban_edit.php?id=<?php echo $info['ban_id'];?>">View/Edit Ban</option>
                  <option value="ban_delete.php?id=<?php echo $info['ban_id'];?>">Delete Ban</option>
                </select>
</td>
			  </tr>
			  </table>
			  </td>
      </tr>
      <?
}
?>
      <tr bgcolor="#000000">
        <td height="1" colspan="4" style="padding:0px;"></td>
      </tr>
    </table>
    <div align="left">
      <br>
      <table width="99%"  border="0" align="center" cellpadding="2" cellspacing="0">
        <tr>
          <td width="200" valign="middle">With Selected:&nbsp;<a href="#" onclick="document.f1.action='ban_edit.php'; document.f1.submit();"><img src="images/button_edit.gif" width="12" height="13" border="0"></a>
            <input name="imageField" type="image" src="images/button_empty.gif" width="11" height="13" border="0"></td><td valign="middle">
<?php
if($numrows==0)
{
	$st=0;
	$en=0;
}
elseif($lastpage==$pageno)
{
	$st=$numrows-$counter+1;
	$en=$numrows;
}
else
{
	$st=$counter*$pageno-$rows_per_page+1;
	$en=$counter*$pageno;
}
?>
                      <div align="center">Showing <?php echo $st ?>-<?php echo $en; ?> of <?php echo $numrows; ?> result(s)</div></td>
                  <td width="200" valign="middle"><div align="right">
                      <?php
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
   echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$nextpage'>&gt;</a> ";   
   echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$lastpage'>&gt;&gt;</a> ";
}
?>
</div>
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
