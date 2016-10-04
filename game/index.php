<?
/****************************************************************
*   This is the main index for Dalaroth.  Written by Behin.
*	For debug, set ?debug=true in the url.
*   Here are initial variables/functions:
****************************************************************/
$_time = microtime();
session_start();
//Disabled because awardspace doesn't allow me to set it, but this would set the max load time of the page to 9 seconds:
//set_time_limit(9);
global $version;
$version = ".1"; // 4 Oct 2016



function top_of_page()
{
	$_topTime = microtime();
	if(isset($_SESSION['uname']))
	{
		global $db;
		include "../mysql.php";
		$result = @mysql_query("SELECT * FROM players WHERE name='".$_SESSION['uname']."'",$db);
		$_SESSION["userinfo"] = mysql_fetch_assoc($result);
		$_SESSION["weaponstats"] = mysql_fetch_array(mysql_query("SELECT * FROM items WHERE number='".$_SESSION["userinfo"]["weapon"]."'",$db));
		$_SESSION["armorstats"] = mysql_fetch_array(mysql_query("SELECT * FROM items WHERE number='".$_SESSION["userinfo"]["armor"]."'",$db));
		$_SESSION["userinfo"]["file"] = traveling_file($_SESSION["userinfo"]["x"],$_SESSION["userinfo"]["y"],@$_POST["traveling"]);
           $_armor = get_armor();
		$_SESSION["AC"] = 10 + min($_armor["maxdex"],floor($_SESSION["userinfo"]["dex"]/2 - 5)) + $_armor["armor"];
           if(isset($_POST["traveling"]))
           	$_POST["traveling"]= name_to_direction($_POST["traveling"]);//This gets around IE's moronic habit of submiting insidetext of a button despite the presence of a value attribute
           if(strlen($_SESSION["userinfo"]["number"] < 1))
			return "Username: ".$_SESSION["uname"]." isn't in database<!--Got number: ".$_SESSION["userinfo"]["number"]."-->";
		return check_for_validation();
	}
	else
		return "I can't tell you who you are. <a target='_PARENT' href='../'>Go sign in.</a><br/>";

}//end top_of_page



function check_for_validation()
{
	global $validated;
	if(isset($_SESSION["dbpassword"]) && $_SESSION["userinfo"]["password"] == $_SESSION["dbpassword"] && isset($_SESSION["uname"]))
		$validated="y";
	elseif(isset($_session["userinfo"]["password"]) || isset($_SESSION["dbpassword"]))
		$validated = "m";
	else
		$validated = "n";
	$_SESSION["validated"] = $validated;
}//end check_for_validation


function echo_background_image()
{
	if(isset($_POST["BGSetting"]))
	{
		$_SESSION["userinfo"]["background"] = $_POST["BGSetting"];
		update_db("background");
		if(file_exists($_SESSION["userinfo"]["background"]))
		return "<img name=backgroundIMG src=".$_SESSION["userinfo"]["background"]." style='border:0' alt='' class=fullsize>";
        else {
			echo "<img src='images/clouds.png' style='border:0' alt='' class=fullsize><!-- debug _begin_db_dump";
			print_r($_SESSION["userinfo"]["background"]);
			return "-->";
            }
	}
	else
		echo "<img src='images/clouds.png' style='border:0' alt='' class=fullsize>";
		
}// end echo_background_image()

function echo_font_color()
{
	if(isset($_POST["setFontColorR"]) && isset($_POST["setFontColorB"]) && isset($_POST["setFontColorG"]))
	{
		$_SESSION["userinfo"]["fontcolor"] = $_POST["setFontColorR"].$_POST["setFontColorG"].$_POST["setFontColorB"];
		update_db("font_color");
	}
	if(isset($_SESSION["userinfo"]["fontcolor"]) && isAColor($_SESSION["userinfo"]["fontcolor"]))
		return $_SESSION["userinfo"]["fontcolor"];
	return "900";
}// end echo_font_color()

