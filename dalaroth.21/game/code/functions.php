<?
$_functime = microtime();
if(file_exists("code/map.php"))
	include "code/map.php";
elseif(file_exists("../code/map.php"))
	include "../code/map.php";


function travel_x($_x,$_traveling)
{
	$_locsArray = array("e" => $_x+1, "w" => $_x-1, "se" => $_x+1, "sw" => $_x-1, "ne" => $_x+1, "nw" => $_x-1, "reset" => 0);
	if(!isset($_locsArray[$_traveling]))
		return $_x;
	return $_locsArray[$_traveling];
}//end travel_x


function travel_y($_y,$_traveling)
{
	$_locsArray = array("s" => $_y-1, "n" => $_y+1, "se" => $_y-1, "sw" => $_y-1, "ne" => $_y+1, "nw" => $_y+1, "reset" => 0);
	if(!isset($_locsArray[$_traveling]))
		return $_y;
	return $_locsArray[$_traveling];
}//end travel_y

function get_weapon_damage()
{
	$weaponeffects = explode(",",$_SESSION["weaponstats"]["effect"]);//the comma splices seperate effects.
	$weapondamage = array("damage"=>"1d3","crit"=>2,"critrange"=>20,"tohit"=>0);
	foreach($weaponeffects as $weaponeffect)
	{
		$weaponeffect = explode(":",$weaponeffect);
           $weapondamage[$weaponeffect[0]]=$weaponeffect[1];
           /*
		if($weaponeffect[0] === "damage")
			$weapondamage[0] = $weaponeffect[1];
		elseif($weaponeffect[0] === "crit")
			$weapondamage[1] = $weaponeffect[1];
		elseif($weaponeffect[0] === "critrange")
			$weapondamage[2] = $weaponeffect[1];
		*/
	}
	return $weapondamage;
}//end get_weapon_damage()

function get_armor()
{
	$armoreffects = explode(",",$_SESSION["armorstats"]["effect"]);//the comma splices seperate effects.
   $armor = array("maxdex"=>0,"armor"=>0);
	foreach($armoreffects as $armoreffect)
	{
		$armoreffect = explode(":",$armoreffect);
           $armor[$armoreffect[0]]=$armoreffect[1];
		//if($armoreffect[0] === "armor")
		//	$armor = $armoreffect[1];
	}
	return $armor;
}//end get_armor()


function are_we_validated()
{
	global $validated;
	if($validated === "y")
		return true;
   if($_SESSION["validated"] == "y")
   	return true;
           
   return false;
}//end are_we_validated


function j_scroll($_amount,$_param,$_object,$_scroll)
{
	echo "scrollAmount='".$_amount."';scrollParam='".$_param."';scrollObject='".$_object."';scroll='".$_scroll."';scrollIt();";
}//end j_scroll()


function find_lowest_value($_array,$_key)
{
	global $_returnthis;
	if(!isset($_returnthis))
		$_returnthis = 999999; //assumes min lower than this
	foreach($_array as $_thatkey => $_item)
	{
		if(is_array($_item))
			$_item = find_lowest_value($_item,$_key);
		if($_item < $_returnthis && $_thatkey == $_key)
			$_returnthis = $_item;
	}

	return $_returnthis-1;

}//end find_lowest_value()


function find_highest_value($_array,$_key)
{
	global $_returnthis;
	if(!isset($_returnthis))
		$_returnthis = -999999; //assumes max higher than this
	foreach($_array as $_thatkey => $_item)
	{
		if(is_array($_item))
			$_item = find_highest_value($_item,$_key);
		if($_item > $_returnthis && $_thatkey == $_key)
			$_returnthis = $_item;
	}
	return $_returnthis+1;

}//end find_lowest_value()




function banner_location()
{
	$__banner__="";
	while(!file_exists($__banner__."banner.php"))
		$__banner__ .= "../";
	return $__banner__."banner.php";

}//end banner_location()




function restorehp($_amount)
{
	if($_SESSION["userinfo"]["hp"]+$_amount < $_SESSION["userinfo"]["maxhp"]) 
		$_SESSION["userinfo"]["hp"] += $_amount;
	else
		$_SESSION["userinfo"]["hp"]=$_SESSION["userinfo"]["maxhp"];

}//end restorehp()

function restoremp($_amount)
{
	if($_SESSION["userinfo"]["mp"]+$_amount < $_SESSION["userinfo"]["maxmp"]) 
		$_SESSION["userinfo"]["mp"] += $_amount;
	else
		$_SESSION["userinfo"]["mp"]=$_SESSION["userinfo"]["maxmp"];
}//end restoremp()

