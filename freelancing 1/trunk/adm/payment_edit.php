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
				if(isset($_POST['dboptions']))
				{
					foreach ($_POST['dboptions'][$value] as $key2 => $value2)
					{
						$_POST['dboptions'][$value][$key2] = stripslashes($value2);
					}
				
					$options = serialize($_POST['dboptions'][$value]);
				}
				else
				{
					$options = '';
				}
							
				mysql_query("UPDATE `".$config['db']['pre']."payments` SET `payment_deposit` = '" . addslashes($_POST['deposit'][$value]) . "',`payment_withdraw` = '" . addslashes($_POST['withdraw'][$value]) . "',`payment_subscription` = '" . addslashes($_POST['subscription'][$value]) . "',`payment_title` = '" . addslashes($_POST['title'][$value]) . "',`payment_cost` = '" . addslashes($_POST['deposit_cost'][$value]) . "',`payment_cost_withdraw` = '" . addslashes($_POST['withdraw_cost'][$value]) . "',`payment_desc_deposit` = '" . addslashes($_POST['desc_deposit'][$value]) . "',`payment_desc_withdraw` = '" . addslashes($_POST['desc_withdraw'][$value]) . "',`payment_desc_subscription` = '" . addslashes($_POST['desc_subscription'][$value]) . "',`payment_settings` = '" . addslashes($options) . "',`payment_minimum_withdraw` = '" . addslashes($_POST['min_withdraw'][$value]) . "',`payment_minimum_deposit` = '" . addslashes($_POST['min_deposit'][$value]) . "' WHERE `payment_id` = '" . addslashes($value) . "' LIMIT 1 ;");
			}
			
			transfer($config,'payment_settings.php','Payment Settings Edited');
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
	<title><?php echo $config['site_title']; ?> Admin - Edit Payment</title>
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
<td class="heading"><img src="images/icon_manage.gif" width="26" height="25" alt="" align="absmiddle" hspace="5">Edit Payment Processors</td>
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
$sql = "SELECT payment_id,payment_title,payment_desc_withdraw,payment_desc_deposit,payment_desc_subscription,payment_deposit,payment_withdraw,payment_subscription,payment_cost_withdraw,payment_cost,payment_settings,payment_minimum_withdraw,payment_minimum_deposit FROM ".$config['db']['pre']."payments ";

foreach ($_POST['list'] as $value) 
{
	if($count == 0)
	{
		$sql.= "WHERE payment_id='" . $value . "'";
	}
	ELSE
	{
		$sql.= " OR payment_id='" . $value . "'";
	}
	
	$count++;
} 
$sql.= " LIMIT " . count($_POST['list']);

