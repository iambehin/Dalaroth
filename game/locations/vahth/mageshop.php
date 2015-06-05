Welcome to the mageshop!
<br>

<!--Debug:
Post: <pre><? print_r($_POST);?></pre>
<br><br>Session:<pre> <? print_r($_SESSION);?> </pre>
-->

<?
include "spells.php";


function learnspell($learn)
{
global $spells;
global $spells_known;



if($_SESSION["userinfo"]["gold"] < pow(7,$learn))
die("ERROR, You don't have that much gold!");
//used die because the radio button should not have appeared if usergold<cost

if(in_array($learn, $spells_known))
echo "You already know ".$spells[$learn]."!\n<br>\n";
else
{
echo "Thanks for choosing to learn ".$spells[$learn]."\n<br>\n";

if($spells_known[0]==NULL)
unset($spells_known[0]);

$spells_known[]=$learn;
$_SESSION["userinfo"]["gold"]-=pow(7,$learn);
$_SESSION["userinfo"]["spells"] = implode("|",$spells_known);
update_db("spells");
update_db("gold");
}
} //END learnspell()



$train=$_POST["train"];
$class=$_SESSION["userinfo"]["class"];
if($train == "yes")
{
$_SESSION["userinfo"]["class"]="mage";
$class="mage";
update_db("class");
echo "Welcome to our ranks then, mage.";
}
elseif($class !== "mage")
echo "<br>You seem to be a ".$class."<br>";


if(is_numeric($train))
learnspell($train);

?>
<br><img height=200 width=200 src="/game/images/Vahth/magehat.png"><br>
<?
if($class == "fighter")
echo "Would you like to train in the ways of the mage?<br><form method=POST action=#>
<br><input type=hidden name=train value=yes>
<button type=submit>I would like to become a mage!</button>
</form>";
elseif($class=="mage")
{
echo "Hello, fellow Mage, these are the spells which you currently know:<br>";
if($spells_known[0]==NULL&&$spells_known[1]==NULL)
echo "NONE<br>\n";
else foreach ($spells_known as $spell_known)
echo "<span class='arcane'>".$spells[$spell_known]."</span> ";
?>
<form method=POST action=#>
<br>
Which spell would you learn?

<!-- Spell Learning Table-->

<table>
<tr><td>Spell Name</td><td>Price</td><td>Learn it?</td></tr>

<?
foreach($spells as $spell_num=>$spell)
{
if($spell_num==null)
continue;
$spell_price=pow(7,$spell_num);
echo "<tr><td class=green>$spell</td><td class=yellow>$spell_price</td><td>";
if($spell_price < $_SESSION["userinfo"]["gold"])
echo "<input type=radio name=train value=$spell_num>";
echo "</tr>";
}
?>
</table>


<button type=submit>Learn!</button>
</form>
<?
}//end if(mage)
else
echo "I can only offer training to fighters or mages.";



?>