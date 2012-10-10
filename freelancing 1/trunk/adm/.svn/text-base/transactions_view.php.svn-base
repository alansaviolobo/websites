<?php 
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.admin.php');
require_once('class.menu.php');

// Connect to database
db_connect($config);

$obj = new Menu();
$nav = $obj->get_js_menu(0,$config);

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
<td class="heading"><img src="images/icon_viewall.gif" width="26" height="26" alt="" align="absmiddle" hspace="5">View Transactions </td>
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
  <form action="transaction_view.php" method="post" name="f1" id="f1">
    <div align="right">      <br>
    </div>
    <table width="99%" border="0" align="center" cellpadding="2" cellspacing="0">
      <tr bgcolor="#FF9900">
        <td width="50" height="30"><div align="center">
            <input type="checkbox" name="selall" value="checkbox" onClick="checkBox(this)">
        </div></td>
        <td width="100" height="30"><span class="style2">ID</span></td>
        <td height="30" bgcolor="#FF9900" class="style2">Transaction From </td>
		<td height="30" class="style2">Description</td>
        <td height="30" class="style2">Transaction To </td>
        <td height="30" class="style2">Amount</td>
		<td height="30" class="style2">Status</td>
      </tr>
      <tr bgcolor="#000000">
        <td height="1" colspan="7" style="padding:0px;"></td>
      </tr>      
<?php
$count = 0;
$counter = 0;

//Pagination Continued
$query = "SELECT 1 FROM ".$config['db']['pre']."transactions";
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

$query = "SELECT transaction_id,transaction_type,buyer_id,provider_id,transaction_amount,transaction_description,transaction_status,transaction_proc,transaction_method FROM ".$config['db']['pre']."transactions ".$limit;
$query_result = mysql_query($query);
while ($info = @mysql_fetch_array($query_result))
{
	if($info['transaction_type'] == 'adm')
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
	elseif($info['transaction_type'] == 'pro')
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
	

	if($info['transaction_status'] == '1')
	{
		$trans_info[$counter]['status'] = 'Pending';
	}
	else
	{
		$trans_info[$counter]['status'] = 'Completed';
	}
	
	$trans_info[$counter]['from'] = $from;
	$trans_info[$counter]['to'] = $to;
	$trans_info[$counter]['amount'] = $info['transaction_amount'];
	$trans_info[$counter]['colour'] = $colour;
	$trans_info[$counter]['id'] = $info['transaction_id'];
	$trans_info[$counter]['type'] = $info['transaction_type'];
	$trans_info[$counter]['buyer_id'] = $info['buyer_id'];
	$trans_info[$counter]['provider_id'] = $info['provider_id'];
	$trans_info[$counter]['description'] = $info['transaction_description'];
	$trans_info[$counter]['transaction_proc'] = $info['transaction_proc'];
	$trans_info[$counter]['transaction_method'] = $info['transaction_method'];	
	
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
	if($value['type'] == 'pro')
	{
		if($value['transaction_method'] == 'withdraw')
		{
			if($info['transaction_proc']!="3")
			{
				$from = 'Admin';
				$to = $provider_info[$value['provider_id']] . ' (provider)';
			}
			else
			{
				$to = 'Admin';
				$from= $provider_info[$value['provider_id']] . ' (provider)';
			}
		}
		else
		{
			if($value['transaction_proc']!='3')
			{
				$from = $provider_info[$value['provider_id']] . ' (provider)';
				$to = $buyer_info[$value['buyer_id']] . ' (buyer)';
			}
			else
			{
				$to = $provider_info[$value['provider_id']] . ' (provider)';
				$from = $buyer_info[$value['buyer_id']] . ' (buyer)';
			}
			if($buyer_info[$value['buyer_id']]=='')
			{
				$to=$provider_info[$value['provider_id']] . ' (provider)';
			}
		}
	}
	elseif($value['type'] == 'buy')
	{
		if($value['transaction_method'] == 'withdraw')
		{
			if($value['transaction_proc']!='3')
			{
				$from = 'Admin';
				$to = $buyer_info[$value['buyer_id']] . ' (buyer)';
			}
			else
			{
				$to = 'Admin';
				$from = $buyer_info[$value['buyer_id']] . ' (buyer)';
			}
		}
		else
		{
			if($value['transaction_proc'] != '3')
			{
				$from = $buyer_info[$value['buyer_id']] . ' (buyer)';
				$to = $provider_info[$value['provider_id']] . ' (provider)';
			}
			else
			{
				$to = $buyer_info[$value['buyer_id']] . ' (buyer)';
				$from = $provider_info[$value['provider_id']] . ' (provider)';
			}
			if($buyer_info[$value['provider_id']]=='')
			{
				$to= $buyer_info[$value['buyer_id']] . ' (buyer)';
			}
		}
	}
	elseif($value['type'] == 'adm')
	{
		if($value['buyer_id'] == '0')
		{
			if($value['transaction_proc']!='3')
			{
				$from = 'Admin';
				$to = $provider_info[$value['provider_id']] . ' (provider)';
			}
			else
			{
				$to = 'Admin';
				$from = $provider_info[$value['provider_id']] . ' (provider)';
			}
		}
		else
		{
			if($value['transaction_proc']!='3'){
				$from = 'Admin';
				$to = $buyer_info[$value['buyer_id']] . ' (buyer)';
			}
			else
			{
				$to = 'Admin';
				$from = $buyer_info[$value['buyer_id']] . ' (buyer)';
			}
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
<td height="25"><span class="style5"><?php echo $value['status'];?></span></td>
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
          <td width="200" valign="middle">With Selected:&nbsp;<a href="#" onclick="document.f1.action='transaction_view.php'; document.f1.submit();"><img src="images/smicon_viewall2.gif" width="11" height="11" border="0"></a>            </td><td valign="middle">
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