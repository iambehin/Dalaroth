<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>Untitled</title>
</head>
<body>
<?

$db = mysql_connect("db1.awardspace.com", "behin_db", "iambob");
mysql_selectdb("behin_db",$db);

$result = mysql_query("SELECT * FROM players where code=\"$validate\"") or die(mysql_error());
$db_email = mysql_result($result, 0, "email");
$db_name = mysql_result($result, 0, "name");
$db_password = mysql_result($result, 0, "password");

$result = mysql_query("INSERT INTO players(name, password, email) VALUES('$db_uname', '$db_pword','$db_email')") or die(mysql_error());



?>


</body>
</html>
