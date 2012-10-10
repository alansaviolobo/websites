<?
function log_adm_action($config,$summary,$details)
{
	mysql_query("INSERT INTO `".$config['db']['pre']."logs` ( `log_date` , `log_summary` , `log_details` ) VALUES ('".time()."', '".validate_input($summary)."', '".validate_input($details)."');");
}
?>