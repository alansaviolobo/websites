<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.admin.php');
require_once('class.menu.php');

// Connect to database
db_connect($config);

$obj = new Menu();
$nav = $obj->get_js_menu(0,$config);

if($_GET['type'] == 'buyer')
{
	$_GET['type'] = 'buyer';
}
else
{
	$_GET['type'] = 'provider';
}

if(isset($_GET['username']))
{
	$_POST['username'] = $_GET['username'];
}

if(isset($_POST['username']))
{
	if($_POST['username'] == '')
	{
		unset($_POST['username']);
	}
}
if(isset($_POST['email']))
{
	if($_POST['email'] == '')
	{
		unset($_POST['email']);
	}
}

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
<title><?php echo $config['site_title']; ?> Admin - Search Users</title>
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

function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}
//--></script>
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
    <td class="heading"><img src="images/icon_editrule.gif" width="19" height="24" alt="" align="absmiddle" hspace="5">Search <?php if($_GET['type'] == 'buyer'){ echo $lang['BUYERU'].'s'; } else { echo $lang['PROVIDERU'].'s'; } ?></td>
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
    <td align="center" bgcolor="#F6F6F6" style="padding:15px;"><div align="right">
        <form action="<?php echo $_GET['type'];?>_delete.php" method="post" name="f1" id="f1">
          <div align="right"> <br>
          </div>
          <table width="99%" border="0" align="center" cellpadding="2" cellspacing="0">
            <tr bgcolor="#FF9900">
              <td width="50" height="30"><div align="center">
                  <input type="checkbox" name="selall" value="checkbox" onClick="checkBox(this)">
              </div></td>
              <td width="200" height="30" class="rowheader">Username</td>
              <td height="30" bgcolor="#FF9900" class="rowheader">Email</td>
			  <td width="150" height="30"><span class="rowheader">Options</span></td>
            </tr>
            <tr>
              <td height="1" colspan="4" style="padding:0px;" bgcolor="#000000"><img src="images/dot.gif" height="1"></td>
            </tr>
            <?php
$count = 0;
$counter = 0;

