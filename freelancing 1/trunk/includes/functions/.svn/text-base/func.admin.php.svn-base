<?php
db_connect($config);

session_start();

if(!isset($_SESSION['admin']['id']))
{
	header("Location: login.php");
	exit;
}

$pay_proc = array();

$query = "SELECT payment_folder FROM ".$config['db']['pre']."payments";
$query_result = mysql_query($query);
while ($info = mysql_fetch_array($query_result))
{
	$pay_proc[$info['payment_folder']] = $info['payment_folder'];
}

if ($handle = opendir('../includes/payments/')) 
{
	while (false !== ($file = readdir($handle))) 
	{
		if ($file != "." && $file != "..") 
		{
			if(file_exists('../includes/payments/' . $file . '/install.php'))
			{
				if(!isset($pay_proc[$file]))
				{
					require_once('../includes/payments/' . $file . '/install.php');
					exit;
				}
			}
		}
	}
	closedir($handle);
}


if($config['mod_rewrite'] == 1)
{
	if(!file_exists('../.htaccess'))
	{
?>
<script>alert('You currently have mod_rewrite turned on but have not uploaded the\r\n.htaccess file from the docs folder.\r\n\r\n\r\n\r\n\r\n\r\nPlease do this straight away or the script may not function correctly.');</script>
<?
	}
}
?>