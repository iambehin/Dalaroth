<?
session_start();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
	<title>
You shouldn't see this.
	</title>
   	<style type="text/css">
		BODY
           {
			color:#<?echo $_SESSION["font_color"];?>;
		}
	</style>
   <meta http-equiv="refresh" content="15">
	<link media="screen, projection" href="../style_05.css" rel="stylesheet" title="Default" type="text/css" />
	<link rel="icon" href="../images/dalaroth.ico" >
   </head>
<body onload="domenu('statsGeneral');">


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
	
include "functions.php";

if(are_we_validated())
{
	echo stats_doall();
}
else
	echo "I can't find your sign in info..<br /><a href='../'>Go sign in.</a>";


function stats_doall()
{

	if(isset($_POST["statsDisp"]))
	{
		$_SESSION["userinfo"]["statsDisp"] = $_POST["statsDisp"];
		update_db("statsDisp");
	}

?>
	<div class=StatsDiv>
<?
	if(isset($_SESSION["userinfo"]["statsDisp"]) && $_SESSION["userinfo"]["statsDisp"] == "list")
	{
		echo echo_move_buttons();
		echo "\n<br/><span class=bigwhite>General:</span><br/>\n\n";
		echo echo_stats_general();
		echo "\n<br/><span class=bigwhite>Stats:</span><br/>\n\n";
		echo echo_stats_stats();
		echo "\n<br/><span class=bigwhite>Equip:</span><br/>\n\n";
		echo echo_stats_equip();
		echo "\n<br/><span class=bigwhite>Settings:</span><br/>\n\n";
		echo echo_stats_settings();
	}
	else
	{

?>
		<div class=statsLinks>

			<a OnMouseOver="domenu('statsGeneral');changeTip('View general Character information.');" onMouseOut="changeTip();" >General</a>
			<a OnMouseOver="domenu('statsStats');changeTip('View your Statistics.');" onMouseOut="changeTip();" >Stats</a>
			<a OnMouseOver="domenu('statsEquip');changeTip('View your Equipment.');" onMouseOut="changeTip();" >Equip</a>
			<a OnMouseOver="domenu('statsSettings');changeTip('Change Page and/or Game Settings.');" onMouseOut="changeTip();" >Settings</a>
		
           </div>

		<div class=StatsDisp id='statsGeneral'>
<?
			echo echo_stats_general();
?>
		</div>
		<div class=StatsDisp id='statsStats'>
<?
			echo echo_stats_stats();
?> 
		</div>
		<div class=StatsDisp id='statsEquip'>
<?
			echo echo_stats_equip();
?>
		</div>
		<div class=StatsDisp id='statsSettings'>
<?
			echo echo_stats_settings();
?>
		</div>
		<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>

		<div class=scroll>
			<center>
				<a OnMouseOver="<? j_scroll(5,'stats','StatsDisp','up'); ?>changeTip('Hover to scroll the Menu Up slowly; click for a faster scroll.');" OnMouseOut="scroll='no';changeTip();" href="javascript:<? j_scroll(15,'stats','StatsDisp','up'); ?>">Menu Up</a>
				<a OnMouseOver="<? j_scroll(5,'stats','StatsDisp','down'); ?>changeTip('Hover to scroll the Menu Down slowly; click for a faster scroll.');" OnMouseOut="scroll='no';changeTip();" href="javascript:<? j_scroll	(15,'stats','StatsDisp','down'); ?>">Menu Down</a>
			</center>
		</div>
		<br/>

<?
	}

?>

	</div><!--ends StatsDiv-->

	<br/><br/><br/><br/>



<?
}//end stats_doall()


/*************************************************************************
* Begin function declarations.                                           *
*************************************************************************/


function echo_stats_general()
{
?>

	Name: 
<? 
	echo ucwords($_SESSION["uname"]); 
?>.
	<br/>
	<form method=post action="../../index.php" target="_PARENT">
		<input type=hidden name=page value="signout.php">
		<input type=hidden name=signout value=y>
		<input type=submit value=Signout>
	</form>
	<?
	echo "Location:<br/>X:" . 
	$_SESSION["userinfo"]["x"] . 
	"<br/>Y:".$_SESSION["userinfo"]["y"] . 
	"<br/>\n<br\>";
	global $version;
	echo "<a onMouseover=\"changeTip('Click this to open version notes for this version of Dalaroth.');\" onMouseOut='changeTip();' target='_blank' href='../version.html'>Dalaroth $version</a> \n";

?>
	<br/>Copyright <a href="mailto:iambehin@gmail.com" target="_BLANK">Behin</a> &#169;2006
	<br/> <a href="../style_05.css" target="_BLANK">Style.</a>
	<br/><a href="../manual.pdf" OnMouseOver="changeTip('View the Dalaroth Manual.');" onMouseOut="changeTip();" target="_BLANK">Manual</a>
<?
}//end echo_stats_general()


