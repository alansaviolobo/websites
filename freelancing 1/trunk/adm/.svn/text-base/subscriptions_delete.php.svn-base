<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.admin.php');
require_once('class.menu.php');

// Connect to database
db_connect($config);

if(isset($_POST['Submit']))
{
	$count = 0;
	$sql = "DELETE FROM `".$config['db']['pre']."upgrades` ";
	
	foreach ($_POST['list'] as $value) 
	{
		if($count == 0)
		{
			$sql.= "WHERE `upgrade_id` = '" . $value . "'";
		}
		else
		{
			$sql.= " OR `upgrade_id` = '" . $value . "'";
		}
		
		$count++;
	} 
	$sql.= " LIMIT " . count($_POST['list']);
	
	mysql_query($sql);
	
	header("Location: subscriptions_view.php");
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
<title><?php echo $config['site_title']; ?> Admin - Delete Subscribers</title>
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
<td class="heading"><img src="images/icon_editrule.gif" width="19" height="24" alt="" align="absmiddle" hspace="5">Delete Subscribers</td>
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
        <form action="" method="post" name="f1" id="f1">
          <div align="center" class="style6">
            <table  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td>
<span class="style6"><span class="style7">Are you sure you want to delete the following subscribers?</span><br></span><br>
<ul>
<?php 
$count = 0;
$sql = "SELECT upgrade_id,user_id,user_type FROM ".$config['db']['pre']."upgrades ";

foreach ($_POST['list'] as $value) 
{
	if($count == 0)
	{
		$sql.= "WHERE upgrade_id='" . $value . "'";
	}
	else
	{
		$sql.= " OR upgrade_id='" . $value . "'";
	}
	
	$count++;
} 
$sql.= " LIMIT " . count($_POST['list']);

$query_result = mysql_query($sql);
while ($info = @mysql_fetch_array($query_result))
{
	$username = 'Removed';

	if($info['user_type'] == 'buyer')
	{
		$user_info = mysql_fetch_row(mysql_query("SELECT buyer_username FROM ".$config['db']['pre']."buyers WHERE buyer_id='".addslashes($info['user_id'])."' LIMIT 1"));
	
		$username = $user_info[0];
	}
	else
	{
		$user_info = mysql_fetch_row(mysql_query("SELECT provider_username FROM ".$config['db']['pre']."providers WHERE provider_id='".addslashes($info['user_id'])."' LIMIT 1"));
	
		$username = $user_info[0];
	}

	echo "<li>" . $username . "</li>";
	echo "<input type=\"hidden\" name=\"list[]\" id=\"list[]\" value=\"" . $info['upgrade_id'] . "\">";
}
?>
</ul>
                  <br>
                  <br>
                  <div align="center">
                    <input name="Submit" type="submit" class="button" id="Submit" value="Yes I'm Sure">
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