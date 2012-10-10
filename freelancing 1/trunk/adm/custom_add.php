<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.admin.php');
require_once('class.menu.php');

// Connect to database
db_connect($config);

if(isset($_POST['title']))
{
	if($_POST['type'] == 'text')
	{
		mysql_query("INSERT INTO `".$config['db']['pre']."custom_fields` (`custom_page` ,`custom_name` ,`custom_title` ,`custom_type` ,`custom_content` ,`custom_min` ,`custom_max` ,`custom_required` ,`custom_options` ,`custom_default`) VALUES ( '".validate_input($_POST['page'])."', '', '".validate_input($_POST['title'])."', '".validate_input($_POST['type'])."', '".validate_input($_POST['content'])."', '0', '".validate_input($_POST['max'])."', '".validate_input($_POST['required'])."', '', '');");
	}
	else
	{
		if(!isset($_POST['optionslist']))
		{
			$_POST['optionslist'] = array();
		}
	
		foreach ($_POST['optionslist'] as $key => $value)
		{
			$_POST['optionslist'][$key] = str_replace(',','&#44;',$value);
		}
		
		$options = implode(',',$_POST['optionslist']);
	
		mysql_query("INSERT INTO `".$config['db']['pre']."custom_fields` (`custom_page` ,`custom_name` ,`custom_title` ,`custom_type` ,`custom_content` ,`custom_min` ,`custom_max` ,`custom_required` ,`custom_options` ,`custom_default`) VALUES ( '".validate_input($_POST['page'])."', '', '".validate_input($_POST['title'])."', '".validate_input($_POST['type'])."', '".validate_input($_POST['content'])."', '0', '".validate_input($_POST['max'])."', '".validate_input($_POST['required'])."', '".validate_input($options)."', '');");
	}

	header("Location: custom_view.php");
	exit;
}

$obj = new Menu();
$nav = $obj->get_js_menu(0,$config);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $config['site_title']; ?> Admin - Add Custom Field</title>
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
<SCRIPT LANGUAGE="JavaScript">
<!-- Original:  Bob Rockers (brockers@subdimension.com) -->
<!-- Begin
function move(fbox,tbox) {
var i = 0;
if(fbox.value != "") {
var no = new Option();
no.value = fbox.value;
no.text = fbox.value;
tbox.options[tbox.options.length] = no;
fbox.value = "";
   }
}
function remove(box) {
for(var i=0; i<box.options.length; i++) {
if(box.options[i].selected && box.options[i] != "") {
box.options[i].value = "";
box.options[i].text = "";
   }
}
BumpUp(box);
} 
function BumpUp(abox) {
for(var i = 0; i < abox.options.length; i++) {
if(abox.options[i].value == "")  {
for(var j = i; j < abox.options.length - 1; j++)  {
abox.options[j].value = abox.options[j + 1].value;
abox.options[j].text = abox.options[j + 1].text;
}
var ln = i;
break;
   }
}
if(ln < abox.options.length)  {
abox.options.length -= 1;
BumpUp(abox);
   }
}
function Moveup(dbox) {
for(var i = 0; i < dbox.options.length; i++) {
if (dbox.options[i].selected && dbox.options[i] != "" && dbox.options[i] != dbox.options[0]) {
var tmpval = dbox.options[i].value;
var tmpval2 = dbox.options[i].text;
dbox.options[i].value = dbox.options[i - 1].value;
dbox.options[i].text = dbox.options[i - 1].text
dbox.options[i-1].value = tmpval;
dbox.options[i-1].text = tmpval2;
      }
   }
}
function Movedown(ebox) {
for(var i = 0; i < ebox.options.length; i++) {
if (ebox.options[i].selected && ebox.options[i] != "" && ebox.options[i+1] != ebox.options[ebox.options.length]) {
var tmpval = ebox.options[i].value;
var tmpval2 = ebox.options[i].text;
ebox.options[i].value = ebox.options[i+1].value;
ebox.options[i].text = ebox.options[i+1].text
ebox.options[i+1].value = tmpval;
ebox.options[i+1].text = tmpval2;
      }
   }
}

