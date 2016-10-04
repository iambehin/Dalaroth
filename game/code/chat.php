<!-- Begin File: chat.php-->
<form method=post action=#>

<input type=text style="width:65%;" name=chat><button type=submit value="Chat!">Chat</button></form>
<?
global $db;

if(!isset($_SESSION["uname"]))
	return "You're not signed in";


?>


<?
if(isset($_POST['chat']))
{
	$chat = htmlspecialchars($_POST['chat'], ENT_QUOTES);
	if(strlen($chat) > 0)
		$chat_result = @mysql_query("INSERT INTO chat(time,text,user) VALUES ('" . date('D, d M y G:i:s') . "','$chat','" . ucfirst($_SESSION["uname"]) . "')",$db) or die(mysql_error());
}
$chat_result = @mysql_query("SELECT * FROM chat ORDER BY number DESC",$db);
$chat_archive = array();
if(mysql_num_rows($chat_result) > 50)
	$chat_num_rows = 50;
else
	$chat_num_rows = mysql_num_rows($chat_result) or die(mysql_error());
for($chat_pointer = 0;$chat_pointer < $chat_num_rows;$chat_pointer++)
{
	$chats = @mysql_fetch_assoc($chat_result);
	array_push($chat_archive,$chats);
}

foreach($chat_archive as $chat_archive_portion)
{
	echo "<span class=grey>".$chat_archive_portion["time"] . "</span> ~ <span class=white>" . $chat_archive_portion["user"] . "</span>: " .  $chat_archive_portion["text"] . "<br/>\n";
}

?>
<!-- End File: chat.php -->