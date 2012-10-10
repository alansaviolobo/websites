<?php
require_once('includes/config.php');
require_once('includes/functions/func.global.php');
$config['lang'] = check_user_lang($config);
require_once('includes/lang/lang_'.$config['lang'].'.php');

db_connect($config);

header('Content-type: text/xml');

switch ($_GET['t']) 
{
	case 'latestprojects':
		echo '<?xml version="1.0" encoding="UTF-8"?>';
		echo '<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom">';
		echo '<channel>';
		echo '<title>' . stripslashes($config['site_title']) . '</title>';
		echo '<link>' . $config['site_url'] . '</link>';
		echo '<description>' . stripslashes($config['site_title']) . '</description>';
		echo '<language>en</language>';
		echo '<atom:link href="'.$config['site_url'].'xml.php?t='.$_GET['t'].'" rel="self" type="application/rss+xml" />';
		
		$query = "SELECT project_id,project_title,project_start,project_end,project_bids,project_desc FROM `".$config['db']['pre']."projects` WHERE project_status='0' ORDER BY project_id DESC";
		$query_result = @mysql_query ($query) OR error(mysql_error());
		while ($info = @mysql_fetch_array($query_result))
		{
			//$info['project_desc'] = strip_tags($info['project_desc']);
			$info['project_title'] = str_replace("&","&amp;",stripslashes($info['project_title']));
			$info['project_title'] = str_replace('<','&lt;',$info['project_title']);
			$info['project_title'] = str_replace('>','&gt;',$info['project_title']);
			$info['project_desc'] = str_replace("&","&amp;",stripslashes($info['project_desc']));
			$info['project_desc'] = str_replace('<','&lt;',$info['project_desc']);
			$info['project_desc'] = str_replace('>','&gt;',$info['project_desc']);
			$info['project_desc'] = str_replace('&lt;br /&gt;','<br />',$info['project_desc']);
			$info['project_desc'] = str_replace('&lt;br&gt;','<br>',$info['project_desc']);
		
			echo '<item>';
			echo '<title><![CDATA[' . $info['project_title'] . ']]></title>';
			echo '<link>' . $config['site_url'] . 'project.php?id=' . $info['project_id'] . '</link>';
			echo '<guid>' . $config['site_url'] . 'project.php?id=' . $info['project_id'] . '</guid>';
			echo '<pubDate>'.date('r',$info['project_start']).'</pubDate>';
			echo '<description><![CDATA[' . $info['project_desc'] . ']]></description>';
			echo '</item>';
		}
		
		echo '</channel>';
		echo '</rss>';
		break;
	case 'featuredprojects':
		echo '<?xml version="1.0" encoding="UTF-8"?>';
		echo '<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom">';
		echo '<channel>';
		echo '<title>' . stripslashes($config['site_title']) . '</title>';
		echo '<link>' . $config['site_url'] . '</link>';
		echo '<description>' . stripslashes($config['site_title']) . '</description>';
		echo '<language>en</language>';
		
		$query = "SELECT project_id,project_title,project_start,project_end,project_bids,project_types FROM `".$config['db']['pre']."projects` WHERE project_featured='1' AND  project_status='0' ORDER BY project_id DESC";
		$query_result = @mysql_query ($query) OR error(mysql_error());
		while ($info = @mysql_fetch_array($query_result))
		{
			//$info['project_desc'] = strip_tags($info['project_desc']);
			$info['project_title'] = str_replace("&","&amp;",stripslashes($info['project_title']));
			$info['project_title'] = str_replace('<','&lt;',$info['project_title']);
			$info['project_title'] = str_replace('>','&gt;',$info['project_title']);
			$info['project_desc'] = str_replace("&","&amp;",stripslashes($info['project_desc']));
			$info['project_desc'] = str_replace('<','&lt;',$info['project_desc']);
			$info['project_desc'] = str_replace('>','&gt;',$info['project_desc']);
			$info['project_desc'] = str_replace('&lt;br /&gt;','<br />',$info['project_desc']);
			$info['project_desc'] = str_replace('&lt;br&gt;','<br>',$info['project_desc']);
		
			echo '<item>';
			echo '<title><![CDATA[' . $info['project_title'] . ']]></title>';
			echo '<link>' . $config['site_url'] . 'project.php?id=' . $info['project_id'] . '</link>';
			echo '<guid>' . $config['site_url'] . 'project.php?id=' . $info['project_id'] . '</guid>';
			echo '<pubDate>'.date('r',$info['project_start']).'</pubDate>';
			echo '<description><![CDATA[' . $info['project_desc'] . ']]></description>';
			echo '</item>';
		}
		
		echo '</channel>';
		echo '</rss>';
		break;
}
?>