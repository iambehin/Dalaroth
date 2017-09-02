<?
session_start();
?>
<!-- Begin File: CenterPane.php -->


<html>
	<head>
	<title>
ERROR This layer goes behind the game
	</title>
   	<style type="text/css">
		BODY
           {
			color:#<?echo $_SESSION["font_color"];?>;
		}
	</style>
	<link media="screen, projection" href="../style_05.css" rel="stylesheet" title="Default" type="text/css" />
	<link rel="icon" href="../images/dalaroth.ico" >
   </head>
<body style="background:transparent">

<!-- v.07 visual updates: remove border from Center Pane
<!--  Borders --
<div class="CenterPaneBorderLeft"></div>
<div class="CenterPaneBorderRight"></div>

<div class="CenterPaneBorderTop"></div>
<div class="CenterPaneBorderBottom"></div>

<div class="CenterPaneBorderTopLeft"></div>
<div class="CenterPaneBorderBottomLeft"></div>
<div class="CenterPaneBorderTopRight"></div>
-->

<div class=CenterPaneText id="CenterPaneText">



<?
global $db;
if(!isset($db))
  {
	for($loc="";!file_exists($loc."mysql.php")&& strlen($loc)<50;$loc.="../")
   {
   	//This searches up the directory tree for the mysql database info,up to 50 characters.(roughly 13 directories up)
	}
	if(file_exists($loc."mysql.php"))
	include $loc."mysql.php";
  }
	
if(!function_exists("travel_x"))
include "functions.php";
echo "<!--";
echo $_SESSION["top_of_page_error"];//here's any errors top_of_page had.
echo $_SESSION["_change_error"];
echo "\n -->";

if(are_we_validated())
	echo doall();
else
	echo "You are not validated";
?>
</div><!--This ends CenterPaneText-->

<div class=CenterPaneNav>

<?
if(@$can_I_Travel)
{			
	echo echo_move_buttons();
	echo "HP:<span style='border:2px black dotted;border-radius:20%;position:absolute;background-color:#D01;width:10em;height:1em;overflow:hidden;text-align:left;'>".
		"<span style='border:2px black solid;border-radius:20%;position:absolute;background-color:DarkGreen;height:1em;width:" . (@$_SESSION["userinfo"]["hp"]/$_SESSION["userinfo"]["maxhp"]*10) . "em;text-align:right;'>" . 
		@$_SESSION["userinfo"]["hp"] . "</span></span>\n<br><br>";
    echo "MP:<span style='border:2px black dotted;border-radius:20%;position:absolute;background-color:#66F;width:10em;height:1em;overflow:hidden;text-align:left;'>".
		"<span style='border:2px black solid;border-radius:20%;position:absolute;background-color:blue;height:1em;width:" . (@$_SESSION["userinfo"]["mp"]/$_SESSION["userinfo"]["maxmp"]*10) . "em;text-align:right;'>" . 
	@$_SESSION["userinfo"]["mp"] . "</span></span>\n<br/>";
	echo "\n<br/><div class=mmap>Minimap: \n";
	echo show_map(@$_SESSION["userinfo"]["x"]-3,$_SESSION["userinfo"]["x"]+3,$_SESSION["userinfo"]["y"]-3,$_SESSION["userinfo"]["y"]+3);
}
elseif(@!isset($can_I_Travel))
	echo "<h1>ERROR 47.2 There's been an issue with your sign in.</h1>".
		"<a href='../../' target='_top'>Go sign in.</a>";
    echo "\n<br/><form method=post action='#'><button name=traveling type=submit value=reset>Reset Your Coordinates</button></form>\n";
    echo "\n<br/>\n";
	echo "<a href=\"javascript:popMap();\">Full Map</a></div>";
   
   ?>
<pre>










                         </pre><!--This makes sure the hp span doesn't randomnly jump outside the pane-->
</div>

<!-- v.07 visual updates: remove border from Center Pane
<div class="CenterPaneBorderBottomRight">
</div>
-->
<?
function echo_move_buttons()
{
?>
	<div class=moveButtons>
<?
	if(strlen($_SESSION["userinfo"]["file"]) == 0)
		printbuttons("n,e,s,w");
	else													
		printbuttons($_SESSION["userinfo"]["travelable"]);
?>
	</div>
<?

}//end echo_move_buttons

