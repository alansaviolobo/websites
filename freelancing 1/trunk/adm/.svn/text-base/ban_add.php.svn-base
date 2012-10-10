<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.admin.php');
require_once('class.menu.php');

// Connect to database
db_connect($config);

if(isset($_POST))
{
	if(count($_POST) > 1)
	{
		mysql_query("INSERT INTO `".$config['db']['pre']."bans` ( `ban_type` , `ban_value` , `ban_time` ) VALUES ('".validate_input($_POST['type'])."', '".validate_input($_POST['value'])."', '".time()."');");
	
		transfer($config,'ban_view.php','Ban Added');
		exit;
	}
}

$obj = new Menu();
$nav = $obj->get_js_menu(0,$config);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $config['site_title']; ?> Admin - Add Ban</title>
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

function bantype(btype)
{
	if(btype.value == '')
	{
		document.getElementById('valuen').innerHTML = '';
		document.getElementById('bandetails').style.display = 'none';
		document.getElementById('bansubmit').style.display = 'none';
		document.getElementById('baninfo').style.display = 'none';
	}
	else
	{
		if(btype.value == 'EMAIL')
		{
			document.getElementById('valuen').innerHTML = 'Email Address';
			document.getElementById('baninfotxt').innerHTML = 'Any user trying to signup with this email address will be told they can\'t use it';
		}
		else if(btype.value == 'IP')
		{
			document.getElementById('valuen').innerHTML = 'IP Address';
			document.getElementById('baninfotxt').innerHTML = 'Any user trying to login with this email address will be told they can\'t use it';
		}
		else if(btype.value == 'USERNAME')
		{
			document.getElementById('valuen').innerHTML = 'Username';
			document.getElementById('baninfotxt').innerHTML = 'Any user trying to signup with this username will be told they can\'t use it';
		}
		else if(btype.value == 'PAYPAL')
		{
			document.getElementById('valuen').innerHTML = 'Paypal Address';
			document.getElementById('baninfotxt').innerHTML = 'Any user trying to add this paypal address will be told they can\'t use it';
		}
		
		document.getElementById('bandetails').style.display = '';
		document.getElementById('bansubmit').style.display = '';
		document.getElementById('baninfo').style.display = '';
	}
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
<td class="heading"><img src="images/icon_addrule.gif" width="19" height="24" alt="" align="absmiddle" hspace="5">Add Ban</td>
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
    <td align="center" bgcolor="#F6F6F6" style="padding:15px;"><form name="form1" method="post" action="ban_add.php">
        <table width="70%" cellpadding="0" cellspacing="2" border="0">
          <tr>
            <td width="25%"><strong>Ban Type </strong></td>
            <td width="55%">:
                <select name="type" class="textbox" style="width:60%;" onChange="bantype(this);">
				  <option value="">Select Type</option>
                  <option value="EMAIL">Email Address</option>
				  <option value="USERNAME">Username</option>
				  <option value="PAYPAL">Paypal</option>
                </select></td>
          </tr>
          <tr id="baninfo" style="display:none;">
            <td width="25%"><strong>Ban Info</strong></td>
            <td width="55%">:
                <span id="baninfotxt"></span></td>
          </tr>
          <tr id="bandetails" style="display:none;">
            <td width="25%"><strong><span id="valuen">Ban Value</span></strong></td>
            <td width="55%">:
                <input name="value" type="text" class="textbox" id="value" style="width:60%" value=""></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr id="bansubmit" style="display:none;">
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