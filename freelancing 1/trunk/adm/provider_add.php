<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.signup.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/lang/lang_'.$config['lang'].'.php');
require_once('class.menu.php');

// Connect to database
db_connect($config);

if(isset($_POST['username']))
{
	$errors = 0;
	
	$_POST['username'] = strip_tags($_POST['username']);
	
	if(ereg('[^A-Za-z0-9]',$_POST['username']))
	{
		$errors++;
		$username_error = $lang['USERALPHA'];
	}
	elseif( (strlen($_POST['username']) < 4) OR (strlen($_POST['username']) > 16) )
	{
		$errors++;
		$username_error = $lang['USERLEN'];
	}
	else
	{
		$avail = mysql_num_rows(mysql_query("SELECT 1 FROM `".$config['db']['pre']."providers` WHERE provider_username='".validate_input($_POST['username'])."' LIMIT 1"));
		
		if($avail)
		{
			$errors++;
			$username_error = $lang['USERUNAV'];
		}
		else
		{
			$username_ban_check = mysql_num_rows(mysql_query("SELECT 1 FROM ".$config['db']['pre']."bans WHERE ban_value='" . validate_input($_POST['username']) . "' AND ban_type='USERNAME' LIMIT 1"));
				
			if($username_ban_check)
			{
				$errors++;
				$username_error = $lang['USERBAN'];
			}
		}
	}
	
	if( (strlen($_POST['password']) < 4) OR (strlen($_POST['password']) > 16) )
	{
		$errors++;
		$password_error = $lang['PASSLENG'];
	}
	
	if(trim($_POST['email']) == '')
	{
		$errors++;
		$email_error = $lang['ENTEREMAIL'];
	}
	elseif(!eregi("^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$", $_POST['email'])) 
	{
		$errors++;
		$email_error = $lang['EMAILINV'];
	}
	else
	{
		$account_exists = check_account_exists($config,$_POST['email'],'provider');
		
		if($account_exists == 1)
		{
			$errors++;
			$email_error = $lang['ACCAEXIST'];
		}
		else
		{
			$email_ban_check = mysql_num_rows(mysql_query("SELECT 1 FROM ".$config['db']['pre']."bans WHERE ban_value='" . validate_input($_POST['email']) . "' AND ban_type='EMAIL' LIMIT 1"));
			
			if($email_ban_check)
			{
				$errors++;
				$email_error = $lang['EMAILBAN'];
			}
		}
	}
	
	if($errors == 0)
	{
		$_POST['jobtype'] = array();
		
		mysql_query("INSERT INTO `".$config['db']['pre']."providers` ( `provider_id` , `provider_username` , `provider_password` , `provider_email` , `provider_name` , `provider_types` , `provider_price` , `provider_joined` , `provider_rating` , `provider_reviews` , `provider_profile` , `provider_notify` , `provider_picture` , `provider_pictype` , `provider_confirm` , `provider_status` ) VALUES ('', '" . validate_input($_POST['username']) . "', '" . validate_input(md5($_POST['password'])) . "', '" . validate_input($_POST['email']) . "', '" . validate_input($_POST['name']) . "', '', '', '" . time() . "', '0', '0', '', '', '', '', '', '1');");

		$user_id = mysql_insert_id();
			
		mysql_query("INSERT INTO `".$config['db']['pre']."providers_balance` ( `provider_id` , `balance_amount` ) VALUES ('" . $user_id . "', '" . $config['start_amount_provider'] . "');");

		header("Location: provider_edit.php");
		exit;
	}
}

$obj = new Menu();
$nav = $obj->get_js_menu(0,$config);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $config['site_title']; ?> Admin - Add <?php echo $lang['PROVIDERU']; ?></title>
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
<td class="heading"><img src="images/icon_addrule.gif" width="19" height="24" alt="" align="absmiddle" hspace="5">Add <?php echo $lang['PROVIDERU']; ?></td>
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
    <td align="center" bgcolor="#F6F6F6" style="padding:15px;"><form name="form1" method="post" action="provider_add.php">
        <table width="70%" cellpadding="0" cellspacing="2" border="0">
          <tr>
            <td width="25%"><strong>Username</strong></td>
            <td width="55%">:
                <input name="username" type="Text" class="textbox" id="username" style="width:60%" value=""><?php if(isset($username_error)){ echo '<br> &nbsp;&nbsp;'.$username_error; } ?></td>
          </tr>
          <tr>
            <td width="25%"><strong>Password</strong></td>
            <td width="55%">:
                <input name="password" type="password" class="textbox" id="password" style="width:60%" value=""><?php if(isset($password_error)){ echo '<br> &nbsp;&nbsp;'.$password_error; } ?></td>
          </tr>
          <tr>
            <td width="25%"><strong>Email</strong></td>
            <td width="55%">:
                <input name="email" type="Text" class="textbox" id="email" style="width:60%" value=""><?php if(isset($email_error)){ echo '<br> &nbsp;&nbsp;'.$email_error; } ?></td>
          </tr>
          <tr>
            <td width="25%"><strong>Name</strong></td>
            <td width="55%">:
                <input name="name" type="Text" class="textbox" id="name" style="width:60%" value=""></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td></td>
            <td height="30" style="padding-left:6px;"><input name="Submit" type="submit" class="button" value="Submit">
&nbsp;
            <input name="Reset" type="reset" class="button" value="Reset">
            </td>
          </tr>
        </table>
    </form></td>
  </tr>
</table>
<!--End form-->
<br>
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