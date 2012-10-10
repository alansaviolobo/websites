<?php 
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/lang/lang_'.$config['lang'].'.php');
require_once('class.menu.php');
require_once('../includes/classes/class.template_engine.php');
db_connect($config);

if(isset($_POST))
{
	if(count($_POST) > 1)
	{
		if($_POST['fromtype'] != 'admin')
		{
			$result = @mysql_query ("SELECT ".$_POST['fromtype']."_id FROM `".$config['db']['pre'].$_POST['fromtype']."s` WHERE ".$_POST['fromtype']."_username='" . $_POST['from'] . "' LIMIT 1") OR error(mysql_error());
			$num_rows = mysql_num_rows($result);
			while ($info = @mysql_fetch_array($result))
			{
				$from_user_id = $info[$_POST['fromtype'].'_id'];
			}
			
			if($from_user_id=='')
			{
				echo '<link rel="stylesheet" type="text/css" href="images/style.css">';
				echo "\n<table align='left' cellpadding=5><tr><td><br>No account was found with the username '".$_POST['from']."'.";
				echo "<br><br><a href='#' onclick='history.back()'>Back</a></td></tr></table>";
				die();
			}
			
			$result = @mysql_query ("SELECT balance_amount FROM `".$config['db']['pre'].$_POST['fromtype']."s_balance` WHERE ".$_POST['fromtype']."_id='" . $from_user_id . "' LIMIT 1") OR error(mysql_error());
			echo "2<hr>";
			while ($info = @mysql_fetch_array($result))
			{
				$balance= $info['balance_amount'];
			}
	
			if($balance<$_POST['amount'])
			{
				echo '<link rel="stylesheet" type="text/css" href="images/style.css">';
				echo "\n<table align='left' cellpadding=5><tr><td><br>There are insufficient funds to be able to ".$method." the funds.";
				echo "<br><br><a href='#' onclick='history.back()'>Back</a></td></tr></table>";
				die();
			}
						
			$deducted=$balance-$_POST['amount'];
	
			mysql_query("UPDATE `".$config['db']['pre'].$_POST['fromtype']."s_balance` SET `balance_amount` = '" . $deducted . "' WHERE `".$_POST['fromtype']."_id` = '" . $from_user_id . "' LIMIT 1 ;");
		
		}
		
		if($_POST['totype']!='admin')
		{
			$result = @mysql_query ("SELECT ".$_POST['totype']."_id FROM `".$config['db']['pre'].$_POST['totype']."s` WHERE ".$_POST['totype']."_username='" . $_POST['to'] . "' LIMIT 1") OR error(mysql_error());
			$num_rows = mysql_num_rows($result);
			while ($info = @mysql_fetch_array($result))
			{
				$to_user_id = $info[0];
			}
			
			if($to_user_id=='')
			{
				echo '<link rel="stylesheet" type="text/css" href="images/style.css">';
				echo "\n<table align='left' cellpadding=5><tr><td><br>No account was found with the username '".$_POST['to']."'.";
				echo "<br><br><a href='#' onclick='history.back()'>Back</a></td></tr></table>";
				die();
			}
		
			$result = @mysql_query ("SELECT balance_amount FROM `".$config['db']['pre'].$_POST['totype']."s_balance` WHERE ".$_POST['totype']."_id='" . $to_user_id . "' LIMIT 1") OR error(mysql_error());
			while ($info = @mysql_fetch_array($result))
			{
				$balance= $info['balance_amount'];
			}
			
			if($_POST['fromtype']!='admin')
			{
				if($balance<$_POST['amount'])
				{
					echo '<link rel="stylesheet" type="text/css" href="images/style.css">';
					echo "\n<table align='left' cellpadding=5><tr><td><br>There are insufficient funds to be able to ".$method." the funds.";
					echo "<br><br><a href='#' onclick='history.back()'>Back</a></td></tr></table>";
					die();
				}		
			}
			
			$added=$balance+$_POST['amount'];
			mysql_query("UPDATE `".$config['db']['pre'].$_POST['totype']."s_balance` SET `balance_amount` = '" . $added . "' WHERE `".$_POST['totype']."_id` = '" . $to_user_id . "' LIMIT 1 ;");
		}
		

		if($_POST['fromtype']=='admin')
		{
			$from_user_id='0';
			$type='adm';
		}
		else
		{
			$type=substr($_POST['fromtype'],0,3);
			if($type=='buy')
			{
				$type2='pro';
			}
			else
			{
				$type2='buy';
			}
			
		}
		
		if($_POST['totype'] == 'buyer')
		{
			$fl_user_id=$from_user_id;
			$wm_user_id=$to_user_id;
		}
		elseif($_POST['totype']=='provider')
		{
			$fl_user_id=$to_user_id;
			$wm_user_id=$from_user_id;
		}
		elseif($_POST['totype']=='admin')
		{
			if($_POST['fromtype']=='buyer')
			{
				$fl_user_id='0';
				$wm_user_id=$from_user_id;
			}
			elseif($_POST['fromtype']=='provider')
			{
				$fl_user_id=$from_user_id;
				$wm_user_id='0';
			}
		}
		
		mysql_query("INSERT INTO `".$config['db']['pre']."transactions` ( `transaction_id` , `transaction_type` , `transaction_method` , `buyer_id` , `provider_id` , `transaction_ip` , `transaction_time` , `transaction_amount` , `transaction_description` , `transaction_proc` ) VALUES ('', '".$type."', 'deposit', '" . $wm_user_id . "', '" . $fl_user_id . "', '" . encode_ip($_SERVER,$_ENV) . "', '" . time() . "', '" . addslashes($_POST['amount']) . "', '".addslashes($_POST['description'])."','1');") OR error(mysql_error());	
		transfer($config,'transactions_view.php','Transactiony Added');
		exit;
	}
}

