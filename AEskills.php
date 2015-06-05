<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" >
<link media="screen, projection" href="style.css" rel="stylesheet" title="Default" type="text/css" >
<title>AE Skills Calculator</title>
<script type="text/javascript" >
<!--
function go()
{
  document.SEND.submit();
}
-->
</script>
</head>
<body>
<br/><br/>
<?php

$current  = $_REQUEST['CURRENT'];
$next = $_REQUEST['NEXT'];
?>
<form name="SEND" method="GET" action=AEskills.php>
Type current skill level here: <input name="CURRENT" value="<?php echo $current;?>" />
Type desired level here:<input value="<?php echo $next;?>" name="NEXT" > <a href='javascript:go()'>Calculate</a>

</form>

<?php

if($current == "0")
echo "This skill is at 0.\n<br/>\n";
else
{
echo "Your current level is $current.\n<br/>\n";

$XPnext= (50*(3*($current*$current) + 3*($current)))/3;

echo "Your XP to next level is ".number_format($XPnext).".\n<br/>\n";
}


if($next > 100 || $next < 2 || $next < $current)
$next = 100;

{
$XPtotal = ((50/3)*(($next)*($next)*($next) - $next)) - ((50/3)*(($current)*($current)*($current) - $current));

echo "The XP to $next is ".number_format($XPtotal).".\n<br/>\n";
}


?>
<br/>
<br/>
<br/>
<br/>

  <p>
    <a href="http://validator.w3.org/check?uri=referer"><img
        src="http://www.w3.org/Icons/valid-html401"
        alt="Valid HTML 4.01 Transitional" height="31" width="88"></a>
  </p>
</body>
</html>