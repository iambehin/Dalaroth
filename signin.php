
<?


global $dbpassword;
if($dbpassword === crypt($_POST['password'], "behin"))
{

?> 
You have successfully signed in as <?echo $_SESSION['uname'];?>, welcome!
<br/>
<a href="./game/index.php">Enter here.</a>
<br/>
<form method=post name=signout action="index.php" style="display:inline;">
<input type=hidden name=page value="signout.php">
<input type=hidden name=signout value=y>
</form>
<a href="javascript:document.signout.submit()">Signout</a>
<?
}
else
echo "Your password could not be verified.<a href= './'>Try again</a>";

?>


</body>
</html>
