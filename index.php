<?
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
}//end signin()



/****************************************************
*End function declaration.
*Begin function implementation and HTML output.
****************************************************/
session_start();

if(@$_POST['signout'] === "y")
signout();
else
signin();
?>



<!DOCTYPE HTML>

<html prefix="og: http://ogp.me/ns#">
<head>
	<link media="screen, projection" href="style.css" rel="stylesheet" title="Default" type="text/css" />
	<link rel="icon" href="./game/images/dalaroth.ico" >
	<title>Dalaroth - <? echo $page;?></title>

	<meta property="og:title" content="Dalaroth" />
	<meta property="og:type" content="video.other" />
	<meta property="og:url" content="http://dalaroth.awardspace.com/" />
	<meta property="og:image" content="http://dalaroth.awardspace.com/game/images/banner.png" />

	<link href="https://plus.google.com/106795922216194347031" rel="publisher" />

	<script type="text/javascript" async defer
	  src="https://apis.google.com/js/platform.js?publisherid=106795922216194347031">
	</script>

	<script src="https://apis.google.com/js/platform.js" async defer></script>
	

</head>
<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.6&appId=54940088208";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<center>
<!-- google ads: --><?include "banner.php";?><!-- end of google ads-->

<!-- google adsense scripts
<script type="text/javascript"><!--
google_ad_client = "pub-1398164775684416";
/* 728x90, created 3/17/08 */
google_ad_slot = "7619028599";
google_ad_width = 728;
google_ad_height = 90;
google_cpa_choice = ""; // on file
//-\->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
end of adsense scripts-->
<img src="./game/images/banner.png" alt="Welcome to Dalaroth">
<br/>
There is an experimental flash version of dalaroth <a href=dalaroth.21>here</a>.
<br/>
<?

if(file_exists($page))
include $page;
else
echo "You requested an invalid page: $page email the admin: admin@dalaroth.awardspace.com";

?>
<br>
<br>


<div class="fb-follow" data-href="https://www.facebook.com/DalarothGame" data-layout="standard" data-show-faces="true"></div>

<div class="g-plusone" data-annotation="inline" data-width="300"></div>

<a href="https://twitter.com/dalaroth" class="twitter-follow-button" data-show-count="false" data-size="large">Follow @dalaroth</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>


</center>


</body>
</html>
