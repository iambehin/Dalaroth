This is where you can sign up for Dalaroth.
<br/>
<form action=# method=post>
<input type=hidden name=page value="submitsignup.php">
<table class=signup>
<tr><td>Username:<input name=uname value=<?php echo @$_POST["uname"];?>></td></tr>
<tr><td>Password:<input type=password name=pword></td></tr>
<tr><td>Email: <input type=text name=email value=<?php echo "'".@$_POST["email"]."'";?>></td></tr>
<tr><td>Sex: <input type=radio name=sex value=male checked=<?php
if(@$_POST["sex"] === "male")
echo "checked='checked'";
?>>Male<input type=radio name=sex value=female <?php
if(@$_POST["sex"] === "female")
echo "checked='checked'";
?>>Female</td></tr>
<tr><td>Race: <select name=race>
<option value="human">Human</option>
<option value="elfin">Elfin</option>
<option value="faery">Faery</option>
</select>


Class: <select name=class>
<option value="swordsman">SwordsMan</option>
<option value="lancer">Lancer</option>
<option value="archer">Archer</option>
<option value="rogue">Rogue</option>
<option value="blazemage">Blaze Mage</option>
<option value="lichmage">Lich Mage</option>
<option value="meleemage">Melee Mage</option>
<option value="exorcist">Exorcist</option>
</select></td></tr>
<tr><td><input type=submit value="Sign Up"></td></tr>
</table>




</form>