function traveling_file($_x,$_y,$_traveling)
{
	$_x = travel_x($_x,$_traveling);
	$_y = travel_y($_y,$_traveling);
	return @mysql_result(@mysql_query("SELECT * FROM locations WHERE x = '$_x' AND y = '$_y' LIMIT 1"), 0, "file");
}//end traveling_file()


function levelup()
{
	include "../code/xptable.php";
	$_exptable = _the_exp_table();
	while($_SESSION["userinfo"]["exp"] > $_exptable[$_SESSION["userinfo"]["level"]])
	{
		$_SESSION["userinfo"]["level"]++;
		$gainedhp = mt_rand($_SESSION["userinfo"]["maxhp"]*.1,long($_SESSION["userinfo"]["maxhp"])*.2);
		$gainedcredits = mt_rand(2,5);
		echo "You have leveled up!\n<br/>";
		echo "You gain $gainedhp Hit Points.\n<br/>";
		echo "You gain $gainedcredits Stat Credits.\n<br/>";
		echo "You are now level ".$_SESSION["userinfo"]["level"].".\n<br/><br/>\n";
		$_SESSION["userinfo"]["credits"]+=$gainedcredits;
		$_SESSION["userinfo"]["maxhp"] +=$gainedhp;
		$_SESSION["userinfo"]["hp"] = $_SESSION["userinfo"]["maxhp"];
		update_db("level");
		update_db("maxhp");
		update_db("hp");
		update_db("credits");
	}
	return false;
}//end levelup()

function time_since($_timesent)
{
	$_timeend = microtime(); 
	echo number_format(((substr($_timeend,0,9)) + (substr($_timeend,-10)) - (substr($_timesent,0,9)) - (substr($_timesent,-10))),4);
}// end time_since()


function roll($_dice,$show)
{
echo "You have rolled a $_dice.\n<br/>";
	$returnthis = 0;
	foreach(explode(",",$_dice) as $_dice)
	{
		$dice = explode("d",$_dice);
		$multiplier = $dice[0];
		$dice = explode("+",$dice[1]);
		while($multiplier > 0)
		{
			$_roll = mt_rand(1,$dice[0]+0);
			if($show)
				echo " <span class=dice>$_roll</span> ";
			$returnthis += $_roll;
			$multiplier--;
		}
		if($show)
           	echo "\n<br/>";
		if(isset($dice[1]))
			$returnthis += $dice[1];
	}
	return $returnthis;
}//end roll()
function direction_to_name($_provided)
{
	$_names=array(
		"nw" => "NorthWest",
		"n" => "North",
		"ne" => "NorthEast",
		"w" => "West",
		"e" => "East",
		"sw" => "SouthWest",
		"s" => "South",
		"se" => "SouthEast"
	);
	return $_names[$_provided]; 
}//end direction_to_name()
function name_to_direction($_provided)
{
	$_names = array(
		"NorthWest"=>"nw",
		"North"=>"n",
		"NorthEast" => "ne",
		"West" => "w",
		"East" => "e",
		"SouthWest" => "sw",
		"South" => "s",
		"SouthEast" => "se"
	);
   if(isset($_names[$_provided]))
   	return $_names[$_provided];
	return $_provided;
}//end name_to_direction()





