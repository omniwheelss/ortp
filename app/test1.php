<?php
if($_REQUEST['key'] == '14583' && $_REQUEST['format'] == 'xml'){
	include_once("includes.php");
	if($_REQUEST['username'] != '' && $_REQUEST['password'] != ''){		
		$sql = "SELECT * from user_master WHERE username='" . $_REQUEST['username'] . "' and password='".md5($_REQUEST['password'])."'";
		$result = mysql_query( $sql );
		$row = mysql_fetch_row($result);
		$record_count=mysql_num_rows($result);
		$record_count == 1?$status = "true" : $status = "false";
		$track = $status;
	}
	else{
		$track = "Key not exist";
	}
}
else{
	$track = "Key not valid";
}
$string =<<<XML
<user_status>
<status>$track</status>
</user_status>
XML;
$xml = new SimpleXMLElement($string);
echo $xml->asXML();
?>