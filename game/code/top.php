<!--Begin file: top.php--> <?
function top_of_page()
{
if(isset($_REQUEST['username']))
{
global $uname;
global $x;
global $y;
global $db;
global $traveling;
global $places;
global $combat;
global $stats;
global $equip;
global $file;
global $dbpassword;

$password = strtolower(@$_REQUEST['password']);
$uname = strtolower(@$_REQUEST['username']);

include "../mysql.php";//this file has mysql config options, stores the connection in $db

$traveling = @$_POST['traveling'];


$result = @mysql_query("SELECT * FROM players WHERE name='$uname'",$db);
$number = @mysql_result($result, 0, "number");
if(strlen($number < 1))
{
echo "Username: $uname isn't in database<!--Got number: $number-->";
return false;
}
$dbpassword = @mysql_result($result, 0, "password");
$x = @mysql_result($result, 0, "x");
$y = @mysql_result($result, 0, "y");
$stats = explode("|",@mysql_result($result, 0, "stats"));
$equip = explode("|",@mysql_result($result, 0, "equipment"));

$position_result = @mysql_query("SELECT travel FROM locations WHERE x = '$x' AND y = '$y' LIMIT 1");
$travelable = explode(",",@mysql_result($position_result, 0, "travel"));
if(strlen($file) == 0)
$exploring = true;

if(!in_array($traveling, $travelable) && count($travelable) > 0 && strlen($traveling) > 0 && $traveling !== "reset" && $exploring != true)
$traveling = "nope";//this means not able to travel there, not reseting, and not exploring
elseif($traveling === "reset")
{
$x=0;
$y=0;
}

/* This is how we traveled before, now we have travel_x and travel_y functions, which are better.

if($traveling === "e")
$x++;
elseif($traveling === "w")
$x--;
elseif($traveling === "s")
$y--;
elseif($traveling === "n")
$y++;
elseif($traveling === "se")
{
$y--;
$x++;
}
elseif($traveling === "sw")
{
$y--;
$x--;
}
elseif($traveling === "ne")
{
$y++;
$x++;
}
elseif($traveling === "nw")
{
$y++;
$x--;
}
}*/

$x = travel_x($x,$traveling);
$y = travel_y($y,$traveling);

$new_file = @mysql_result(mysql_query("SELECT * FROM locations WHERE x = '$x' AND y = '$y' LIMIT 1"),0,"file");





if($new_file != "invalid.php")
{
echo "You've gone to: $new_file, from $x, $y";
if($x !== @mysql_result($result, 0, "x") && strlen($x) > 0)
$result2 = mysql_query("UPDATE players SET x='$x' WHERE name='$uname'");
if($y !== @mysql_result($result, 0, "y") && strlen($y) > 0)
$result2 = mysql_query("UPDATE players SET y='$y' WHERE name='$uname'");
}

if($dbpassword === crypt($password, "behin"))
{
setcookie("username","$uname",time()+2880000);
}
}
else
echo "I can't tell you who you are. <a href='../'>Go sign in.</a><br/>";
}//end top_of_page
?>
<!--End File: top.php-->