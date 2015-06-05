This is the Mayor's office!
<img height="200" width="100" src="/game/images/Vahth/mayor.png">

<br>
<br>
<?

if($_SESSION["userinfo"]["vahth_mayor"] =="0")
{

if($_POST["mayor_quest"] === "yes")
{
$_SESSION["userinfo"]["vahth_mayor"]=".5";
echo "Thank you. \n<br>";
}
elseif($_POST["mayor_quest"]=== "no")
{
?>
"My thanks for thy time, nonetheless. "
<br/>
<?
}
else
{
?>
A kind-looking older gentleman approaches you.
<br>
"Hail Adventurer, I be right well pleased to meetest thou.  
Verily I be mayor of yon fine village, and also thrilled for thine grace, hero!"

<br>
He peers into the distance.
<br>
"Alas, most shamed are we for thou to see us in this pitiful state.
Wherefore knoweth we not, yet whitherto are we affronted by an unfortunate unnatural affliction.
Our abood be most under siege!
<br>
"We bid thy aide! Prithee, wouldst thou venture forth and slay over 50 of the beasts plaguing this realm?"


<br><br>
<form action=# method=POST>
<button name="mayor_quest" value='yes'>Yes, I'll be glad to.</button>
<button name="mayor_quest" value='no'>No, this is not my problem</button>
</form>


<?
}


}//end if($_SESSION["userinfo"]["vahth_mayor"] =="0")
elseif ($_SESSION["userinfo"]["vahth_mayor"] == ".5")
{
echo "Thou hast yet to slay a beast, my champion, please return whenst thou hast.";

}
elseif($_SESSION["userinfo"]["vahth_mayor"] > 50)
{
echo "My thanks, champion, thou hast proven thy worth.";


$_SESSION["userinfo"]["exp"] += 1000;
update_db("exp");
$_SESSION["userinfo"]["credits"] += 12;
update_db("credits");
?>

<br/>
<br/>
You have recieved 1000 exp, and 12 stat credits!

<br/>
<br/>

<?
levelup();
$_SESSION["userinfo"]["vahth_mayor"]=0;
}
else
echo "Thou hast only slain ".$_SESSION["userinfo"]["vahth_mayor"]." monsters.  Prithee, return after thou hast slain 50.\n<br>";


update_db("vahth_mayor");

?>


<br/><br/><br/>