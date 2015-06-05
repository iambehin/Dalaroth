<?php

global $db;
if($_SERVER["HTTP_HOST"]=="localhost")
	$db = mysql_connect("localhost", "behin_db", "iambob");
else
	$db = mysql_connect("fdb2.awardspace.com", "behin_db", "iambob");

mysql_selectdb("behin_db",$db);
?>