function echo_stats_equip()
{
	global $db;
	$backpack_items = array();
	foreach(explode(",",@$_SESSION["userinfo"]["backpack"]) as $backpack_item)
		array_push($backpack_items,mysql_fetch_assoc(mysql_query("SELECT * FROM items WHERE number='".$backpack_item."' LIMIT 1",$db)));

	echo "<b  OnMouseOver=\"changeTip('This is how much gold your character has.');\" onMouseOut='changeTip();' >Gold</b> : ".$_SESSION["userinfo"]["gold"]."\n<br/>";
	echo "<b OnMouseOver=\"changeTip('This is the armor you are currently wearing.');\" onMouseOut='changeTip();'>Armor</b> : <a OnMouseOver=\"changeTip('Click to view the armor you are currently wearing.');\" onMouseOut='changeTip();'href='javascript:popItemStats(".$_SESSION["armorstats"]["number"].")'>" .ucwords(@$_SESSION["armorstats"]["name"]) . "</a> ";
	echo "<form name='equip_armor' action='#' method=POST OnMouseOver=\"changeTip('Change the armor you are wearing here.');\" onMouseOut='changeTip();'><select name='equip_armor'>";
	foreach($backpack_items as $backpack)
	{
		if($backpack["type"] == "armor")
		echo "<option value=".$backpack["number"].">".$backpack["name"]."\n";
	}
	echo "<option value=1>Nothing.\n";
	echo "</select><button type=submit>Equip</button></form>\n";
	echo "<b OnMouseOver=\"changeTip('This is the weapon you are currently wielding.');\" onMouseOut='changeTip();'>Weapon</b> : <a  OnMouseOver=\"changeTip('Click to view the weapon you are currently wielding.');\" onMouseOut='changeTip();'href='javascript:popItemStats(".$_SESSION["weaponstats"]["number"].")'>" .ucwords(@$_SESSION["weaponstats"]["name"]) . "</a> ";
	echo "<form name='equip_weapon' action='#' method=POST OnMouseOver=\"changeTip('Change the weapon you are wielding here.');\" onMouseOut='changeTip();'><select name='equip_weapon'>";
	foreach($backpack_items as $backpack)
	{
		if($backpack["type"] == "weapon")
		echo "<option value=".$backpack["number"].">".$backpack["name"]."\n";
	}
	echo "<option value=1>Nothing\n";
	echo "</select><button type=submit>Equip</button></form>\n";
	echo "<b OnMouseOver=\"changeTip('View items in your backpack.');\" onMouseOut='changeTip();'>Backpack</b> : ";
	array_pop($backpack_items);
	if(count($backpack_items )== 0)
		$backpack_items = array(array("name" =>"Empty","number" => "0"));
	$string_to_echo = "";
	foreach($backpack_items as $backpack)
		$string_to_echo .=  '<a  OnMouseOver="changeTip(\'View items in your backpack.\');" onMouseOut="changeTip();" href="javascript:popItemStats('.$backpack["number"].')">'.ucwords($backpack["name"]) . "</a>, ";
	$string_to_echo{strlen($string_to_echo)-2} = " ";
	echo $string_to_echo."\n<br/>";
}//end echo_stats_equip



function echo_stats_stats()
{
	include "xptable.php";
	$_exptable = _the_exp_table();
?>
	<b>Race:</b> <?echo ucfirst($_SESSION["userinfo"]["race"]);?><br/>
	<b>Sex:</b> <?echo ucfirst($_SESSION["userinfo"]["sex"]);?><br/>
	<b OnMouseOver="changeTip('This is your player number, which is useful in error reports.');" onMouseOut="changeTip();"> P-Num:</b><?echo ucfirst($_SESSION["userinfo"]["number"]);?><br/>
	<b>Level:</b> <?echo ucfirst($_SESSION["userinfo"]["level"]); ?> : <?echo ucfirst($_SESSION["userinfo"]["exp"])." / " . $_exptable[$_SESSION["userinfo"]["level"]];?><br/>
	<b>HP:</b> <?echo $_SESSION["userinfo"]["hp"] . " / " . $_SESSION["userinfo"]["maxhp"];?><br/>
	<b>AC:</b> <?echo $_SESSION["AC"];?> <b>ToHit:</b> <?$_weapon_ = get_weapon_damage(); echo floor($_SESSION["userinfo"]["str"]/2-5)+$_weapon_["tohit"];?><br/>
   <b>Damage:</b> <?echo $_weapon_["damage"];?><br/>
	<form method=post action=# style="display:inline;">
		<b>Strength:</b> <?echo $_SESSION["userinfo"]["str"];if($_SESSION["userinfo"]["credits"] >= $_SESSION["userinfo"]["str"]) { ?> <button name="add_credit" value=str type=submit>+</button><?}?><br/>
		<b>Dexterity:</b> <?echo $_SESSION["userinfo"]["dex"];if($_SESSION["userinfo"]["credits"] >= $_SESSION["userinfo"]["dex"]) { ?> <button name="add_credit" value=dex type=submit>+</button><?}?><br/>
		<b>Constitution:</b> <?echo $_SESSION["userinfo"]["con"];if($_SESSION["userinfo"]["credits"] >= $_SESSION["userinfo"]["con"]) { ?> <button name="add_credit" value=con type=submit>+</button><?}?><br/>
		<b>Wisdom:</b> <?echo $_SESSION["userinfo"]["wis"];if($_SESSION["userinfo"]["credits"] >= $_SESSION["userinfo"]["wis"]) { ?> <button name="add_credit" value=wis type=submit>+</button><?}?><br/>
		<b>Intelligence:</b> <?echo $_SESSION["userinfo"]["intel"];if($_SESSION["userinfo"]["credits"] >= $_SESSION["userinfo"]["intel"]) { ?> <button name="add_credit" value=intel type=submit>+</button><?}?><br/>
		<b>Stat Credits:</b> <?echo $_SESSION["userinfo"]["credits"];?><br/>
	</form>
<?
}//end echo_stats_stats()