function travel()
{
	global $db;
	$position_result = mysql_query("SELECT * FROM locations WHERE x = '".$_SESSION["userinfo"]["x"]."' AND y = '".$_SESSION["userinfo"]["y"]."' LIMIT 1");
        $travelable2 = mysql_result($position_result, 0, "travel");
	if(isset($_POST["traveling"]))
		$traveling = $_POST["traveling"];
	else
		$traveling = " ";
	if(!in_array($traveling, explode(",",$_SESSION["userinfo"]["travelable"])) && strlen($traveling) > 0 && strlen($traveling) < 3  && $traveling != " ")
	{
		$traveling = "__";
		echo "ERROR:Can't go there";
	}
	$_SESSION["userinfo"]["x"] = travel_x($_SESSION["userinfo"]["x"],$traveling);
	$_SESSION["userinfo"]["y"] = travel_y($_SESSION["userinfo"]["y"],$traveling);
	$position_result = mysql_query("SELECT * FROM locations WHERE x = '".$_SESSION["userinfo"]["x"]."' AND y = '".$_SESSION["userinfo"]["y"]."' LIMIT 1",$db);
	$_SESSION["userinfo"]["travelable"] = mysql_result($position_result, 0, "travel");

}//end travel()




function doall()
{
	if( are_we_validated())
	{
		function printbuttons($go)
		{
			$allPlaces = array("nw","n","ne","w","e","sw","s","se");
			$places = array_intersect($allPlaces, explode(",",$go));

?>
			<center><form method=post action='#'>
<?
			foreach($allPlaces as $place)
			{
				if(in_array($place,$places))
					echo "<span class=Navigate>" . makeDirectionLink($place) . "</span>";
				else
				{
					$_place =  direction_to_name($place);
					echo "<span>$_place</span>";
				}
				if($place === "ne" || $place === "e")
					echo "\n<br/>";
				echo "\n";
			}



?>

			</form></center>
<?
		}//end printbuttons

		function makeDirectionLink($_direction)
		{
			$_name = direction_to_name($_direction);
			return "<button name=traveling type=submit  class=Navigate value=$_direction >$_name</button>";
		}


           
		function show_location()
		{
			if(file_exists("../locations/".$_SESSION["userinfo"]["file"]) && strlen($_SESSION["userinfo"]["file"]) > 0)
			{
                    		$_SESSION["userinfo"]["file"] = traveling_file($_SESSION["userinfo"]["x"],$_SESSION["userinfo"]["y"],@$_POST["traveling"]);
                      	echo "\n";
				global $display_Travel;
				global $can_I_Travel;
				$can_I_Travel = true;
				$display_Travel = true;
				echo travel();
				echo "<img style='position:relative;border-radius:50px' width=100% src='../images/".$_SESSION["userinfo"]["file"].".jpg'>
				<div style='padding:15px;text-align:center;position:absolute;margin:auto;top:200px;background-color:rgba(255,255,255,.85);border-radius:50px'>";
				include "../locations/".$_SESSION["userinfo"]["file"];
				echo "</div><!--end thisFile-->";
				
                                 echo "<br/>\n";
				if($display_Travel)
                                 	echo "\n<br/>You may travel.\n<br/>";
                                 echo "<br/>\n";
				
			}
			else
                        {
                               	echo "<div class=exclaim>This place doesn't exist.</div>Your browser asked for: ".$_SESSION["userinfo"]["file"]." \n<br>I'm resetting your location. Please reload your browser.";
                                $_SESSION["userinfo"]["x"]=0;
                                $_SESSION["userinfo"]["y"]=0;
                                update_db("x");
                                update_db("y");
                        }
		}//end show_location

	}
	else
		return "You're not signed in. <br/> <a target='_top' href='../'>Go sign in.</a>";

	echo show_location();

	update_db("location");

}//end doall();
?>

</body></html><! End File: CenterPane.php >