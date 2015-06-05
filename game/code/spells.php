<?
/** Spells: Price will be X^(spell level) : currently x=7 **/
global $spells;
global $spells_known;
$spells_known = explode("|",$_SESSION["userinfo"]["spells"]);
$spells=array("ERROR","Magic Missile",
"Mage Shield","Time Warp","Ice Blast","Fulminating Explosion","Summon");
?>