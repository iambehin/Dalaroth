<! begin garden.php >
<?


$hp_amount = $_SESSION["userinfo"]["con"]*4;


restorehp($hp_amount);
restoremp(5);
echo "<br/>Your hp is restored by $hp_amount\n<br/>Your mp is restored by 5.";
update_db("hp");
update_db("mp");

?>
<br/>
<a style="color:green;" target="_top" href="../index.php"><!--The ../ is because this file is located in the code folder-->Restore again.</a>

<! end garden.php>