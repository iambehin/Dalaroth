<!-- Begin File: OPTIMIZE.PHP -->
<!DOCTYPE HTML>

<html>
<head>
<title>Page title</title>
</head>
<body>
<?



function one($_x,$_traveling)
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

}//end one()


function two($_x,$_traveling)
{


$_q = array("e" => $_x+1, "w" => $_x-1, "se" => $_x+1, "sw" => $_x-1, "ne" => $_x+1, "nw" => $_x-1, "reset" => 0);

if(!isset($_q[$_traveling]))
return $_x;

return $_q[$_traveling];

}//end two()



$times1 = array();
$times2 = array();
for($i = 0;$i < 500;$i++)
{

$locs = array("n","s","ne","se","nw","sw","e","w","reset",""," ","COMBAT");

foreach($locs as $loc)
{
$_time = microtime();
one($i,$loc);
$_timesince = microtime()+1-$_time;
echo "<!--$_timesince-->";
array_push($times1,$_timesince);

$_time = microtime();
two($i,$loc);
$_timesince = microtime()+1-$_time;
echo "<!--$_timesince-->\n";
array_push($times2,$_timesince);

}
echo "\n\n\n\n";

}
echo "\n\n\n\n";
echo "\n\n\n\n";
echo "\n\n\n\n";
echo number_format(array_sum($times1)/count($times1),50);
echo "<br/>";
echo number_format(array_sum($times2)/count($times2),50);


?>

</body>
</html><! End File: OPTIMIZE.PHP >
