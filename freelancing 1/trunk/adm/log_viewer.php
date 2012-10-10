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

if(isset($_GET['clear']))
{
	mysql_query("TRUNCATE TABLE `".$config['db']['pre']."logs`");
	
	header("Location: log_viewer.php");
	exit;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title><?php echo $config['site_title']; ?> Admin - Log Viewer</title>
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
<script language="JavaScript">
<!--

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

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}
//-->
</script>
</head>

<body onLoad="init()">
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
    <td class="heading"><img src="images/icon_edittemplate.gif" width="22" height="21" alt="" align="absmiddle" hspace="5">Log Viewer </td>
  </tr>
  <tr>
    <td bgcolor="#333333"><img src="images/dot.gif" width="1" height="1" alt=""></td>
  </tr>
</table>
<!--End heading page-->
<!--Start form-->
<br>
<!--Start heading page-->

<table width="850" border="0" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #CCCCCC;">
<tr>
<td align="center" bgcolor="#F6F6F6" style="padding:15px;"><div align="right">
  <form action="content_delete.php" method="post" name="f1" id="f1">
    <div align="right"><input name="delete" type="button" value="Clear Log" onClick="document.location.href='log_viewer.php?clear=1';"></div>
    <br><br><br>
    <table width="99%" border="0" align="center" cellpadding="2" cellspacing="0">
      <tr bgcolor="#FF9900">
        <td width="50" height="30" style="padding-left:5px;"><span class="rowheader">#</span></td>
        <td width="80" height="30"><span class="rowheader">Date</span></td>
        <td width="200" height="30" bgcolor="#FF9900"><span class="rowheader">Summary</span></td>
        <td height="30" bgcolor="#FF9900"><span class="rowheader">Details</span></td>
		</tr>
      <tr bgcolor="#000000">
        <td height="1" style="padding:0px;" colspan="4"><img src="images/dot.gif" width="1" height="1" alt=""></td>
      </tr>      
<?php
$count = 0;
$counter = 0;


//Pagination Continued
$query = "SELECT 1 FROM ".$config['db']['pre']."logs";
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

$query = "SELECT * FROM ".$config['db']['pre']."logs ORDER BY log_date DESC ".$limit;
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
        <td width="50" height="25" valign="top" style="padding-left:5px;"><span class="style5"><?php echo $info['log_id'];?></span></td>
        <td height="25" valign="top"><span class="style5"><?php echo date("Y-m-d H:i:s",$info['log_date']);?></span></td>
        <td width="200" height="25" valign="top"><span class="style5"><?php echo stripslashes($info['log_summary']);?></span></td>
        <td height="25"><div id="ldetails<?php echo $info['log_id'];?>" style="overflow:auto;height:90px;"><span class="style5"><?php echo stripslashes($info['log_details']);?></span></div></td>
		    </tr>
      <?php
}
?>
      <tr bgcolor="#000000">
        <td height="1" style="padding:0px;" colspan="4"><img src="images/dot.gif" width="1" height="1" alt=""></td>
      </tr>
    </table>
    <div align="left">
      <br>
              <table width="99%"  border="0" align="center" cellpadding="2" cellspacing="0">
                <tr>
                  <td width="200" valign="middle">&nbsp;</td>
                  <td valign="middle">
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
	$st=((($pageno-1)*10)+1);
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
                  </div></td>
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
<table width="850" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td bgcolor="#333333"><img src="images/dot.gif" width="1" height="1" alt=""></td>
  </tr>
  <tr>
    <td style="padding:15px;" align="center"><span class="copyright">Copyright &copy; 2008 <a href="http://www.technotrix.co.in" class="copyright" target="_blank">Technotrix</a> All Rights Reserved.</span></td>
  </tr>
</table>
<!--End bottom-->
</body>
</html>