function echo_stats_Settings()
{
?>

	<form name=statsDisp method=POST action=#>
		Stats Pane Display:
		<br/>
		<input type=radio name=statsDisp value=menu>Menu
		<input type=radio name=statsDisp value=list>List
		<br/>
		<input onMouseOver="changeTip('Submit your stats display choice to the database.');" onMouseOut="changeTip();" type=submit value="Submit Stats Display">
	</form>
	<br/>


	Background:
	<form class=statsDisp name=BGSetting method=POST  action=#>
		<select name="BGSetting" onChange="backgroundIMG.src = form.BGSetting.options[form.BGSetting.options.selectedIndex].value;">
			<option value="images/black.png">Black
			<option value="images/black_squiggle.png">Black Squiggles
			<option value="images/yellow_globe.png">Yellow Globe
			<option value="images/red_globe.png">Red Globe
		</select>
		<input onMouseOver="changeTip('Submit your background choice to the database.');" onMouseOut="changeTip();" type=submit value="Submit Background">
	</form>
	<br/>
	<form name=red method=POST action=#>

		Font Color<br/>
		Red:
		<select name="setFontColorR" onChange="document.body.style.color = '#'+form.setFontColorR.options[form.setFontColorR.options.selectedIndex].value + form.setFontColorG.options[form.setFontColorG.options.selectedIndex].value +form.setFontColorB.options[form.setFontColorB.options.selectedIndex].value;">

			<? printColorOptions("red"); ?>
		</select>
		<br/>
		Green:
		<select name="setFontColorG" onChange="document.body.style.color = '#'+form.setFontColorR.options[form.setFontColorR.options.selectedIndex].value + form.setFontColorG.options[form.setFontColorG.options.selectedIndex].value +form.setFontColorB.options[form.setFontColorB.options.selectedIndex].value;">
			<? printColorOptions("green"); ?>
		</select>
		<br/>
		Blue:
		<select name="setFontColorB" onChange="document.body.style.color = '#'+form.setFontColorR.options[form.setFontColorR.options.selectedIndex].value + form.setFontColorG.options[form.setFontColorG.options.selectedIndex].value +form.setFontColorB.options[form.setFontColorB.options.selectedIndex].value;">
			<? printColorOptions("blue"); ?>
		</select>
		<br/>
		<input onMouseOver="changeTip('Submit your font color choice to the database.');" onMouseOut="changeTip();" type=submit value="Submit Color">

	</form>
	<br/>
	<form name=StatsDisp method=POST action=#>
		Encounter rate:<select name="set_difficulty">
			<option value=5>Rarely 16%
			<option value=4>Occasionally 20%
			<option value=3>Sometimes 25%
			<option value=2>Often 33%
			<option value=1>Alot 50%
			<option value=0>Always 100%
		</select>
		<input onMouseOver="changeTip('Submit your difficulty choice to the database.');" onMouseOut="changeTip();" type=submit value="Submit Difficulty">
	</form>

<?
}//end echo_stats_settings()


function printColorOptions($whichColor)
{
	$_Colors = array("1","2","3","4","5","6","7","8","9","A","B","C","D","E","F");
	$_CurrentColor = $_SESSION["userinfo"]["fontcolor"];
	if($whichColor == "red")
		@$_char = $_CurrentColor{0};
	elseif($whichColor == "green")
		@$_char = $_CurrentColor{1};
	elseif($whichColor == "blue")
		@$_char = $_CurrentColor{2};
	else
		$_char = "Z";

	foreach($_Colors as $_color)
	{
		echo "<option ";
		if($_char == $_color)
			echo " SELECTED ";
		echo "value=$_color>$_color\n";
	}


}//end printColorOptions()
?>
</body>