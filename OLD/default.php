
<script language="JavaScript" type="text/javascript">
<!--
function signup()
{
  document.signup.submit();
}
-->
</script>
<?
global $uname;
if(strlen($uname) > 0)
{
?>
Heya <?echo$uname;?>, welcome!
<form action=./game/index.php method=post>
<input type=hidden name=username value=<?echo $uname;?>>
<input type=submit value='Enter the Game'>
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
Username: <input type=text name=username>
Password: <input type=password name=password>
<input type=submit value='Sign in'></form>
<br/>
<form name=signup method=post>
<input type=hidden name=page value=signup.php>
<a href='javascript:signup()'>Sign Up</a></form>
<?
}
?>