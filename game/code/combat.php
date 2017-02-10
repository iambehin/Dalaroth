<!-- Begin File: combat.php -->
<embed src="bgmusic.mp3" autostart="true" loop="true"
width="400" height="100">
</embed>
<?

include "spells.php";
global $spells_known;
docombat();

global $combat;
$_SESSION["userinfo"]["combat"] = implode("|",$combat);
update_db("combat");
unset($combat);


function treasure($_cr)
{
$_cr*=10;
	echo "Your treasure roll: ";
	$goldroll = roll("1d100",true);

   if($goldroll < 5)
		return roll("1d$_cr",true);
   elseif($goldroll < 25)
		return roll("3d$_cr",true);
   elseif($goldroll < 50)
		return roll("5d$_cr",true);
   elseif($goldroll < 90)
		return roll("7d$_cr",true);
   elseif($goldroll < 101)
		return roll("10d$_cr",true);
   else
   	return "hate";

}//end treasure()


function encounter()
{
	global $db;
	include "../../mysql.php";//mysql data, stores it in $db
	if(isset($_POST["set_difficulty"])&& $_POST["set_difficulty"] 
		<= 5 && $_POST["set_difficulty"] >= 0)
	{
		$_SESSION["userinfo"]["difficulty"] = $_POST["set_difficulty"];
		update_db("difficulty");
	}

						 	

	$combat_result = @mysql_query(
                  "SELECT * FROM monsters WHERE x_max > "
                  .$_SESSION["userinfo"]["x"]
                  ." AND y_max > ".$_SESSION["userinfo"]["y"]." AND x_min < "
                  .$_SESSION["userinfo"]["x"]." AND y_min < "
                  .$_SESSION["userinfo"]["y"]) or die(mysql_error());
	$combat_monsters = array();
	$num_mobs = $_SESSION["userinfo"]["difficulty"]+0;
	while($num_mobs>0)
	{
		$combat_monsters[] = "-1";
		$num_mobs--;
	}


// if a non array is selected, then no encounter.  Thus controlling encounter rate


	for($combat_pointer = 0;$combat_pointer 
				< mysql_num_rows($combat_result);$combat_pointer++)
		array_push($combat_monsters, mysql_fetch_assoc($combat_result));
	$combat_monster = $combat_monsters[mt_rand(0, count($combat_monsters)-1)];
	global $combat;
	if(is_array($combat_monster))
		$combat[0]="yes";
	return $combat_monster;
}//end encounter





function docombat()
{
	global $db;
	global $display_Travel;
	global $combat;

//This tells CenterPane not to display the "You may travel using blah blah" message,
// since we will if its applicable.
	$display_Travel = false;

	$combat = explode("|",$_SESSION["userinfo"]["combat"]);
	if(isset($_POST["combat"])) 
		$combat[0] = $_POST["combat"];

	if(count($combat) > 1 && $combat[0] !== "no" && strlen($combat[0])>0)
	{
		$combat_monster = mysql_fetch_assoc(mysql_query(
				"SELECT * FROM monsters WHERE number='"
				. $combat[1] . "'",$db));
		$combat_monster["hp"]=$combat[2];
	}
	else
		$combat_monster = encounter();
	if(is_array($combat_monster)) //check if a monster was encountered
		echo do_encounter($combat_monster);
	else //if no encountered monster
	{
		echo "You hear the sounds of monsters, but nothing attacks you.<br>\n";
		echo "You may travel using the travel buttons on the right.\n<br>";
	}


}//end docombat()



function print_attack_form($combat_monster)
{
echo "<form method=post action=#>";
	global $can_I_Travel;
	$can_I_Travel = false;
	global $spells_known;
	global $spells;
if($spells_known[0]!==NULL)
{
	foreach ($spells_known as $spell_known)
echo "<button type=submit class=arcane name=combat value=$spell_known>".$spells[$spell_known]."</button> ";

}
	echo "<br>Or you can".
	"<button type=submit name=combat value=Attack>Attack</button>\n".
	"</form>\n".
	"You may not flee this beast though.\n<br>\n";

}//end print_attack_form()


