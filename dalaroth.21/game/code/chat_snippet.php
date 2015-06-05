<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<META HTTP-EQUIV="REFRESH" CONTENT="30">
<title>Chat</title>
</head>
<body>
<span class=white>Here are the most recent chats, click on chat on the side to load all chats and to open the chatbox.</span>
<br/>
<?
global $db;

$chat_result = @mysql_query("SELECT * FROM chat ORDER BY number DESC",$db);
$chat_archive = array();
if(mysql_num_rows($chat_result) > 2)
$chat_num_rows = 2;
else
$chat_num_rows = mysql_num_rows($chat_result) or die(mysql_error());
for($chat_pointer = 0;$chat_pointer < $chat_num_rows;$chat_pointer++)
{
$chats = @mysql_fetch_assoc($chat_result);
array_push($chat_archive,$chats);
}

foreach($chat_archive as $chat_archive_portion)
{
echo "<span class=grey>".$chat_archive_portion["time"] . "</span> ~ <span class=white>" . $chat_archive_portion["user"] . "</span>: " .  $chat_archive_portion["text"] . "<br/>";
}



?>

</body></html>