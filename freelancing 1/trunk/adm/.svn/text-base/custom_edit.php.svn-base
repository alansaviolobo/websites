<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.admin.php');
require_once('class.menu.php');

// Connect to database
db_connect($config);

if(isset($_POST['Submit']))
{
	//print_r($_POST);

	foreach ($_POST['id'] as $value) 
	{
		if($_POST['type'] == 'text')
		{
			mysql_query("UPDATE `".$config['db']['pre']."custom_fields` SET `custom_page` = '" . addslashes($_POST['page'][$value]) . "',`custom_title` = '" . addslashes($_POST['title'][$value]) . "',`custom_type` = '" . addslashes($_POST['type'][$value]) . "',`custom_content` = '" . addslashes($_POST['content'][$value]) . "',`custom_min` = '0',`custom_max` = '" . addslashes($_POST['max'][$value]) . "',`custom_required` = '" . addslashes($_POST['required'][$value]) . "' WHERE `custom_id` = '" . $value . "' LIMIT 1 ;");
		}
		else
		{
			if(!isset($_POST['optionslist'][$value]))
			{
				$_POST['optionslist'][$value] = array();
			}
		
			foreach ($_POST['optionslist'][$value] as $key2 => $value2)
			{
				$_POST['optionslist'][$value][$key2] = str_replace(',','&#44;',$_POST['optionslist'][$value][$key2]);
			}
			
			$options = implode(',',$_POST['optionslist'][$value]);
		
			mysql_query("UPDATE `".$config['db']['pre']."custom_fields` SET `custom_page` = '" . addslashes($_POST['page'][$value]) . "',`custom_title` = '" . addslashes($_POST['title'][$value]) . "',`custom_type` = '" . addslashes($_POST['type'][$value]) . "',`custom_content` = '" . addslashes($_POST['content'][$value]) . "',`custom_min` = '0',`custom_max` = '" . addslashes($_POST['max'][$value]) . "',`custom_required` = '" . addslashes($_POST['required'][$value]) . "',`custom_options` = '".validate_input($options)."' WHERE `custom_id` = '" . $value . "' LIMIT 1 ;");
		}
	}
 
	header("Location: custom_view.php");
	exit;
}

if(isset($_GET['id']))
{
	$_POST['list'][] = $_GET['id'];
}

$obj = new Menu();
$nav = $obj->get_js_menu(0,$config);

$field_list = array();

$count = 0;
$sql = "SELECT * FROM ".$config['db']['pre']."custom_fields ";

foreach ($_POST['list'] as $value) 
{
	if($count == 0)
	{
		$sql.= "WHERE custom_id='" . $value . "'";
	}
	else
	{
		$sql.= " OR custom_id='" . $value . "'";
	}
	
	$count++;
} 
$sql.= " LIMIT " . count($_POST['list']);

