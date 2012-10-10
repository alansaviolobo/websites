<?php 
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/lang/lang_'.$config['lang'].'.php');
require_once('class.menu.php');

db_connect($config);

if(isset($_POST))
{
	if(count($_POST) > 1)
	{
		if(isset($_POST['Submit']))
		{
			$count = 0;
			$sql = "DELETE FROM `".$config['db']['pre']."providers` ";
			
			foreach ($_POST['list'] as $value) 
			{
				if($count == 0)
				{
					$sql.= "WHERE `provider_id` = '" . $value . "'";
				}
				else
				{
					$sql.= " OR `provider_id` = '" . $value . "'";
				}
				
				$count++;
			} 
			$sql.= " LIMIT " . count($_POST['list']);
			
			mysql_query($sql);
			
			transfer($config,'provider_edit.php',$lang['PROVIDERU'].'s Deleted');
			exit;
		}
	}
}

if(isset($_GET['id']))
{
	$_POST['list'][$_GET['id']] = $_GET['id'];
}

$obj = new Menu();
$nav = $obj->get_js_menu(0,$config);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title><?php echo $config['site_title']; ?> Admin - Delete <?php echo $lang['PROVIDERU']; ?>s</title>
<link rel="stylesheet" type="text/css" href="images/style.css">
<SCRIPT LANGUAGE="JavaScript" SRC="menu/JSCookMenu.js"></SCRIPT>
<LINK REL="stylesheet" HREF="menu/themes/Office/theme.css" TYPE="text/css">
<SCRIPT LANGUAGE="JavaScript" SRC="menu/themes/Office/theme.js"></SCRIPT>
<style type="text/css">
<!--
.style6 {font-size: 14px}
.style7 {
	font-size: 12px;
	font-weight: bold;
}
-->
</style>

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
<td class="heading"><img src="images/icon_editrule.gif" width="19" height="24" alt="" align="absmiddle" hspace="5">Delete <?php echo $lang['PROVIDERU']; ?>s</td>
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
  <form action="" method="post" name="f1" id="f1">
          <div align="center" class="style6">            
            <table  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><span class="style6"><span class="style7">Are you sure you want to delete the following <?php echo $lang['PROVIDERU']; ?>s?</span><br>
                  </span><br>
<ul>
<?php 
$count = 0;
$sql = "SELECT provider_username,provider_id FROM ".$config['db']['pre']."providers ";

foreach ($_POST['list'] as $value) 
{
	if($count == 0)
	{
		$sql.= "WHERE provider_id='" . $value . "'";
	}
	else
	{
		$sql.= " OR provider_id='" . $value . "'";
	}
	
	$count++;
} 
$sql.= " LIMIT " . count($_POST['list']);

$query_result = mysql_query($sql);
while ($info = @mysql_fetch_array($query_result))
{
	echo "<li>" . $info['provider_username'] . "</li>";
	echo "<input type=\"hidden\" name=\"list[]\" id=\"list[]\" value=\"" . $info['provider_id'] . "\">";
}
?>
</ul>
                  <br>
                  <br>
                  <div align="center">
                    <input name="Submit" type="submit" class="button" id="Submit" value="Yes I'm Sure">
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