$query_result = mysql_query($sql);
while ($info = @mysql_fetch_array($query_result))
{
	$options = array();
	$options = unserialize($info['payment_settings']);
	?>
<table width="70%" cellpadding="0" cellspacing="2" border="0">
  <tr>
    <td width="35%"><strong>Payment ID</strong></td>
    <td>:
        <input name="id[<?php echo $info['payment_id'];?>]" type="Text" class="textbox" style="width:316px" value="<?php echo $info['payment_id'];?>" disabled></td>
        <input name="id[<?php echo $info['payment_id'];?>]" type="hidden" class="textbox" style="width:316px" value="<?php echo $info['payment_id'];?>"></td>
  </tr>
  <tr>
    <td width="35%"><strong>Payment Title</strong></td>
    <td>:
        <input name="title[<?php echo $info['payment_id'];?>]" type="Text" class="textbox" style="width:316px" value="<?php echo stripslashes($info['payment_title']);?>"></td>
  </tr>
  <tr>
    <td valign="top"><strong>Payment Deposit Description</strong></td>
    <td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="7" valign="top">:</td>
          <td><textarea name="desc_deposit[<?php echo $info['payment_id'];?>]" class="textbox" style="width:317px"><?php echo stripslashes($info['payment_desc_deposit']);?></textarea></td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td valign="top"><strong>Payment Withdraw Description</strong></td>
    <td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="7" valign="top">:</td>
          <td><textarea name="desc_withdraw[<?php echo $info['payment_id'];?>]" class="textbox" style="width:317px"><?php echo stripslashes($info['payment_desc_withdraw']);?></textarea></td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td valign="top"><strong>Payment Subscription Description</strong></td>
    <td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="7" valign="top">:</td>
          <td><textarea name="desc_subscription[<?php echo $info['payment_id'];?>]" class="textbox" style="width:317px"><?php echo stripslashes($info['payment_desc_subscription']);?></textarea></td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td><strong>Payment Deposits</strong></td>
    <td>:
        <select name="deposit[<?php echo $info['payment_id'];?>]" style="width:316px">
          <option value="1" <?php if($info['payment_deposit'] == 1){ echo "selected"; } ?>>Enable</option>
          <option value="0" <?php if($info['payment_deposit'] == 0){ echo "selected"; } ?>>Disable</option>
        </select></td>
  </tr>
  <tr>
    <td><strong>Payment Withdrawals</strong></td>
    <td>:
        <select name="withdraw[<?php echo $info['payment_id'];?>]" style="width:316px">
          <option value="1" <?php if($info['payment_withdraw'] == 1){ echo "selected"; } ?>>Enable</option>
          <option value="0" <?php if($info['payment_withdraw'] == 0){ echo "selected"; } ?>>Disable</option>
        </select></td>
  </tr>
  <tr>
    <td><strong>Payment Subscriptions</strong></td>
    <td>:
        <select name="subscription[<?php echo $info['payment_id'];?>]" style="width:316px">
          <option value="1" <?php if($info['payment_subscription'] == 1){ echo "selected"; } ?>>Enable</option>
          <option value="0" <?php if($info['payment_subscription'] == 0){ echo "selected"; } ?>>Disable</option>
        </select></td>
  </tr>
    <tr height="20">
    <td></td>
    <td></td>
  </tr>
<?
if(is_array($options))
{
	foreach ($options as $key => $value) 
	{
		$title = explode('_', $key);
	?>
	  <tr>
		<td><strong>
		<?
		foreach ($title as $key2 => $value2) 
		{
			echo strtoupper(substr($value2,0,1)) . substr($value2,1) . ' ';
		}
		?></strong></td>
		<td>:
			<input name="dboptions[<?php echo $info['payment_id'];?>][<?php echo $key; ?>]" type="Text" class="textbox" style="width:316px" value="<?php echo htmlspecialchars($value); ?>"></td>
	  </tr>
	<?
	}
}
?>
    <tr height="20">
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td><strong>Display Deposit Cost</strong></td>
    <td>:
        <input name="deposit_cost[<?php echo $info['payment_id'];?>]" type="Text" class="textbox" style="width:316px" value="<?php echo stripslashes($info['payment_cost']);?>"></td>
  </tr>
  <tr>
    <td><strong>Display Withdraw Cost</strong></td>
    <td>:
        <input name="withdraw_cost[<?php echo $info['payment_id'];?>]" type="Text" class="textbox" style="width:316px" value="<?php echo stripslashes($info['payment_cost_withdraw']);?>"></td>
  </tr>
  <tr>
    <td><strong>Minimum Withdraw Cost</strong></td>
    <td>:
        <input name="min_withdraw[<?php echo $info['payment_id'];?>]" type="Text" class="textbox" style="width:316px" value="<?php echo stripslashes($info['payment_minimum_withdraw']);?>"></td>
  </tr>
  <tr>
    <td><strong>Minimum Deposit Cost</strong></td>
    <td>:
        <input name="min_deposit[<?php echo $info['payment_id'];?>]" type="Text" class="textbox" style="width:316px" value="<?php echo stripslashes($info['payment_minimum_deposit']);?>"></td>
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