function isAColor($sent_color)
{
	$returnThis = 3;
	if(!strlen($sent_color) == 3)
		$sent_color = 900;
	if($sent_color{0} == "1" || $sent_color{0} == "2" || $sent_color{0} == "3" || $sent_color{0} == "4" || $sent_color{0} == "5" || $sent_color{0} == "6" || $sent_color{0} == "7" || $sent_color{0} == "8" || $sent_color{0} == "9" || $sent_color{0} == "A" || $sent_color{0} == "B" || $sent_color{0} == "C" || $sent_color{0} == "D" || $sent_color{0} == "E" || $sent_color{0} == "F")
		$returnThis -= 1;
	if($sent_color{1} == "1" || $sent_color{1} == "2" || $sent_color{1} == "3" || $sent_color{1} == "4" || $sent_color{1} == "5" || $sent_color{1} == "6" || $sent_color{1} == "7" || $sent_color{1} == "8" || $sent_color{1} == "9" || $sent_color{1} == "A" || $sent_color{1} == "B" || $sent_color{1} == "C" || $sent_color{1} == "D" || $sent_color{1} == "E" || $sent_color{1} == "F")
		$returnThis -= 1;
	if($sent_color{2} == "1" || $sent_color{2} == "2" || $sent_color{2} == "3" || $sent_color{2} == "4" || $sent_color{2} == "5" || $sent_color{2} == "6" || $sent_color{2} == "7" || $sent_color{2} == "8" || $sent_color{2} == "9" || $sent_color{2} == "A" || $sent_color{2} == "B" || $sent_color{2} == "C" || $sent_color{2} == "D" || $sent_color{2} == "E" || $sent_color{2} == "F")
		$returnThis -= 1;
	if($returnThis == 0)
		return true;
	else
		return false;

}//end isAColor()

function change_()
{
      //Until I figure out why my reset button isn't working, I'm moving the function here to ensure players don't get stuck in fake places.
          if(isset($_POST["traveling"])&&$_POST["traveling"] == "reset")
                {
                 $_SESSION["userinfo"]["x"]=0;
                 update_db("x");
                 $_SESSION["userinfo"]["y"]=0;
                 update_db("y");
                }
	$_bp=explode(",",$_SESSION["userinfo"]["backpack"]);
	if(isset($_POST["equip_armor"]))
	{
		if($_POST["equip_armor"] != 1 && !in_array($_POST["equip_armor"],$_bp))
			return "ERROR, one Cheater Point Added.\n<br/>";
		if($_SESSION["userinfo"]["armor"] != 1)
			$_SESSION["userinfo"]["backpack"] .= $_SESSION["userinfo"]["armor"].",";
		$_SESSION["userinfo"]["armor"] = $_POST["equip_armor"];
		if($_POST["equip_armor"] == 1)
		{
			update_db("armor");
			update_db("backpack");
			return null;
		}
		$_bp=explode(",",$_SESSION["userinfo"]["backpack"]);
		unset($_bp[array_search($_POST["equip_armor"],$_bp)]);
		$_SESSION["userinfo"]["backpack"] = implode(",",$_bp);
		update_db("backpack");
		update_db("armor");
		echo "\n<br/>Changed Armor to ".$_POST["equip_armor"];
	}
	elseif(isset($_POST["equip_weapon"]))
	{
		if($_POST["equip_weapon"] != 1 && !in_array($_POST["equip_weapon"],$_bp))
			return "ERROR, one Cheater Point Added.\n<br/>";
		if($_SESSION["userinfo"]["weapon"] != 1)
			$_SESSION["userinfo"]["backpack"] .= $_SESSION["userinfo"]["weapon"] . ",";
		$_SESSION["userinfo"]["weapon"] = $_POST["equip_weapon"];
		if($_POST["equip_weapon"] == 1)
		{
			update_db("weapon");
			update_db("backpack");
			return null;
		}
		$_bp=explode(",",$_SESSION["userinfo"]["backpack"]);
		unset($_bp[array_search($_POST["equip_weapon"],$_bp)]);
		$_SESSION["userinfo"]["backpack"] = implode(",",$_bp);
		update_db("backpack");
		update_db("weapon");
		echo "\n<br/>Changed Weapon to ".$_POST["equip_weapon"];
	}
	elseif(isset($_POST["add_credit"]))
	{ 
   	$_stat = @$_POST["add_credit"];
		$stats = array("str","dex","intel","wis","con");
		if(!in_array($_stat,$stats))
			return "ERROR, That's not a stat you can increase.\n<br/>";
		if($_SESSION["userinfo"]["credits"] < $_SESSION["userinfo"][$_stat])
			return "ERROR, You don't have enough stat credits.\n<br/>";
		$_SESSION["userinfo"]["credits"]-=$_SESSION["userinfo"][$_stat];
		$_SESSION["userinfo"][$_POST["add_credit"]]+=1;
		if($_stat == "con")
		{
			$_SESSION["userinfo"]["maxhp"]+=max(0,floor($_SESSION["userinfo"]["con"]/2-5)*$_SESSION["userinfo"]["level"]);
			update_db("maxhp");
		}
		update_db("credits");
		update_db("stats");
		echo "Added one point to ".$_POST["add_credit"].".\n<br/>";
	}
}//end change_()