$obj = new Menu();
$nav = $obj->get_js_menu(0,$config);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title><?php echo $config['site_title']; ?> Admin - Add Transaction</title>
<link rel="stylesheet" type="text/css" href="images/style.css">
<SCRIPT LANGUAGE="JavaScript" SRC="menu/JSCookMenu.js"></SCRIPT>
<LINK REL="stylesheet" HREF="menu/themes/Office/theme.css" TYPE="text/css">
<SCRIPT LANGUAGE="JavaScript" SRC="menu/themes/Office/theme.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript">
function formValidation(form){
if(notEmpty(form.description,"Description")){
	if(notEmpty(form.to,"To Username")){
		if(notEmpty(form.from,"From Username")){
			if(notEmpty(form.amount,"Amount")){
				if(notSame(form.fromtype,form.totype,"From Type", "To Type")){
					return true;
				}
			}
		}
	}
}


return false;
}
function notSame(elem1,elem2,fname,sname){
	if(elem1.value == elem2.value){
		alert("The "+fname+" field and the "+sname+" field must not be the same.");
		return false;
	} else {
		return true;
	}
}
function notEmpty(elem,fname){
	var str = elem.value;
	if(str.length == 0){
		alert("You must fill in the "+fname+" field.");
		return false;
	} else {
		return true;
	}
}
</script>
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
<td class="heading"><img src="images/icon_addrule.gif" width="19" height="24" alt="" align="absmiddle" hspace="5">Add Transaction </td>
</tr>
<tr>
<td bgcolor="#333333"><img src="images/dot.gif" width="1" height="1" alt=""></td>
</tr>
</table>
<!--End heading page-->
<br>
<!--Start form-->
<table width="850" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #CCCCCC;" align="center">
<tr>
<td align="center" bgcolor="#F6F6F6" style="padding:15px;">  <form onSubmit="return formValidation(this)" name="form" method="post" action="">
  <table width="70%" cellpadding="0" cellspacing="2" border="0">
    <tr>
      <td width="25%" valign="top"><strong>Transaction Description:</strong></td>
      <td width="55%"><textarea class="textarea" name="description" cols="60" rows="70" id="description"></textarea></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><strong>'From' User Type:</strong></td>
      <td><select onChange="if(this.value=='0')from.value='Admin';" name="fromtype" id="fromtype">
        <option value="admin">Admin</option>
        <option value="provider"><?php echo $lang['PROVIDERU']; ?></option>
        <option value="buyer"><?php echo $lang['BUYERU']; ?></option>
                  </select>      
      </td>
    </tr>
    <tr>
      <td><strong>'From' Username:</strong></td>
      <td><input name="from" type="text" class="textbox" id="from" value="Admin"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><strong>'To' User Type:</strong></td>
      <td><select onChange="if(this.value=='0')from.value='Admin';" name="totype" id="select5">
        <option value="admin">Admin</option>
        <option value="provider"><?php echo $lang['PROVIDERU']; ?></option>
        <option value="buyer"><?php echo $lang['BUYERU']; ?></option>
      </select></td>
    </tr>
    <tr>
      <td><strong>'To' Username:</strong></td>
      <td><input name="to" type="text" class="textbox" id="to" value="Admin"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><strong>Transaction Amount:</strong></td>
      <td>$
          <input name="amount" type="text" class="textbox" id="amount" size="7">
          (Positive Amounts Only) </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td></td>
      <td height="30" style="padding-left:6px;">
        <input name="Submit" type="submit" class="button" value="Submit">
&nbsp;
      <input name="Reset" type="reset" class="button" value="Reset">
      </td>
    </tr>
  </table>
</form></td>
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