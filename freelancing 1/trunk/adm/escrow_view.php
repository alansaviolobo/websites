<?php 
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/lang/lang_'.$config['lang'].'.php');
require_once('class.menu.php');

// Connect to database
db_connect($config);

$obj = new Menu();
$nav = $obj->get_js_menu(0,$config);

if(isset($_GET['approve']))
{
	$escrow_exists = mysql_fetch_array(mysql_query("SELECT * FROM `".$config['db']['pre']."escrow` WHERE escrow_id='".validate_input($_GET['approve'])."' LIMIT 1"));

	if($escrow_exists['escrow_from'] == 'buy')
	{
		mysql_query("UPDATE `".$config['db']['pre']."escrow` SET `escrow_status` = '1' WHERE `escrow_id` = '".validate_input($escrow_exists['escrow_id'])."' LIMIT 1 ;");
	
		mysql_query("UPDATE `".$config['db']['pre']."providers_balance` SET `balance_amount` = balance_amount+".$escrow_exists['escrow_amount']." WHERE `provider_id` = '" . $escrow_exists['provider_id'] . "' LIMIT 1 ;");
		mysql_query("INSERT INTO `".$config['db']['pre']."transactions` ( `transaction_id` , `transaction_type` , `transaction_method` , `buyer_id` , `provider_id` , `transaction_ip` , `transaction_time` , `transaction_amount` , `transaction_description` ) VALUES ('', 'web', 'escrow', '" . $escrow_exists['buyer_id'] . "', '" . $escrow_exists['provider_id'] . "', '" . encode_ip($_SERVER,$_ENV) . "', '" . time() . "', '" . validate_input($escrow_exists['escrow_amount']) . "', 'Escrow Transfer from ".$lang['BUYERU']." to ".$lang['PROVIDERU']."');");	
	
	}
	else
	{
		mysql_query("UPDATE `".$config['db']['pre']."escrow` SET `escrow_status` = '1' WHERE `escrow_id` = '".validate_input($escrow_exists['escrow_id'])."' LIMIT 1 ;");
		
		mysql_query("UPDATE `".$config['db']['pre']."buyers_balance` SET `balance_amount` = balance_amount+".$escrow_exists['escrow_amount']." WHERE `buyer_id` = '" . $escrow_exists['buyer_id'] . "' LIMIT 1 ;");				
		mysql_query("INSERT INTO `".$config['db']['pre']."transactions` ( `transaction_id` , `transaction_type` , `transaction_method` , `buyer_id` , `provider_id` , `transaction_ip` , `transaction_time` , `transaction_amount` , `transaction_description` ) VALUES ('', 'fre', 'escrow', '" . $escrow_exists['buyer_id'] . "', '" . $escrow_exists['provider_id'] . "', '" . encode_ip($_SERVER,$_ENV) . "', '" . time() . "', '" . validate_input($escrow_exists['escrow_amount']) . "', 'Escrow Transfer from ".$lang['PROVIDERU']." to ".$lang['BUYERU']."');");
	}

	header("Location: escrow_view.php");
	exit;
}
elseif(isset($_GET['cancel']))
{
	$escrow_exists = mysql_fetch_array(mysql_query("SELECT * FROM `".$config['db']['pre']."escrow` WHERE escrow_id='".validate_input($_GET['cancel'])."' LIMIT 1"));
	
	if($escrow_exists['escrow_from'] == 'buy')
	{
		mysql_query("UPDATE `".$config['db']['pre']."escrow` SET `escrow_status` = '2' WHERE `escrow_id` = '".validate_input($escrow_exists['escrow_id'])."' LIMIT 1 ;");
					
		mysql_query("UPDATE `".$config['db']['pre']."buyers_balance` SET `balance_amount` = balance_amount+".$escrow_exists['escrow_amount']." WHERE `buyer_id` = '" . $escrow_exists['buyer_id'] . "' LIMIT 1 ;");
		mysql_query("INSERT INTO `".$config['db']['pre']."transactions` ( `transaction_id` , `transaction_type` , `transaction_method` , `buyer_id` , `provider_id` , `transaction_ip` , `transaction_time` , `transaction_amount` , `transaction_description` ) VALUES ('', 'web', 'escrow', '" . $escrow_exists['buyer_id'] . "', '" . $escrow_exists['provider_id'] . "', '" . encode_ip($_SERVER,$_ENV) . "', '" . time() . "', '" . validate_input($escrow_exists['escrow_amount']) . "', 'Escrow Cancel from ".$lang['BUYERU']." to ".$lang['PROVIDERU']."');");
	}
	else
	{
		mysql_query("UPDATE `".$config['db']['pre']."escrow` SET `escrow_status` = '2' WHERE `escrow_id` = '".validate_input($escrow_exists['escrow_id'])."' LIMIT 1 ;");
					
		mysql_query("UPDATE `".$config['db']['pre']."providers_balance` SET `balance_amount` = balance_amount+".$escrow_exists['escrow_amount']." WHERE `provider_id` = '" . $escrow_exists['provider_id'] . "' LIMIT 1 ;");				
			mysql_query("INSERT INTO `".$config['db']['pre']."transactions` ( `transaction_id` , `transaction_type` , `transaction_method` , `buyer_id` , `provider_id` , `transaction_ip` , `transaction_time` , `transaction_amount` , `transaction_description` ) VALUES ('', 'fre', 'escrow', '" . $escrow_exists['buyer_id'] . "', '" . $escrow_exists['provider_id'] . "', '" . encode_ip($_SERVER,$_ENV) . "', '" . time() . "', '" . validate_input($escrow_exists['escrow_amount']) . "', 'Escrow Cancel from ".$lang['PROVIDERU']." to ".$lang['BUYERU']."');");
	}
	

	header("Location: escrow_view.php");
	exit;
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
	<title><?php echo $config['site_title']; ?> Admin</title>
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

function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
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
<td class="heading"><img src="images/icon_viewall.gif" width="26" height="26" alt="" align="absmiddle" hspace="5">View Escrow Payments </td>
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
  <form action="escrow_view2.php" method="post" name="f1" id="f1">
    <div align="right">      <br>
    </div>
    <table width="99%" border="0" align="center" cellpadding="2" cellspacing="0">
      <tr bgcolor="#FF9900">
        <td width="50" height="30"><div align="center">
            <input type="checkbox" name="selall" value="checkbox" onClick="checkBox(this)">
        </div></td>
        <td width="90" height="30"><span class="style2">ID</span></td>
        <td width="120" height="30" bgcolor="#FF9900" class="style2">Transaction From </td>
		<td height="30" class="style2">Description</td>
        <td width="120" height="30" class="style2">Transaction To </td>
        <td width="80" height="30" class="style2">Amount</td>
		<td width="150" height="30" class="style2">Options</td>
      </tr>
      <tr bgcolor="#000000">
        <td height="1" colspan="7" style="padding:0px;"></td>
      </tr>      
<?php
$count = 0;
$counter = 0;

//Pagination Continued
$query = "SELECT 1 FROM ".$config['db']['pre']."escrow WHERE escrow_status='0'";
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
$limit = 'LIMIT '.(($pageno-1)*10) .',10';


$buyers = array();
$providers = array();
$trans_info = array();
$provider_info = array();
$buyer_info = array();

$provider_info[0] = '';
$buyer_info[0] = '';

$query = "SELECT * FROM ".$config['db']['pre']."escrow WHERE escrow_status='0' ".$limit;
$query_result = mysql_query($query);
while ($info = @mysql_fetch_array($query_result))
{
	if($info['buyer_from'] == 'adm')
	{
		if($info['provider_id'] == 0)
		{
			$from = 'Admin';
			$to = $info['buyer_id'];
		}
		else
		{
			$from = 'Admin';
			$to = $info['provider_id'];
		}
	}
	elseif($info['buyer_from'] == 'pro')
	{
		$from = $info['provider_id'];
		$to = $info['buyer_id'];
	}
	else
	{
		$from = $info['buyer_id'];
		$to = $info['provider_id'];
	}

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
	

	if($info['transaction_status'] == '0')
	{
		$trans_info[$counter]['status'] = 'Pending';
	}
	elseif($info['transaction_status'] == '2')
	{
		$trans_info[$counter]['status'] = 'Cancelled';
	}
	else
	{
		$trans_info[$counter]['status'] = 'Completed';
	}
	
	$trans_info[$counter]['from'] = $from;
	$trans_info[$counter]['to'] = $to;
	$trans_info[$counter]['amount'] = $info['escrow_amount'];
	$trans_info[$counter]['colour'] = $colour;
	$trans_info[$counter]['id'] = $info['escrow_id'];
	$trans_info[$counter]['buyer_id'] = $info['buyer_id'];
	$trans_info[$counter]['provider_id'] = $info['provider_id'];
	$trans_info[$counter]['description'] = $info['escrow_desc'];
	$trans_info[$counter]['escrow_from'] = $info['escrow_from'];
	
	$counter++;

	if($info['buyer_id'] != 0)
	{
		$buyers[$info['buyer_id']] = $info['buyer_id'];
	}
	
	if($info['provider_id'] != 0)
	{
		$providers[$info['provider_id']] = $info['provider_id'];
	}
}

if(count($buyers) != 0)
{
	$count = 0;
	$sql = "SELECT buyer_id,buyer_username FROM ".$config['db']['pre']."buyers ";
	
	foreach ($buyers as $value) 
	{
		if($count == 0)
		{
			$sql.= "WHERE buyer_id='" . $value . "'";
		}
		else
		{
			$sql.= " OR buyer_id='" . $value . "'";
		}
		
		$count++;
	} 
	$sql.= " LIMIT " . count($buyers);
	
	$query_result2 = mysql_query($sql);
	while ($info2 = @mysql_fetch_array($query_result2))
	{
		$buyer_info[$info2['buyer_id']] = $info2['buyer_username'];
	}
}

if(count($providers) != 0)
{
	$count = 0;
	$sql = "SELECT provider_id,provider_username FROM ".$config['db']['pre']."providers ";
	
	foreach ($providers as $value) 
	{
		if($count == 0)
		{
			$sql.= "WHERE provider_id='" . $value . "'";
		}
		ELSE
		{
			$sql.= " OR provider_id='" . $value . "'";
		}
		
		$count++;
	} 
	$sql.= " LIMIT " . count($providers);
	
	$query_result2 = mysql_query($sql);
	while ($info2 = @mysql_fetch_array($query_result2))
	{
		$provider_info[$info2['provider_id']] = $info2['provider_username'];
	}
}

foreach ($trans_info as $value) 
{
	if($value['escrow_from'] == 'pro')
	{
		$to = $buyer_info[$value['buyer_id']] . ' (buyer)';
		$from = $provider_info[$value['provider_id']] . ' (provider)';
		
		if($buyer_info[$value['provider_id']]=='')
		{
			$to= $buyer_info[$value['buyer_id']] . ' (buyer)';
		}
	}
	elseif($value['escrow_from'] == 'buy')
	{
		$from = $buyer_info[$value['buyer_id']] . ' (buyer)';
		$to = $provider_info[$value['provider_id']] . ' (provider)';
	
		if($buyer_info[$value['buyer_id']]=='')
		{
			$from= $buyer_info[$value['buyer_id']] . ' (buyer)';
		}
	}
?>
      <tr bgcolor="<?php echo $value['colour'];?>">
        <td width="50" height="25" align="center"><input type="checkbox" name="list[]" id="list[]" value="<?php echo $value['id'];?>"></td>
        <td height="25"><span class="style5"><?php echo $value['id'];?></span></td>
        <td height="25"><span class="style5"><?php echo $from;?></span></td>
		<td height="25"><span class="style5"><?php echo stripslashes($value['description']);?></span></td>
        <td height="25"><span class="style5"><?php echo $to; ?></span></td>
        <td height="25"><span class="style5"><?php echo $config['currency_sign'].$value['amount'];?></span></td>
<td height="25"><table border="0" cellpadding="0" cellspacing="0"><tr><td>
                <select name="amenu<?php echo $info['id'];?>" id="amenu<?php echo $info['id'];?>" onChange="MM_jumpMenu('parent',this,0)" style="width:145px">
				  <option value="">Options</option>
                  <option value="escrow_view.php?approve=<?php echo $value['id'];?>">Approve</option>
                  <option value="escrow_view.php?cancel=<?php echo $value['id'];?>">Cancel</option>
                </select>
</td>
			  </tr>
			  </table></td>
      </tr>
      <?
}
?>
      <tr bgcolor="#000000">
        <td height="1" colspan="7" style="padding:0px;"></td>
      </tr>
    </table>
    <div align="left">
      <br>
      <table width="99%"  border="0" align="center" cellpadding="2" cellspacing="0">
        <tr>
          <td width="200" valign="middle">            </td><td valign="middle">
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
   echo " <a href='{$_SERVER['PHP_SELF']}?pageno=1'>&lt;&lt;</a> ";
   $prevpage = $pageno-1;
   echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$prevpage'>&lt;</a> ";
}
echo " ( Page $pageno of $lastpage ) ";

if ($pageno != $lastpage AND $numrows!=0) 
{
   $nextpage = $pageno+1;
   echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$nextpage'>&gt;</a> ";   
   echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$lastpage'>&gt;&gt;</a> ";
}
?>
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