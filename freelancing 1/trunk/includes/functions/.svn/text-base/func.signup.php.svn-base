<?php
function get_random_id()
{
	$random = '';
	
	for ($i = 1; $i <= 8; $i++) 
	{
		$random.= mt_rand(0, 9);
	}
	
	return $random;
}

function check_account_exists($config,$email,$account_type)
{
	if($account_type=='provider')
	{
		$query = "SELECT provider_id FROM ".$config['db']['pre']."providers WHERE provider_email='" . validate_input($email) . "' LIMIT 1";
		$query_result = mysql_query($query);
		$num_rows = mysql_num_rows($query_result);
	}
	elseif($account_type=='buyer')
	{
		$query = "SELECT buyer_id FROM ".$config['db']['pre']."buyers WHERE buyer_email='" . validate_input($email) . "' LIMIT 1";
		$query_result = mysql_query($query);
		$num_rows = mysql_num_rows($query_result);
	}
		
	return $num_rows;
}

function check_username_exists($config,$username,$account_type)
{
	if($account_type=='provider')
	{
		$query = "SELECT provider_id FROM ".$config['db']['pre']."providers WHERE provider_username='" . validate_input($username) . "' LIMIT 1";
		$query_result = mysql_query($query);
		$num_rows = mysql_num_rows($query_result);
	}
	elseif($account_type=='buyer')
	{
		$query = "SELECT buyer_id FROM ".$config['db']['pre']."buyers WHERE buyer_username='" . validate_input($username) . "' LIMIT 1";
		$query_result = mysql_query($query);
		$num_rows = mysql_num_rows($query_result);
	}
		
	return $num_rows;
}
?>