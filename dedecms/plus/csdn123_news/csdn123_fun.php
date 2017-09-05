<?php
require_once (dirname(__FILE__) . "/../../include/common.inc.php");
$cfg_version=strtolower($cfg_version);
if(!empty($_POST["csdn123_mycontent"]))
{
	
	$csdn123_mycontent=$_POST["csdn123_mycontent"];
	$csdn123_is_utf8=$cfg_version;
	$csdn123_data = array ('csdn123_mycontent' => $csdn123_mycontent,'csdn123_is_utf8' => $csdn123_is_utf8);
	$csdn123_ch = curl_init ();
	curl_setopt ( $csdn123_ch, CURLOPT_URL, "http://www.csdn123.net/opensearch/replaceTongyici_201512.php" );
	curl_setopt ( $csdn123_ch, CURLOPT_POST, 1 );
	curl_setopt ( $csdn123_ch, CURLOPT_HEADER, 0 );
	curl_setopt ( $csdn123_ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt ( $csdn123_ch, CURLOPT_POSTFIELDS, $csdn123_data );
	$csdn123_return = curl_exec ( $csdn123_ch );
	curl_close ( $csdn123_ch );
	echo $csdn123_return;

}
?>