function change_type(val)
{
	if(val == 'text')
	{
		document.getElementById('selectoptions').style.display = 'none';
	}
	else if(val == 'select')
	{
		document.getElementById('selectoptions').style.display = '';
	}
}

function formSubmitted(form)
{
	for (var i = 0; i < form.optionslist.length; i++) 
	{
        form.optionslist.options[i].selected = true;
    }
}
//  End -->
</script>
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
<td class="heading"><img src="images/icon_addrule.gif" width="19" height="24" alt="" align="absmiddle" hspace="5">Add Custom Field</td>
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
    <td align="center" bgcolor="#F6F6F6" style="padding:15px;"><form name="form1" method="post" action="custom_add.php" onSubmit="return formSubmitted(this)">
        <table width="70%" cellpadding="0" cellspacing="2" border="0">
          <tr>
            <td width="25%"><strong>Field Title</strong></td>
            <td width="55%">:
                <input name="title" type="Text" class="textbox" id="title" style="width:60%" value=""></td>
          </tr>
          <tr>
            <td width="25%"><strong>Field Page</strong></td>
            <td width="55%">:
                <select name="page" style="width:60%" >
                  <option value="create_project">Create Project</option>
                  <option value="profile_buyer">Buyer Profile</option>
                  <option value="profile_provider">Provider Profile</option>
                </select></td>
          </tr>
          <tr>
            <td width="25%"><strong>Field Type</strong></td>
            <td width="55%">:
                <select name="type" style="width:60%" onChange="change_type(this.value);" >
                  <option value="text" selected>Textfield</option>
                  <option value="select">Select Box</option>
                </select></td>
          </tr>
          <tr>
            <td width="25%"><strong>Field Content</strong></td>
            <td width="55%">:
                <select name="content" style="width:60%" >
                  <option value="all" selected>Anything</option>
                </select></td>
          </tr>
          <tr>
            <td width="25%"><strong>Field Required</strong></td>
            <td width="55%">:
                <select name="required" style="width:60%" >
                  <option value="0" selected>No</option>
                </select></td>
          </tr>
          <tr>
            <td width="25%"><strong>Field Min Characters</strong></td>
            <td width="55%">:
                <input name="min" type="Text" class="textbox" id="min" style="width:60%" value="0" disabled></td>
          </tr>
          <tr>
            <td width="25%"><strong>Field Max Characters</strong></td>
            <td width="55%">:
                <input name="max" type="Text" class="textbox" id="max" style="width:60%" value="50"></td>
          </tr>
          <tr id="selectoptions" style="display:none;">
            <td width="25%" valign="top" style="padding-top:1px;"><strong>Field Options</strong></td>
        <td width="55%">
                <table border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="7" valign="top">:</td>
<td width="100">
<select name="optionslist[]" id="optionslist" size="7" multiple style="width:233px;height:120px;">
</select>
<br>
<br>
<input type="button" value="Delete" onClick="remove(this.form.optionslist)" name="deleteitems" style="width:233px;" id="deleteitems">
<br><br>
<input type="text" name="optioninsert" value="" style="width:233px;">
<br>
<input type="button" value="Add To List" onClick="move(this.form.optioninsert,this.form.optionslist)" name="addtoitems" style="width:233px;" id="addtoitems">
</td>
<td valign="top"><table height="120" border="0" cellpadding="0" cellspacing="0">
  <tr><td valign="top"><input type="button" value="&uarr;" onClick="Moveup(this.form.optionslist)" name="moveup" id="moveup"></td></tr><tr><td valign="bottom">
  <input type="button" value="&darr;" onClick="Movedown(this.form.optionslist)" name="movedown"></td></tr></table></td>
</tr>
</table></td>
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