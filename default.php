<span class=warning>WARNING!:</span>Current version of Dalaroth may not be compatible with Internet Explorer
<br/><br/><br/>
<script type="text/javascript" >
<!--
function signup()
{
  document.signup.submit();
}
-->
</script>
<?
if(isset($_SESSION["uname"])&&$_SESSION["userinfo"]["name"]==$_SESSION["uname"])
{
?>
Heya <?echo$_SESSION["uname"];?>, welcome!
<form action="./game/index.php" method=post>
<input type=submit value="Enter the Game" >
</form>

<form method=post action=index.php>
<input type=hidden name=page value="signout.php">
<input type=hidden name=signout value=y>
<input type=submit value=Signout>
</form>

<?
}
else
{
?>
Sign in to enter.
<form method=post action=index.php>
<input type=hidden name=page value=signin.php>
Username: <input type=text name=username autofocus>
Password: <input type=password name=password>
<input type=submit value='Sign in'></form>
<br/>
<form name=signup action=# method=post>
<input type=hidden name=page value=signup.php>
<a href='javascript:signup()'>Sign Up</a></form>
<?
}
?>

<iframe src=version.html width=90% height=50%>You need frames.</iframe>