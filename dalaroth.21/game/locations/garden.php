<?php



$hp_amount = $_SESSION["userinfo"]["con"]*4;
$mp_amount = $_SESSION["userinfo"]["wis"]*4;


$_SESSION["userinfo"]["hp"]+=$hp_amount;
$_SESSION["userinfo"]["mp"]+=$mp_amount;
echo "<br/>\nYour hp is restored by $hp_amount\nYour mp is restored by $mp_amount.";

?>
<br/>
<br/>
<a href="./">Restore again.</a>
