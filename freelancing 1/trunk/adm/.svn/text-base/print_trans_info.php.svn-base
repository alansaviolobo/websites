<?
require_once("../includes/config.php");
require_once("../includes/functions/func.global.php");
require_once("../includes/functions/func.admin.php");
require_once("class.menu.php");

db_connect($config);

$array = array();

$query = "SELECT transaction_settings FROM ".$config['db']['pre']."transactions where transaction_id='" . addslashes($_GET['id']) . "' LIMIT 1";
$query_result = mysql_query($query);
while ($info = mysql_fetch_array($query_result))
{
	$array = unserialize($info['transaction_settings']);
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Kubelabs Admin - Trans Info</title>
</head>

<body>
<?
foreach ($array as $key => $value) 
{
	echo "<strong>".ucwords(str_replace('_',' ',$key)) . "</strong><br>";
	echo stripslashes($value) . "<br>";
	
	echo "<br><br>";
}
?>
</body>
</html>
