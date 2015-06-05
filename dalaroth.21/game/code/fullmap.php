<?
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
<link media="screen, projection" href="../../style.css" rel="stylesheet" title="Default" type="text/css" />
<link rel="icon" href="../images/dalaroth.ico" >
<title>Full Map</title>
</head>
<body>

<?
include "functions.php";
echo show_map($_SESSION["userinfo"]["x"]-30,$_SESSION["userinfo"]["x"]+30,$_SESSION["userinfo"]["y"]-30,$_SESSION["userinfo"]["y"]+30);
?>
</body>
</html>
