<?
/****************************************************************
*   This is the main index for Dalaroth.  Written by Behin.
*	TODO: Hooking
****************************************************************/


//Function which loads info for the game to access
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


/****************************************************************
*    End of initial functions
*    Begin body
****************************************************************/
header('Content-type: text/html; charset=utf-8');

?>
<!DOCTYPE html>
<Html>
	<head>
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
		<title>
			Dalaroth 2.0
		</title>
		<link media="screen, projection" href="style_05.css" rel="stylesheet" title="Default" type="text/css" />
		<link rel="icon" href="./images/dalaroth.ico" >
	</head>
	<body>
	Hello welcome to Dalaroth 2.0
	
	<? echo $_SESSION["userinfo"]["x"];?>
	</body>
</html>