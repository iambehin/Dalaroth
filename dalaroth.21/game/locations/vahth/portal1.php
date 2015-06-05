Upon entering the room, you see an elaborate portal emanating mystical pulses.
<br/>
The Portal Authority approaces you.
<br/>
<?php
global $display_Travel;
$display_Travel = false;



if(isset($_POST["portal_travel"]))
{

	if($_POST["portal_travel"] == "gate")
	{
		$_SESSION["userinfo"]["x"]=-1;
		$_SESSION["userinfo"]["y"]=9;
	}
	if($_POST["portal_travel"] == "forest")
	{
		$_SESSION["userinfo"]["x"]=-1;
		$_SESSION["userinfo"]["y"]=36;
	}
   if($_POST["portal_travel"] == "ayra")
   {
		$_SESSION["userinfo"]["x"]=0;
		$_SESSION["userinfo"]["y"]=20;
   	$_SESSION["userinfo"]["travelable"]="n,s,e";
   }
	echo 
	"\"You have been teleported.\"\n".
	"<br/><br/>\n".
	"You no longer see the cobblestone of Vahth outside the room.\n<br/>\n".
	"You may step outside of the portal room now.\n";
}
else
{
	echo
	'"You wish to travel eh?"'."\n<br/>\n".
	"Here are the places I will let you travel:\n<br/>\n".
	"<form action=# method=post>\n".
	"<button name=portal_travel value=gate type=submit>North of the Vahth City Gates.</button>\n<br/>".
	"<button name=portal_travel value=forest type=submit>North of Witch Forest.</button>\n<br/>".
   "<button name=portal_travel value=ayra type=submit>West of Ayra's Garden.</button>\n<br/>".
	"</form>\n";
}

?>
