This is where you can sign up for Dalaroth.
<br/>
<form action=# method=post>
<input type=hidden name=page value="submitsignup.php">
<table class=signup>
<tr><td>Username:<input name=uname value=<?echo @$_POST["uname"];?>></td></tr>
<tr><td>Password:<input type=password name=pword></td></tr>
<tr><td>Email: <input type=text name=email value=<?echo "'".@$_POST["email"]."'";?>></td></tr>
<tr><td>Sex: <input type=radio name=sex value=male checked=<?
if(@$_POST["sex"] === "male")
echo "true";
else
echo "false";
?>>Male<input type=radio name=sex value=female checked=<?
if(@$_POST["sex"] === "female")
echo "true";
else
echo "false";
?>>Female</td></tr>
<tr><td>Race: <select name=race><option value=human>Human</select></td></tr>
<tr><td>Class: <select name=class><option value=fighter>Fighter</select></td></tr>
<tr><td><input type=submit value="Sign Up"></td></tr>
</table>




</form>