/****************************************************************
*    End of initial functions
*    Begin body
****************************************************************/
?>
<?php header('Content-type: text/html; charset=utf-8'); ?>
<!doctype html>

<html>
	<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
	<title>
   	Dalaroth - v 
<?
		echo "$version";
?>
	</title>
	<link media="screen, projection" href="style_05.css" rel="stylesheet" title="Default" type="text/css" />
	<link rel="icon" href="./images/dalaroth.ico" >
<?
	if(!function_exists("travel_x"))// test to see if we have these functions yet
          include "code/functions.php"; //This file has functions that will be needed by multiple pages.
	$_SESSION["top_of_page_error"] =  top_of_page();
	$_SESSION["_change_error"] = change_();
   $_SESSION["font_color"] = echo_font_color();
	 //this actually starts the function chain running, but puts errors in a variable.

//we'll echo any errors later, to put them in body element, even though we need some of the vars in top_of_page, before then.
?>

	<style type="text/css">
		BODY
           {
			color:#<?echo $_SESSION["font_color"];?>;
			font-family: "<?echo $_SESSION["userinfo"]["font-family"];?>", Sans-Serif;
		}
	</style>
	<script type="text/javascript">
<!--

	function CenterPOS()
	{
		cent = document.getElementById("CenterPane");
		stats = document.getElementById("StatsPane");
		text = document.getElementById("CenterPaneText");
		cent.style.left  = stats.style.width + "px";
           alert(stats.style.width);
           cent.style.width = window.innerWidth - stats.style.width + "px";
           cent.style.height = (text.style.height + 250) + "px";
           //setTimeout("CenterPOS()",250);
	}
//-->
	</script>
   <meta http-equiv="refresh" content="180">
</head>
<body onload="domenu('statsGeneral');">
	<?
		if(@$_GET["debug"]) 
		{
			var_dump($_SESSION);
			var_dump($_REQUEST);
		}
	?>
	<div id=background class=background>
		<!-- Time Before error_echo: <? echo time_since($_time);?> -->
		<?	echo echo_background_image();
		?>
		<!-- Time after bg image: <? echo time_since($_time);?> -->
	</div>
	<div id="CenterPane" class="CenterPane">
		<div id="TopPane" class="TopPane">
			<img src="./images/frostyBanner.png" alt="Welcome To Dalaroth" style="border:0" id="fullsize" class="fullsize"/>
		</div>
		<!-- Time Since: <? echo time_since($_time);?> -->
		<div id="ChatScroll" class="ChatScroll">
			<a OnMouseOver="<? j_scroll(1,'none','ChatPane','up'); ?>" OnMouseOut="scroll='no'" href="javascript:<? j_scroll(5,'none','ChatPane','up'); ?>" style="background-color:#242;">Chat Up</a>
			<a OnMouseOver="<? j_scroll(1,'none','ChatPane','down'); ?> " OnMouseOut="scroll='no'" href="javascript:<? j_scroll(5,'none','ChatPane','down'); ?>" style="background-color:#262;">Chat Down</a> 
		</div>
		<div  id="ChatPane" class="ChatPane">
			<?	include "./code/chat.php";
			?>
		</div>
		<!-- Time Since: <? echo time_since($_time);?> -->
		<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
		<iframe src="code/CenterPane.php" class=CenterFrame allowTransparency="true">
			Sorry, Dalaroth needs frames.
		</iframe>
		<!-- google ads: -->
			<?	include banner_location();
			?>
		<!-- end of google ads-->

	</div>
	<!-- Time Since: <? echo time_since($_time);?> -->


	<div class="StatsPane">
		<?
			if(file_exists("./code/StatsPane.php"))
				include "./code/StatsPane.php";
			else
				echo "Can't find the stats pane.";
		?>
	</div><!-- ends StatsPane-->


		<!-- Time Since: <? echo time_since($_time);?> -->
	<div class="BottomPane">
		<?
			@mysql_close();
			@mysql_close();
			@mysql_close();
		?>
		Pageload: <? echo time_since($_time);?> seconds. 
		<!-- sadly we are no longer validated
		<a href="http://validator.w3.org/check/referer" target="_BLANK">
			<img src="http://www.w3.org/Icons/valid-xhtml10" style="border:0" alt="Valid XHTML 1.0!" height="31" width="88" />
		 </a>
		-->
	</div>

</body> </html>