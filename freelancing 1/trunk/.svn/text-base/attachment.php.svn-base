<?php
require_once('includes/config.php');
require_once('includes/functions/func.global.php');

db_connect($config);

if(!isset($_GET['type']))
{
	$_GET['type'] = 'attachment';
}

if($_GET['type'] == 'image')
{
	$query = "SELECT provider_picture,provider_pictype FROM `".$config['db']['pre']."providers` WHERE provider_id='" . validate_input($_GET['id']) . "' LIMIT 1";
	$query_result = @mysql_query ($query) OR error(mysql_error());
	$rows = mysql_num_rows($query_result);
	
	if($rows == 0)
	{
		$filedata = base64_decode('R0lGODlhAQABAIAAAMDAwAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==');
		$filesize = strlen($filedata);
		
		header('Content-type: image/gif');
		header('Content-Length: ' . $filesize);
		header('Connection: Close');
		
		echo $filedata;
		exit;
	}
	else
	{
		while ($info = @mysql_fetch_array($query_result))
		{	
			if($info['provider_picture'] == '')
			{
				$filedata = base64_decode('R0lGODlhAQABAIAAAMDAwAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==');
				$filesize = strlen($filedata);
				
				header('Content-type: image/gif');
				header('Content-Length: ' . $filesize);
				header('Connection: Close');
				
				echo $filedata;
				exit;
			}
			else
			{
				header("Content-type: " . $info['provider_pictype']);
				echo $info['provider_picture'];
			}
		}
	}
}
else
{
	$query = "SELECT file_name,file_type,file_size,file_content FROM `".$config['db']['pre']."attachments` WHERE file_id='" . validate_input($_GET['id']) . "' LIMIT 1";
	$query_result = @mysql_query ($query) OR error(mysql_error());
	$rows = mysql_num_rows($query_result);
		
	if($rows == 0)
	{
		exit;
	}
	else
	{
		while ($info = @mysql_fetch_array($query_result))
		{	
			header('Content-disposition: attachment; filename="' . $info['file_name'] . '"'); 
			header("Content-length: ".$info['file_size']);
			header('Content-type: ' . $info['file_type']);
			echo $info['file_content'];
			exit;
		}
	}
}
?>