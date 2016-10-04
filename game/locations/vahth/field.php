You're at Vahth's Farm.
<br>
<br>
<?
if(!function_exists("travel_x"))
include "functions.php";

$bp = explode(",",$_SESSION["userinfo"]["backpack"]);
$quest = explode(":",$_SESSION["userinfo"]["ahlon_quest"]);
if(in_array("14",$bp))
{//if ahlon in bp
echo "Ahlon:\"This is my field, thank you for the escort, I shall deactivate the protective enchantment, and gather the crops while you stand guard.\"\n<br/>";

$quest[0]="0";
$quest[1]=3;
$_SESSION["userinfo"]["ahlon_quest"] = implode(":",$quest);
update_db("quest");

echo ".<br/>\n.<br/>\n.<br/>\n.<br/>\n.<br/>\n";
echo "After a fair amount of time, and minor skirmishes, Ahlon has finished gathering, and is prepared for you to return him to the town hall.\n<br/>";
if (($key = array_search('14', $bp)) !== false) 
    unset($bp[$key]); //removes ahlon from bp.
$_SESSION["userinfo"]["backpack"]=implode(",",$bp);
update_db("backpack");
}//end if ahlon in bp

elseif($quest[0]>0)//if this quest has been done.
	letsfarm();
else
	echo "You see a field lush with thriving crops, protected by an enchantment.\n<br/>\n";

 
 function letsfarm()
 {
	 
	 
	 if($_SESSION["userinfo"]["vahth_farm"]==0)
	 {
		 echo "You see the field which you helped Ahlon clear.\n<br/>\n Ahlon notices you looking and approaches you saying 'Hail and well met friend!  I can see you're interested in yon field, as thanks for thine assistance it's my pleasure to offer it to you, should you have any need of it!<br><br>\n";
		 echo "Ah yet it would seem thou hast no experience harnessing the magic of dirt to create crops.  Fear not brave adventurer, for I am here to help.  To begin with, I'll give you one plot and some potato seeds.";
		 $bp = explode(",",$_SESSION["userinfo"]["backpack"]);
		 $bp[]=17;
		 $bp[]=17;
		 $bp[]=17;
		 $_SESSION["userinfo"]["backpack"]=implode(",",$bp);
		update_db("backpack");
		$_SESSION["userinfo"]["vahth_farm"]=1;
		update_db("vahth_farm");
		
	 }
	 elseif($_SESSION["userinfo"]["vahth_farm"]==1)
	 echo "Welcome back!";
 }


?>
