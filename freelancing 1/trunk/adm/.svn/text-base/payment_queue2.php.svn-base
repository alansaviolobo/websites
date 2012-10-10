<?php 
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.admin.php');
require_once('class.menu.php');

db_connect($config);

$obj = new Menu();
$nav = $obj->get_js_menu(0,$config);

if(isset($_GET['trans']))
{
	mysql_query("UPDATE `".$config['db']['pre']."transactions` SET `transaction_status` = '".addslashes($_GET['status'])."' WHERE `transaction_id` ='".addslashes($_GET['trans'])."' LIMIT 1 ;");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title><?php echo $config['site_title']; ?> Admin - Payment Queue</title>
<link rel="stylesheet" type="text/css" href="images/style.css">
<SCRIPT LANGUAGE="JavaScript" SRC="menu/JSCookMenu.js"></SCRIPT>
<LINK REL="stylesheet" HREF="menu/themes/Office/theme.css" TYPE="text/css">
<SCRIPT LANGUAGE="JavaScript" SRC="menu/themes/Office/theme.js"></SCRIPT>
<style type="text/css">
<!--
.style6 {
	font-size: 14px;
	font-weight: bold;
}
.style2 {color: #FFFFFF;
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
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
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
<td class="heading"><img src="images/icon_manage.gif" width="26" height="25" alt="" align="absmiddle" hspace="5">Withdrawal Queue </td>
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

    <div align="right">      </div>
    <table width="100%"  border="0" cellspacing="0" cellpadding="4">
</table>
    <table width="99%" border="0" align="center" cellpadding="2" cellspacing="0">
      <tr bgcolor="#FF9900">
        <td width="150" height="30"><span class="style2">Transaction Amount </span></td>
        <td width="150" height="30" bgcolor="#FF9900" class="style2">Transaction Date </td>
        <td width="120" height="30" bgcolor="#FF9900" class="style2">Transaction IP </td>
        <td height="30" bgcolor="#FF9900" class="style2">Transaction Pay </td>
		<td width="150" height="30"><span class="rowheader">Options</span></td>
      </tr>
      <tr bgcolor="#000000">
        <td height="1" colspan="5" style="padding:0px;"></td>
      </tr>
      <?php
$count = 0;
$counter = 0;

$query = "SELECT payment_settings FROM ".$config['db']['pre']."payments where payment_id='" . addslashes($_GET['id']) . "' LIMIT 1";
$query_result = mysql_query($query);
while ($info = @mysql_fetch_array($query_result))
{
	$settings = $info['payment_settings'];
}
$settings2 = unserialize($settings);

$query = "SELECT transaction_id,transaction_amount,transaction_time,transaction_ip,transaction_settings FROM ".$config['db']['pre']."transactions where transaction_status='1' AND transaction_proc='" . $_GET['id'] . "' AND transaction_method='withdraw'";
$query_result = mysql_query($query);
while ($info = @mysql_fetch_array($query_result))
{
	$tran_id = $info['transaction_id'];
	if($info['transaction_settings'] != '')
	{
		$tran_settings = unserialize(stripslashes($info['transaction_settings']));
	}
	else
	{
		$tran_settings = array();
	}
	$tran_amount = $info['transaction_amount'];
	
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
        <td height="25"><span class="style5">$<?php echo $info['transaction_amount']; ?></span></td>
        <td height="25"><span class="style5"><?php echo date('m/d/y \a\t G:i:s', $info['transaction_time']); ?></span></td>
        <td height="25"><span class="style5"><?php echo $info['transaction_ip']; ?></span></td>
        <td height="25"><?php echo eval($settings2['withdraw_link']);?></td>
			  <td height="25">
			  <table border="0" cellpadding="0" cellspacing="0"><tr><td>
                <select name="amenu<?php echo $info['transaction_id'];?>" id="amenu<?php echo $info['transaction_id'];?>" onChange="MM_jumpMenu('parent',this,0)" style="width:145px">
				  <option value="">Options</option>
                  <option value="payment_queue2.php?id=<?php echo $_GET['id'];?>&trans=<?php echo $info['transaction_id'];?>&status=0">Mark As Paid</option>
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
        <td height="1" colspan="5" style="padding:0px;"></td>
      </tr>
    </table>
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