function monster_turn($combat_monster)
{
	include "spells.php";
	if($combat_monster["hp"] < 1)//interupts if dead monster
	{
		$_SESSION["userinfo"]["combat"] = "Victory";
		$combat_monster=victory($combat_monster);
		return $combat_monster;
	}
	global $combat;
	$monster_attackroll = mt_rand(0,20)+$combat_monster["tohit"];
	if($monster_attackroll < $_SESSION["AC"])//if monster misses
	{
		$monster_hit=false;
		$monster_damage_done = 0;
		echo "<span class=yellow>The ".$combat_monster["name"]
				." failed to hit you.</span>\n<br>";
	}
	else //monster hit
	{
		$monster_hit=true;
		$monster_damage_done = roll($combat_monster["damage"],false);
		echo "<span class=redMild>".$combat_monster["name"]." ".$combat_monster["attack"]
		." for $monster_damage_done damage.</span>\n<br>";
		
		if($combat[0]==2)//Mage Shield
		{
			$monster_damage_done*=.75;
			 $monster_damage_done=floor($monster_damage_done);
			echo "<span class=yellow>Your Mage Shield reduces 25% of the damage dealt, to $monster_damage_done.</span><br>\n";
		}
		$_SESSION["userinfo"]["hp"] -=$monster_damage_done;
		update_db("hp");
	}
	$traveling = "combat";
	if($_SESSION["userinfo"]["hp"] < 1)
	{
		$_SESSION["userinfo"]["hp"] = 1;
		update_db("hp");
		$_SESSION["userinfo"]["x"] = 0;
		$_SESSION["userinfo"]["y"] = 0;
		update_db("location");
		$_SESSION["userinfo"]["travelable"] = " ";
		$combat[0]="no";
		echo "<div class=exclaim>You have died
				</div>\n<br>Bad things happen!\n
				<br>You've been teleported to the vahth plaza.
				  Please reset your coordinates.\n<br><br><br>";
		return 5;
	}

}//end monster_turn()

function player_turn($combat_monster)
{
	global $combat;
	if($_SESSION["userinfo"]["combat"] === "Victory")
	{
		return $combat_monster;
	}
	$weapondamage=get_weapon_damage();
	$attackroll = roll("1d20",true);
	if($attackroll >= $weapondamage["critrange"])
		$_crit=true;
	else
		$_crit=false;
	$attackroll+=floor($_SESSION["userinfo"]["str"]/2 - 5);
	if($attackroll < $combat_monster["AC"])
		echo "<span class=yellow>
				Your Modified Attack Roll of $attackroll failed to hit the "
		.$combat_monster["name"].".</span><br>\n";
	else
	{
		echo "<span class=green>
				Your Modified Attack Roll of $attackroll succeeded to hit the "
		.$combat_monster["name"].".</span><br>\n";
		$damage_done = roll($weapondamage["damage"],true) 
				+ floor($_SESSION["userinfo"]["str"]/2-5);
		if($_crit)
		{
			echo "<span class=green>You made a critical hit!</span><br>\n";
			$damage_done*=$weapondamage["crit"];
		}
		echo "<span class=green>You deal ".$combat_monster['name']
				." $damage_done damage.</span><br>\n";
		$combat_monster["hp"]-=$damage_done;
		$combat[2] = $combat_monster["hp"];
	}
	if($combat_monster["hp"] > 0)
		echo "The Monster has " . $combat_monster["hp"] . " health.\n<br><br>";
	else
		echo "You did " . -$combat_monster["hp"] . " more damage than " . $combat_monster["name"] . " had health left.\n<br><br>";

	if($combat_monster["hp"] < 1)
		$_SESSION["userinfo"]["combat"] = "Victory";		
 
	if($_SESSION["userinfo"]["combat"] === "Victory")
		$combat_monster=victory($combat_monster);
	else
		print_attack_form($combat_monster);

   return $combat_monster;
}//end player_turn()

