Thiis is a Battle Shop ran by Vrell.<br/>



<?


if(isset($_POST["buy_armor"]) || isset($_POST["buy_weapon"]))
echo purchase_();
elseif(isset($_POST["sell_item"]))
echo sell_();
echo "\n<br/><br/><br/>\n";
echo list_();



function list_()
{
global $db;

$_shop_weapons=array();
$_shop_armors=array();
$_sellables=array();

$armor_shop_result = mysql_query("SELECT * FROM items WHERE type = 'armor' ORDER BY price",$db);

$weapon_shop_result = mysql_query("SELECT * FROM items WHERE type = 'weapon' ORDER BY price",$db);


for($_var = 0;$_var < mysql_num_rows($armor_shop_result);$_var++)
array_push($_shop_armors, mysql_fetch_assoc($armor_shop_result));

for($_var = 0;$_var < mysql_num_rows($weapon_shop_result);$_var++)
array_push($_shop_weapons, mysql_fetch_assoc($weapon_shop_result));


$_bp = explode(",",$_SESSION["userinfo"]["backpack"]);
array_pop($_bp);
foreach($_bp as $_sellable)
{
array_push($_sellables,mysql_fetch_assoc(mysql_query("SELECT * FROM items WHERE number = '".$_sellable."'")));
}

?>
You approach the counter where Vrell awaits.
<br/><br/>
<form method=POST action=#>
Vrell: "Here are the armors I carry."
<select name="buy_armor">
<?
foreach($_shop_armors as $armor)
echo "<option value='".$armor["number"]."'>".$armor["name"]." - ".ltrim($armor["price"],0)."g\n";
?>
</select>
<button type=submit>Purchase</button>
</form>
<form method=POST action=#>
<br/>
Vrell: "Here are the weapons I carry."
<select name="buy_weapon">
<?
foreach($_shop_weapons as $weapon)
echo "<option value='".$weapon["number"]."'>".$weapon["name"]." - ".ltrim($weapon["price"],0)."g\n";
?>
</select>
<button type=submit>Purchase</button>
</form>

<form method=POST action=#>
<br/>
Vrell: "Here is what you have which I'll buy."
<select name="sell_item">
<?




foreach($_sellables as $_sellable)
{
if($_sellable["type"] == "armor" || $_sellable["type"] == "weapon")
echo "<option value='".$_sellable["number"]."'>".$_sellable["name"]." - ".floor($_sellable["price"]*.75)."g\n";
}
?>
</select>
<button type=submit>Sell</button>
</form>
<?

}//end list_armor()

function purchase_()
{
global $db;

if(isset($_POST["buy_armor"]))
$_request = mysql_fetch_assoc(mysql_query("SELECT * FROM items WHERE number = '".$_POST["buy_armor"]."' AND type='armor' LIMIT 1",$db));
elseif(isset($_POST["buy_weapon"]))
$_request = mysql_fetch_assoc(mysql_query("SELECT * FROM items WHERE number = '".$_POST["buy_weapon"]."' AND type='weapon' LIMIT 1",$db));

if($_request["price"] > $_SESSION["userinfo"]["gold"])//if the price is  lower than the user's gold.
return "You don't have the money for that.\n<br/>";//stop function and yell at user.


echo "You have purchased ".$_request["name"]." for ".ltrim($_request["price"],0)." gold, you can pick it up on your way out.\n<br/>";
$_SESSION["userinfo"]["backpack"] .= $_request["number"].",";
update_db("backpack");
$_SESSION["userinfo"]["gold"] -= $_request["price"];
update_db("gold");


}//end purchase_armor()


function sell_()
{
global $db;

if(isset($_POST["sell_item"]))
$_request = mysql_fetch_assoc(mysql_query("SELECT * FROM items WHERE number = '".$_POST["sell_item"]."'  LIMIT 1",$db));
$_bp = explode(",",$_SESSION["userinfo"]["backpack"]);



unset($_bp[array_search($_request["number"],$_bp)]);//searches the array for the first value matching the sell request, and removes it.

$_SESSION["userinfo"]["backpack"] = implode(",",$_bp);
update_db("backpack");


$_SESSION["userinfo"]["gold"] += floor($_request["price"]*.75);
update_db("gold");

echo "You have sold ".$_request["name"]." for ".floor($_request["price"]*.75)." gold.\n<br/>";

}//end sell_()
?>
<br/>
