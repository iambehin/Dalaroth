<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>Untitled</title>
</head>
<body>
<?



if(isset($_REQUEST['x']))
{

function travel_x($_x,$_traveling)
{
if($_traveling === "e")
return $_x+1;
elseif($_traveling === "w")
return $_x-1;
elseif($_traveling === "se")
return ($_x+1);
elseif($_traveling === "sw")
return $_x-1;
elseif($_traveling === "ne")
return $_x+1;
elseif($_traveling === "nw")
return $_x-1;
elseif($_traveling === "reset")
return 0;
else
return $_x;
}//end travel_x


function travel_y($_y,$_traveling)
{

if($_traveling === "s")
return $_y-1;
elseif($_traveling === "n")
return $_y+1;
elseif($_traveling === "se")
return $_y-1;
elseif($_traveling === "sw")
return $_y-1;
elseif($_traveling === "ne")
return $_y+1;
elseif($_traveling === "nw")
return $_y+1;
elseif($_traveling === "reset")
return 0;
else
return $_y;
}//end travel_y



$x = $_REQUEST['x'];
$y = $_REQUEST['y'];
$direction = $_REQUEST["direction"];
$range = $_REQUEST["range"];
$file = $_REQUEST['file'];
$travel = $_REQUEST['travel'];

include "../../mysql.php";

while($range > 0)
{

$result = mysql_query("INSERT INTO locations(x,y,file,travel) VALUES ('$x','$y','$file','$travel')") or die("mysql failed<br/>" . mysql_error());
if($result)
echo "Added: X:$x Y:$y file:$file travel:$travel<br/>";
$range--;

$x = travel_x($x,$direction);
$y = travel_y($y,$direction);
}

echo "<br/><br/><br/>";
}
?>

<form method=post>
X<input name=x>
Y<input name=y>
File<input name=file>
Travel<input name=travel>
Direction<input name=direction>
#<input name=range value=1>
<input type=submit>
</form>
</body>
</html>
