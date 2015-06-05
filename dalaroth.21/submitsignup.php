<?php


//Yay functions
function is_email_in_correct_format($supplied)
{
$supplied_stuff = explode("@",$supplied);
$more_supplied_stuff = explode(".",array_pop($supplied_stuff));
$supplied_stuff = array_merge($supplied_stuff,$more_supplied_stuff);
if(count($supplied_stuff) == 3)
return true;
else
return false;

}

//behin wrote this:
function are_there_unwanted_chars($stuff, $allowedchars)
{ // allowedchars are the chars you want to allow, includes the uppercase form too.
$alpha = str_split($allowedchars); //Split the chars into an array.
$stuff = strtolower($stuff); //Make it Lowercase.
$newstuff = $stuff; //Make new var with old stuff
foreach($alpha as $item) //stick each item in the array into $item for the loop
$newstuff = str_replace($item, "", $newstuff); //remove $item from the new stuff

 //Now, every item which was in $allowedchars, is now gone from new stuff.

if(strlen($newstuff) > 0) //If there's anything left
return true; //then there are unwanted chars
else //if not
return false; //then there aren't
}

//variables

global $signup;
$signup=true;
global $signup_fail_message;
$signup_fail_message="<div align=left>Signup failed, because:\n<ol>\n";

//put supplied stuff in variables.
if(strlen($_POST['pword']) > 0)
$pword=crypt($_POST['pword'], "behin");//One way encryption, with behin as the salt.
else
{
$signup = false;
$signup_fail_message.="<li>The password wasn't properly sized, or not sent.  It must be 4+ characters.\n";
}
if(strlen($_POST['uname']) > 3)
$uname=strtolower($_POST['uname']);//make it lowercase
else
{
$signup = false;
$signup_fail_message.="<li>There username wasn't properly sized or not sent. It must be 3+ characters.\n";
}
if(strlen($_POST['email']) > 3)
$email=$_POST['email'];

else
{
$signup = false;
$signup_fail_message.="<li>The email wasn't properly sized or not sent. It must be 3+ characters.\n";
}
$race=@$_POST['race'];
$sex=@$_POST['sex'];

//Now we have variables, lets test em.
if(are_there_unwanted_chars(@$uname,"abcdefghijklmnopqrstuvwxyz1234567890"))
{
$signup = false;
$signup_fail_message.="<li>Supplied username contained an invalid character.\n";
}

//Password was encrypted already, so we can't do much with it, not that we'd want to.

if(@are_there_unwanted_chars($email, "abcdefghijklmnopqrstuvwxyz1234567890@."))
{
$signup = false;
$signup_fail_message.="Supplied email contained an invalid character.\n";
}

if(!@is_email_in_correct_format($email))
{
$signup = false;
$signup_fail_message.="<li>Supplied email is incorrectly formated.";
}


include "mysql.php";
$result = mysql_query("SELECT * FROM players WHERE email='".@$email."'",$db) or die(mysql_error());
$db_email = @mysql_result($result, 0, "email");//the @ suppresses error messages
$result = mysql_query("SELECT * FROM players WHERE name='".@$uname."'",$db) or die(mysql_error());
$db_uname = @mysql_result($result, 0, "name");

if(strlen($db_email) > 3)
{
$signup = false;
$signup_fail_message.="<li>Supplied Email is in use.\n";
}
if(strlen($db_uname) > 3)
{
$signup = false;
$signup_fail_message.="<li>Supplied Username is in use.\n";
}

if($signup)
{
$result = mysql_query("INSERT INTO players(name, password, email,sex,race) VALUES ('$uname', '$pword','$email','$sex','$race')") or die(mysql_error());
echo "Signup succeeded!<br/>\n<a href=index.php>Sign in to play.</a>\n<br/><br/>";
}
else
{
echo $signup_fail_message."\n</ol>\n".
"</div>\n";



include "signup.php";
}


mysql_close();





?>