function update_db($update_act)
{
	global $db;
	if(!isset($db))
   {
		for($loc="";!file_exists($loc."mysql.php")&& strlen($loc)<50;$loc.="../")//This searches up the directory tree for the mysql database info,up to 50 characters.(roughly 13 directories up)
		{
		}
		if(file_exists($loc."mysql.php"))
		include $loc."mysql.php";
   }
		
//Here are acts that require multiple queries:
	switch ((string)$update_act)
   {
	case "location":
		if(strlen($_SESSION["userinfo"]["x"]) > 0)
			$result2 = mysql_query("UPDATE players SET x='".$_SESSION["userinfo"]["x"]."' WHERE name='".$_SESSION["uname"]."'",$db) or die(mysql_error());
		if(strlen($_SESSION["userinfo"]["y"]) > 0)
			$result2 = mysql_query("UPDATE players SET y='".$_SESSION["userinfo"]["y"]."' WHERE name='".$_SESSION["uname"]."'",$db) or die(mysql_error());
		if(isset($_POST["traveling"]))
			$result2 = mysql_query("UPDATE players SET travelable='".$_SESSION["userinfo"]["travelable"]."' WHERE name='".$_SESSION["uname"]."'",$db) or die(mysql_error());
	break;
	case "stats":
		$result2 = mysql_query("UPDATE players SET str = '".$_SESSION["userinfo"]["str"]."' WHERE name = '".$_SESSION["uname"]."'",$db) or die(mysql_error());
		$result2 = mysql_query("UPDATE players SET dex = '".$_SESSION["userinfo"]["dex"]."' WHERE name = '".$_SESSION["uname"]."'",$db) or die(mysql_error());
		$result2 = mysql_query("UPDATE players SET con = '".$_SESSION["userinfo"]["con"]."' WHERE name = '".$_SESSION["uname"]."'",$db) or die(mysql_error());
		$result2 = mysql_query("UPDATE players SET wis = '".$_SESSION["userinfo"]["wis"]."' WHERE name = '".$_SESSION["uname"]."'",$db) or die(mysql_error());
		$result2 = mysql_query("UPDATE players SET intel = '".$_SESSION["userinfo"]["intel"]."' WHERE name = '".$_SESSION["uname"]."'",$db) or die(mysql_error());
	break;
   case "armor":
		$result2 = mysql_query("UPDATE players SET armor = '".$_SESSION["userinfo"]["armor"]."' WHERE name = '".$_SESSION["uname"]."'",$db) or die(mysql_error());
		$_SESSION["armorstats"] = mysql_fetch_array(mysql_query("SELECT * FROM items WHERE number='".$_SESSION["userinfo"]["armor"]."'",$db));
           $_armor = get_armor();
		$_SESSION["AC"] = 10 + min($_armor["maxdex"],floor($_SESSION["userinfo"]["dex"]/2 - 5)) + $_armor["armor"];
	break;
   case "weapon":
   	$_SESSION["weaponstats"] = mysql_fetch_array(mysql_query("SELECT * FROM items WHERE number='".$_SESSION["userinfo"]["weapon"]."'",$db));
		$result2 = mysql_query("UPDATE players SET weapon = '".$_SESSION["userinfo"]["weapon"]."' WHERE name = '".$_SESSION["uname"]."'",$db) or die(mysql_error());
	break;
	}//end switch
//This is how we do the acts that only require one query:
	$_acts = array(
	"hp"=>"hp","maxhp"=>"maxhp","mp"=>"mp","exp"=>"exp","level"=>"level","gold"=>"gold","background"=>"background",
	"font_color"=>"fontcolor","difficulty"=>"difficulty","statsDisp"=>"statsDisp","backpack"=>"backpack",
   "credits"=>"credits","quest"=>"quest","combat"=>"combat"
	);
	if(isset($_acts[$update_act]))
		$result2 = mysql_query("UPDATE players SET ".$_acts[$update_act]." = '".$_SESSION["userinfo"][$_acts[$update_act]]."' WHERE name = '".$_SESSION["uname"]."'",$db) or die(mysql_error());
}//end update_db()
?>

	<script type="text/javascript">

	function popMap()
	{
		window.open('./code/fullmap.php','Map','scrollbars,resizable=1,status=0');
	}

	var currentdiv = 'statsGeneral';//this is the div currently displaying. Defaults to general.
	document.getElementById(currentdiv).style.display='inline';
	
	function domenu(targetdiv)
	{
		document.getElementById(currentdiv).style.display='none';
		currentdiv=targetdiv;
		document.getElementById(currentdiv).style.display='inline';
	}//end domenu()

	var scroll;
	var scrollAmount = 5;
	var scrollSpeed = 25;
	var scrollObject;
	var scrollParam;

	function scrollIt()
	{
		if(scrollParam == "stats")
			scrollObject = currentdiv;//this sets the object to the stats div currently displayed
		var scrollID = document.getElementById(scrollObject);
		if(scroll == "up")
		{
			scrollID.scrollTop -= scrollAmount;
			setTimeout("scrollIt()",scrollSpeed);
		}
		if(scroll == "down")
		{
			scrollID.scrollTop += scrollAmount/1;//The /1 fixes a wierd bug where firefox was making scrollAmount a string, screwing it all up.
			setTimeout("scrollIt()",scrollSpeed);
		}
	}//end scrollIt()


	function popItemStats(pop_item)
	{
		var pop_this="code/item_stats.php?num="+pop_item;
		window.close("ItemStats");
		window.open(pop_this,"ItemStats","width=400,height=300,scrollbars,resizable=1,status=0")
	}//end popItemStats()

	</script>
<!-- Func time: <?time_since($_functime);?> -->