if(isset($_POST['username']))
{
	$query = "SELECT 1 FROM ".$config['db']['pre'].validate_input($_GET['type'])."s WHERE ".validate_input($_GET['type'])."_username LIKE '%".validate_input($_POST['username'])."%' ";
}
elseif(isset($_POST['email']))
{
	$query = "SELECT 1 FROM ".$config['db']['pre'].validate_input($_GET['type'])."s WHERE ".validate_input($_GET['type'])."_email='".validate_input($_POST['email'])."' ";
}
else
{
	$query = "SELECT 1 FROM ".$config['db']['pre'].validate_input($_GET['type'])."s";
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
$limit = ' LIMIT '.(($pageno-1)*10) .',10';

if(isset($_POST['username']))
{
	$query = "SELECT ".validate_input($_GET['type'])."_id,".validate_input($_GET['type'])."_username,".validate_input($_GET['type'])."_email FROM ".$config['db']['pre'].validate_input($_GET['type'])."s WHERE ".validate_input($_GET['type'])."_username LIKE '%".validate_input($_POST['username'])."%' ".$limit;
}
elseif(isset($_POST['email']))
{
	$query = "SELECT ".validate_input($_GET['type'])."_id,".validate_input($_GET['type'])."_username,".validate_input($_GET['type'])."_email FROM ".$config['db']['pre'].validate_input($_GET['type'])."s WHERE ".validate_input($_GET['type'])."_email='".validate_input($_POST['email'])."' ".$limit;
}
else
{
	$query = "SELECT ".validate_input($_GET['type'])."_id,".validate_input($_GET['type'])."_username,".validate_input($_GET['type'])."_email FROM ".$config['db']['pre'].validate_input($_GET['type'])."s".$limit;
}

$query_result = mysql_query($query);
while ($info = @mysql_fetch_array($query_result))
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
              <td width="50" height="25" align="center"><input type="hidden" name="titles[]" id="titles[]" value="<?php echo $info[$_GET['type'].'_username']; ?>">
                  <input type="checkbox" name="list[]" id="list[]" value="<?php echo $info[$_GET['type'].'_id']; ?>"></td>
              <td height="25"><span class="title1"><?php echo $info[$_GET['type'].'_username']; ?></span></td>
              <td height="25"><span class="title1"><?php echo $info[$_GET['type'].'_email']; ?></span></td>
			  <td height="25">
			  <table border="0" cellpadding="0" cellspacing="0"><tr><td>
                <select name="amenu<?php echo $info[$_GET['type'].'_id'];?>" id="amenu<?php echo $info[$_GET['type'].'_id'];?>" onChange="MM_jumpMenu('parent',this,0)" style="width:145px">
				  <option value="">Options</option>
                  <option value="<?php echo $_GET['type'];?>_edit2.php?id=<?php echo $info[$_GET['type'].'_id'];?>">View/Edit User</option>
                 </select>
</td>
			  </tr>
			  </table>
			  </td>
            </tr>
            <?php
}
?>
            <tr bgcolor="#000000">
              <td height="1" style="padding:0px;" colspan="4"><img src="images/dot.gif" width="1" height="1" alt=""></td>
            </tr>
          </table>
          <div align="left"> <br>
              <table width="99%"  border="0" align="center" cellpadding="2" cellspacing="0">
                <tr>
                  <td width="200" valign="middle">With Selected:&nbsp;<a href="#" onClick="document.f1.action='<?php echo $_GET['type'];?>_edit2.php'; document.f1.submit();"><img src="images/button_edit.gif" width="12" height="13" border="0"></a>
                      <input name="imageField" type="image" src="images/button_empty.gif" width="11" height="13" border="0"></td>
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
	if(isset($_POST['username']))
	{
		echo " <a href='{$_SERVER['PHP_SELF']}?username=".$_POST['username']."&pageno=1'>&lt;&lt;</a> ";
		$prevpage = $pageno-1;
		echo " <a href='{$_SERVER['PHP_SELF']}?username=".$_POST['username']."&pageno=$prevpage'>&lt;</a> ";
	}
	elseif(isset($_POST['email']))
	{
		echo " <a href='{$_SERVER['PHP_SELF']}?email=".$_POST['email']."&pageno=1'>&lt;&lt;</a> ";
		$prevpage = $pageno-1;
		echo " <a href='{$_SERVER['PHP_SELF']}?email=".$_POST['email']."&pageno=$prevpage'>&lt;</a> ";
	}
	else
	{
		echo " <a href='{$_SERVER['PHP_SELF']}?pageno=1'>&lt;&lt;</a> ";
		$prevpage = $pageno-1;
		echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$prevpage'>&lt;</a> ";
	}
}
echo " ( Page $pageno of $lastpage ) ";

if ($pageno != $lastpage AND $numrows!=0) 
{
	$nextpage = $pageno+1;
   
	if(isset($_POST['username']))
	{
		echo " <a href='".$_SERVER['PHP_SELF']."?username=".$_POST['username']."&pageno=$nextpage'>&gt;</a> ";   
		echo " <a href='".$_SERVER['PHP_SELF']."?username=".$_POST['username']."&pageno=$lastpage'>&gt;&gt;</a> ";
	}
	elseif(isset($_POST['email']))
	{
		echo " <a href='".$_SERVER['PHP_SELF']."?email=".$_POST['email']."&pageno=$nextpage'>&gt;</a> ";   
		echo " <a href='".$_SERVER['PHP_SELF']."?email=".$_POST['email']."&pageno=$lastpage'>&gt;&gt;</a> ";
	}
	else
	{
		echo " <a href='".$_SERVER['PHP_SELF']."?pageno=$nextpage'>&gt;</a> ";   
		echo " <a href='".$_SERVER['PHP_SELF']."?pageno=$lastpage'>&gt;&gt;</a> ";
	}
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
<br>
<br>
<!--Start bottom-->
<table width="850" cellpadding="0" cellspacing="0" border="0" align="center">
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