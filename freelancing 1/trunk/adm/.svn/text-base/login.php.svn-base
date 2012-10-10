<?php
require_once("../includes/config.php");
require_once("../includes/functions/func.global.php");

// Connect to database
db_connect($config);

if(isset($_POST['username']))
{
	if(strlen($_POST['username']) == 0) 
	{
		$error[] = 'Error: Username Missing<br>';
	}
	elseif(strlen($_POST['username']) > 40)
	{
		$error[] = 'Error: Username Too Long<br>';
	}
	elseif(!preg_match("/^[[:alnum:]]+$/", $_POST['username']))
	{
		$error[] = 'Error: Username contains invalid characters<br>';
	}
	
	if(strlen($_POST['password']) == 0)
	{
		$error[] = 'Error: Password Missing<br>';
	}
	elseif(strlen($_POST['password']) > 40)
	{
		$error[] = 'Error: Password Too Long<br>';
	}	
	
	if(!isset($error))
	{
		$query = "SELECT admin_id FROM ".$config['db']['pre']."admins WHERE username='" . validate_input($_POST['username']) . "' AND password='" . validate_input(md5($_POST['password'])) . "' LIMIT 1";
		$query_result = mysql_query($query);
		while ($info = mysql_fetch_array($query_result))
		{
			$admin_id = $info['admin_id'];
		}
		if(isset($admin_id))
		{
			session_start();
			$_SESSION['admin']['id'] = $admin_id;
			$_SESSION['admin']['username'] = $_POST['username'];
			header("Location: index.php");
			exit;
		}
		else
		{
			$error[] = "Error: Username & Password do not match";
		}
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $config['site_title']; ?> - Admin</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
}
.style1 {
	color: #FFFFFF;
	font-size: 12px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
}
.style6 {font-size: 12px; font-weight: bold; font-family: Verdana, Arial, Helvetica, sans-serif; }
.style7 {
	color: #FF0000;
	font-size: 12px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
.style8 {
	font-size: 12px;
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
}
-->
</style>
</head>

<body onload="document.f.username.focus()">

<br>
<br>
<table width="550" border="0" align="center" cellpadding="1" cellspacing="0">
  <tr>
    <td width="50%" height="200" align="center" valign="middle" bgcolor="#FF9900"><table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
	  <tr>
	  <form action="login.php" method="post" name="f" id="f">
        <td bgcolor="#E1E1E1"><div align="center">
          <table border="0" cellspacing="0" cellpadding="3">
            <tr>
              <td class="style8"><div align="left">Username</div></td>
            </tr>
            <tr>
              <td><input name="username" id="username" type="text" style="width: 150px;" value="<?php if(isset($_POST['username'])){ echo stripslashes($_POST['username']); } ?>"></td>
            </tr>
            <tr>
              <td class="style8"><div align="left">Password</div></td>
            </tr>
            <tr>
              <td><input style="width: 150px;" type="password" name="password"></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><input onClick="submit();document.f.Submit.disabled=true;" style="width: 150px;" type="submit" name="Submit" value="login"></td>
            </tr>
          </table>
        </div></td>
		  </form>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>