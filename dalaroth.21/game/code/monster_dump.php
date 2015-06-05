<table border=8 cellspacing=4>
<tr>
<style>

<!--
table {
font-size:10px;
}
-->
</style>
<?
global $db;
include "../../mysql.php";
global $monsters;

$monsters = array();

$result = mysql_query("SELECT * FROM monsters") or die(mysql_error());

for($loopvar=0;$loopvar < mysql_num_rows($result);$loopvar++)
{
$mob = mysql_fetch_assoc($result);
echo "\n<br/><br/>";
array_push($monsters,$mob);
}
$squares = array();

//populate the squares:

for($y_row=50;$y_row>7;$y_row--)
{
for($x_row=-10;$x_row<11;$x_row++)
{
array_push($squares,"$x_row,$y_row");
}}
foreach($squares as $square)
{
$square=explode(",",$square);

if($square[0] == -10)
echo "</tr>\n<tr><td>".$square[1]."</td>";
elseif($square[1] == 50)
echo "<td>".$square[0]."</td>";
else
{
echo "<td>";

foreach($monsters as $monster)
{
if($monster["y_min"] < $square[1] && $monster["y_max"] > $square[1])
{
//echo "<font color=".mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).">";
echo "<img src='../".$monster["image"]."' alt='' width=30 height=30 /><br/>";
//echo "</font>";
}
}

echo "</td>";
}
}



?>


</tr>
</table>
