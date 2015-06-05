<html>
<head>
<title>Chart</title>
<style>
table { background-color:green; color:white; border:5px solid black;border-radius:2em;  }
</style>
</head>


<body>
<table>

<?php


for($a = 1;$a <= 25;$a++)
{
echo "<tr><td>$".$a/2*10;
echo "</td><td>";
echo .7*$a."g</td></tr>";
}
?>
</table>
</html>