$query_result = mysql_query($sql);
while ($info = @mysql_fetch_array($query_result))
{
	$field_list[$info['custom_id']] = $info;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $config['site_title']; ?> Admin - Edit Custom Fields</title>
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

function change_type(val,id)
{
	if(val == 'text')
	{
		document.getElementById('selectoptions'+id).style.display = 'none';
	}
	else if(val == 'select')
	{
		document.getElementById('selectoptions'+id).style.display = '';
	}
}

function formSubmitted(form)
{
	<?
	foreach ($field_list as $info)
	{
	?>
	for (var i = 0; i < form.optionslist<?=$info['custom_id'];?>.length; i++) 
	{
        form.optionslist<?=$info['custom_id'];?>.options[i].selected = true;
    }
	<?
	}
	?>
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
<td class="heading"><img src="images/icon_editrule.gif" width="19" height="24" alt="" align="absmiddle" hspace="5">Edit Custom Fields</td>
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
<td align="center" bgcolor="#F6F6F6" style="padding:15px;">
  <form action="" method="post" name="f1" id="f1" onSubmit="return formSubmitted(this)">
<?php
foreach ($field_list as $info)
{
	$options = array();

	if($info['custom_options'])
	{
		$options = explode(',',$info['custom_options']);
	}
?>
<table width="70%" cellpadding="0" cellspacing="2" border="0">
  <tr>
    <td width="35%"><strong>Custom ID</strong></td>
    <td>:
        <input name="id[<?php echo $info['custom_id']; ?>]" type="Text" class="textbox" style="width:60%" value="<?php echo $info['custom_id']; ?>" disabled>
        <input name="id[<?php echo $info['custom_id']; ?>]" type="hidden" class="textbox" value="<?php echo $info['custom_id']; ?>"></td>
  </tr>
 <tr>
    <td width="25%"><strong>Field Title</strong></td>
    <td width="55%">:
        <input name="title[<?php echo $info['custom_id']; ?>]" type="Text" class="textbox" style="width:60%" value="<?php echo $info['custom_title']; ?>"></td>
  </tr>
  <tr>
    <td width="25%"><strong>Field Page</strong></td>
    <td width="55%">:
        <select name="page[<?php echo $info['custom_id']; ?>]" style="width:60%" >
          <option value="create_project" <?php if($info['custom_page'] == 'create_project'){ echo 'selected'; } ?>>Create Project</option>
          <option value="profile_buyer" <?php if($info['custom_page'] == 'profile_buyer'){ echo 'selected'; } ?>>Buyer Profile</option>
          <option value="profile_provider" <?php if($info['custom_page'] == 'profile_provider'){ echo 'selected'; } ?>>Provider Profile</option>
        </select></td>
  </tr>
  <tr>
    <td width="25%"><strong>Field Type</strong></td>
    <td width="55%">:
        <select name="type[<?php echo $info['custom_id']; ?>]" style="width:60%" onChange="change_type(this.value,'<?php echo $info['custom_id']; ?>');" >
          <option value="text" <?php if($info['custom_type'] == 'text'){ echo 'selected'; } ?>>Textfield</option>
          <option value="select" <?php if($info['custom_type'] == 'select'){ echo 'selected'; } ?>>Select Box</option>
        </select></td>
  </tr>
  <tr>
    <td width="25%"><strong>Field Content</strong></td>
    <td width="55%">:
        <select name="content[<?php echo $info['custom_id']; ?>]" style="width:60%" >
          <option value="all" <?php if($info['custom_content'] == 'all'){ echo 'selected'; } ?>>Anything</option>
        </select></td>
  </tr>
  <tr>
    <td width="25%"><strong>Field Required</strong></td>
    <td width="55%">:
        <select name="required[<?php echo $info['custom_id']; ?>]" style="width:60%" >
          <option value="0" <?php if($info['custom_required'] == 0){ echo 'selected'; } ?>>No</option>
        </select></td>
  </tr>
  <tr>
    <td width="25%"><strong>Field Min Characters</strong></td>
    <td width="55%">:
        <input name="min[<?php echo $info['custom_id']; ?>]" type="Text" class="textbox" id="min" style="width:60%" value="<?php echo $info['custom_min']; ?>" disabled></td>
  </tr>
  <tr>
    <td width="25%"><strong>Field Max Characters</strong></td>
    <td width="55%">:
        <input name="max[<?php echo $info['custom_id']; ?>]" type="Text" class="textbox" id="max" style="width:60%" value="<?php echo $info['custom_max']; ?>"></td>
  </tr>
          <tr id="selectoptions<?php echo $info['custom_id']; ?>" style="<? if($info['custom_type'] == 'text'){ ?>display:none;<? } ?>">
            <td width="25%" valign="top" style="padding-top:1px;"><strong>Field Options</strong></td>
        <td width="55%">
                <table border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="7" valign="top">:</td>
<td width="210">
<select name="optionslist[<?php echo $info['custom_id']; ?>][]" id="optionslist<?php echo $info['custom_id']; ?>" size="7" multiple style="width:208px;height:120px;">
<?
foreach ($options as $key => $value)
{
	echo '<option value="'.stripslashes($value).'">'.stripslashes($value).'</option>';
}
?>
</select>
<br>
<br>
<input type="button" value="Delete" onClick="remove(this.form.optionslist<?php echo $info['custom_id']; ?>)" name="deleteitems" style="width:208px;" id="deleteitems">
<br><br>
<input type="text" name="optioninsert<?php echo $info['custom_id']; ?>" value="" style="width:208px;">
<br>
<input type="button" value="Add To List" onClick="move(this.form.optioninsert<?php echo $info['custom_id']; ?>,this.form.optionslist<?php echo $info['custom_id']; ?>)" name="addtoitems" style="width:208px;" id="addtoitems">
</td>
<td valign="top"><table height="120" border="0" cellpadding="0" cellspacing="0">
  <tr><td valign="top"><input type="button" value="&uarr;" onClick="Moveup(this.form.optionslist<?php echo $info['custom_id']; ?>)" name="moveup" id="moveup"></td></tr><tr><td valign="bottom">
  <input type="button" value="&darr;" onClick="Movedown(this.form.optionslist<?php echo $info['custom_id']; ?>)" name="movedown"></td></tr></table></td>
</tr>
</table></td>
          </tr>
</table>
<br><br>
<?php
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