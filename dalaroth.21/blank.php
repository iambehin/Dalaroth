hi

<?php
/**************************************************************************
*index.php - Main Page for Dalaroth
* Dalaroth is a browser based text RPG developed by Behin aka Ben Davis
* This file is the main index for the game.
* This file handles inclusion of pages as per user requests.
* These files make a variety of functions available to the user.
* Functions such as signup, signout, signin, etc.
**************************************************************************/



/****************************
*Begin Function Declaration.
****************************/

//I really like this function, but it's only available in php5, so this came from here: http://us3.php.net/manual/en/function.str-split.php
if (!function_exists("str_split")) {
  function str_split($str,$length = 1) {
   if ($length < 1) return false;
   $strlen = strlen($str);
   $ret = array();
   for ($i = 0; $i < $strlen; $i += $length)
     $ret[] = substr($str,$i,$length);
   return $ret;
  }//end str_split()
}



function signout()
{
global $page;
unset($_SESSION["uname"]);
$page="signout.php";
}//end signout()
/*
function cfh_banner()
{
$__banner__="/";
while(!file_exists($__banner__."banner.php")||strlen($__banner__) > 100)
$__banner__ .= "../";

if(file_exists($__banner__."banner.php"))
include $__banner__."banner.php";
else
return "Can't find banner code";
}//end cfh_banner()
*/
function signin()
{
global $page;
if(isset($_POST['page']))
$page=$_POST['page'];
else
$page="default.php";


if(isset($_POST['username']))
{
global $dbpassword; 
@$password = ($_POST['password']);


include "mysql.php";//this file has mysql config options, stores the connection in $db


$result = mysql_query("SELECT * FROM players WHERE name='".$_POST['username']."'",$db);
$dbpassword = @mysql_result($result, 0, "password") or die(mysql_error());
mysql_close();
if($dbpassword === crypt($password, "behin"))
{
$_SESSION["uname"]=$_POST["username"];
$_SESSION["dbpassword"]=$dbpassword;
}

}
}//end signin(





?>