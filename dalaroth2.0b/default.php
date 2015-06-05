<span class=warning>WARNING!:</span>Current version of Dalaroth is only compatible with Mozilla Firefox version 1.0+
<br/><br/><br/>
<script type="text/javascript" >
<!--
function signup()
{
  document.signup.submit();
}
-->
</script>
<?php
if(isset($_SESSION["uname"]))
{
?>
Heya <?php echo ucfirst($_SESSION["uname"]);?>, welcome!
<br/>
<a href="./game/index.lzx.swf">I wish to play!</a>
<br/>
<form method=post name=signout action="index.php" style="display:inline">
<input type=hidden name=page value="signout.php">
<input type=hidden name=signout value="y">
</form>
<a href="javascript:document.signout.submit()">Sign me out!</a>
<br/>
<br/>
<br/>
<?php
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
<form name=signup action=# method=post>
<input type=hidden name=page value=signup.php>
<a href='javascript:signup()'>Sign Up</a></form>
<?php
}
?>

<iframe src=version.html width=90% height=50%>You need frames.</iframe>