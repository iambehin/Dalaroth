<?php
global $db;
$host=$_SERVER["HTTP_HOST"];
if($host=="localhost"||$host=="behin"||$host=="192.168.2.3")
	$db = mysql_connect("localhost", "behin_db", "iambob");
else
	$db = mysql_connect("fdb2.awardspace.com", "behin_db", "iambob");

mysql_selectdb("behin_db",$db);
?>

