<?php
if( ($settings['deposit_cost'] != '') AND ($settings['deposit_cost'] != '0') )
{
	$_POST['amount'] = $_POST['amount'] + $settings['deposit_cost'];
}

if( ($settings['deposit_percentage'] != '') AND ($settings['deposit_percentage'] != '0') )
{
	$_POST['amount'] = ($_POST['amount'] / 100) * (100 + $settings['deposit_percentage']);
}

$_POST['amount'] = round($_POST['amount'], 2);

$page = new HtmlTemplate ("includes/payments/cheque/deposit.html");
$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang));
$page->SetParameter ('PAYMENT_ID', $_POST['payment_id']);
$page->SetParameter ('AMOUNT', $_POST['amount']);
$page->SetParameter ('PAYABLE_TO', $settings['Payable_To']);
$page->SetParameter ('ADDRESS1', $settings['Address_1']);
$page->SetParameter ('ADDRESS2', $settings['Address_2']);
$page->SetParameter ('CITY', $settings['City']);
$page->SetParameter ('STATE', $settings['State']);
$page->SetParameter ('ZIP_CODE', $settings['Post/Zip_Code']);
$page->SetParameter ('COUNTRY', $settings['Country']);
$page->SetParameter ('USERNAME', $_SESSION['user']['name']);
$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
$page->CreatePageEcho($lang,$config);
?>