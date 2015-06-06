<?php
global $db;
$host=$_SERVER["HTTP_HOST"];
if($host=="localhost"||$host=="behins_desktop")
	$db = mysql_connect("localhost", "behin_db", "iambob");
else
	$db = mysql_connect("fdb2.awardspace.com", "behin_db", "iambob");

mysql_selectdb("behin_db",$db);
?>