function victory($combat_monster)
{
	global $combat;
    $combat[0] = "no";
	echo "You defeat the ".$combat_monster["name"]."!\n<br>";
	$_exp = $combat_monster["CR"]*50*($combat_monster["CR"] / $_SESSION["userinfo"]["level"]);
	$expgain = mt_rand($_exp/5,$_exp);
	$_SESSION["userinfo"]["exp"]+=$expgain;
	if(!levelup())
		update_db("exp");
	$_exptable = _the_exp_table();
	echo "You gain $expgain exp.\n<br> <span class=notice>Level ".$_SESSION["userinfo"]["level"]."</span> : "
.ucfirst($_SESSION["userinfo"]["exp"])." / " . $_exptable[$_SESSION["userinfo"]["level"]]."<br><div class=emphasis>Treasure:</div>\n";
	$goldgain = treasure($combat_monster["CR"]);
	echo "You find $goldgain gold pieces.\n<br>";
	$_SESSION["userinfo"]["gold"]+=$goldgain;
	update_db("gold");
	echo "You now have ".$_SESSION["userinfo"]["gold"]." gold.<br>\n";
	
	//Vahth Mayor Quest
       if($_SESSION["userinfo"]["vahth_mayor"] == ".5")
		$_SESSION["userinfo"]["vahth_mayor"]="1";
       elseif($_SESSION["userinfo"]["vahth_mayor"] > ".5")
		$_SESSION["userinfo"]["vahth_mayor"]++;
	if($_SESSION["userinfo"]["vahth_mayor"] !==0)
		echo "You've slain another monster for the mayor for a total of ".$_SESSION["userinfo"]["vahth_mayor"]." now!<br>\n";
    update_db("vahth_mayor");
	echo "You may travel.\n<br>";
	return $combat_monster;
}

function do_encounter($combat_monster)
{
           include "spells.php";
           global $combat;
	$combat[1]=$combat_monster["number"];
	$combat[2]=$combat_monster["hp"];

	
	if($combat[0] == "Attack")
	{
           $turn1=monster_turn($combat_monster);
           if($turn1 == 5)
           return null;
           $combat_monster=player_turn($combat_monster);
	}
	elseif($combat[0] == 1)//magic missile
	{
		if($_SESSION["userinfo"]["mp"]<1)
			echo "You do not have the mana for that!<br>\n";
		else
		{
           echo "<span class=arcane>You spend 1 mana to cast ".$spells[1]." at ".$combat_monster["name"]." for 10 damage!</span>\n<br>";
		   $_SESSION["userinfo"]["mp"]-=1;
		   update_db("mp");
           $combat_monster["hp"]-=10;
		   $combat[2] = $combat_monster["hp"]; 
           echo "The Monster has " . $combat_monster["hp"] . " health remaining.\n<br>";
		}
           $turn1=monster_turn($combat_monster);
           if($turn1 == 5)
           return null;
           $combat_monster=player_turn($combat_monster);		
	}
	elseif($combat[0] == 5)
	{
		if($_SESSION["userinfo"]["mp"]<100)
			echo "You do not have the mana for that!<br>\n";
		else
		{
           echo "<span class=arcane>You spend 100 mana to cast ".$spells[5]." at ".$combat_monster["name"]." for 10000 damage!</span>\n<br>";
		   $_SESSION["userinfo"]["mp"]-=100;
		   update_db("mp");
           $combat_monster["hp"]-=10000;
		   $combat[2] = $combat_monster["hp"]; 
           echo "The Monster has " . $combat_monster["hp"] . " health remaining.\n<br>";
		}
           $turn1=monster_turn($combat_monster);
           if($turn1 == 5)
           return null;
           $combat_monster=player_turn($combat_monster);
	} 
	elseif($combat[0] == 2)//Mage Shield
	{
		if($_SESSION["userinfo"]["mp"]<25)
			echo "You do not have the mana for that!<br>\n";
		else
		{
           echo "<span class=arcane>You spend 25 mana to cast ".$spells[2]."</span>\n<br>";
		   $_SESSION["userinfo"]["mp"]-=25;
		   update_db("mp");
      
		}
           $turn1=monster_turn($combat_monster);
           if($turn1 == 5)
           return null;
           $combat_monster=player_turn($combat_monster);		
	}
	else//no spells
	{
		$combat[0] == "yes";

		echo $combat_monster["name"] . " attacks!\n<br><img src='../"
				.$combat_monster["image"]."' alt='' />\n<br>";
		print_attack_form($combat_monster);
	}
   

}//end do_encounter()


?>
<!-- End File: combat.php-->