<?php
$hostname="54.255.200.93";
$username="vts";
$password="vts@123";
$dbname = "vts";
$conn=mysql_connect($hostname,$username,$password);
$db=mysql_select_db($dbname,$conn);

if($_REQUEST['debug'] == 1){
	if($db)
		echo "DB - Connected";
	else
		echo "Not Connected";
}
?>
