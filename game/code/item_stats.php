<?
if(!isset($_SESSION))
session_start();?>
<!-- Begin File: item_stats.php-->
<html>
<head><title>Item Stats -> <?
echo @$_GET["num"];
?></title>
<style type="text/css">
Body {
background-color:black;
color:white;
}
</style>
</head>
<body>
<?

if($_SESSION["validated"] === "y")
{
echo show_item_stats();
}
else
echo "You're not signed in.";

function show_item_stats()
{
global $db;
include "../../mysql.php";
if(isset($_GET["num"]))
{
if(is_numeric($_GET["num"]))
$item = mysql_fetch_assoc(mysql_query("SELECT * FROM items WHERE number='".$_GET["num"]."'",$db));
else
$item = mysql_fetch_assoc(mysql_query("SELECT * FROM items WHERE name LIKE '".$_GET["num"]."'",$db));
}
else
return null;

if($item==false)
return "Can't find that item\n<br/>";

$item_stats_names = array_keys($item);

for($item_inc = 0;$item_inc < count($item);$item_inc++)
{
echo ucwords($item_stats_names[$item_inc]).": ".ltrim(ucwords($item[$item_stats_names[$item_inc]]),0)."\n<br/>";

}

mysql_close();
}//end show_item_stats()


?>
<br/>
Get another item's stats.
<form action=# method=GET>
Name/Num <input name=num><input type=submit value="Search for Item.">
</form>
</body>
</html>
<!--End file: item_stats.php-->