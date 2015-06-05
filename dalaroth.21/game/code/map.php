<?
function show_map($_fromx,$_tox,$_fromy,$_toy)
{
	$_time = microtime();
	$_mappath = "./";
	global $db;
		while(!file_exists($_mappath."mysql.php"))
		{
			$_mappath.="../";
			if(strlen($_mappath) > 50)
			return "ERROR: can't find mysql file.";
		}
		include $_mappath . "mysql.php";
		$_mappath = substr($_mappath,0,strlen($_mappath)-3);
	


	$map_q = "SELECT * FROM locations WHERE x>'".$_fromx."' AND x<'".$_tox."' AND y>'".$_fromy."' AND y<'".$_toy."' ORDER BY y DESC,x ASC";
	$map_res = mysql_query($map_q);


?>
<br/><br/>
<?


	$map = array();
	$bigymap = array();
	$smallymap = array();
	$ymap = array();
	$_r = 0;
	for($_var=0;$_var<mysql_num_rows($map_res);$_var++)
		array_push($map,mysql_fetch_assoc($map_res));
	foreach($map as $_map)
	{
		array_push($ymap,$_map);
	}

?>

	<table onMouseOver="changeTip('This is your minimap, you are in the center.');" onMouseOut="changeTip();" cellpadding=0 cellspacing=0 border=0>

<?
	foreach($ymap as $big)
		$bigxmap[$big["y"]][$big["x"]] = $big;
	$lowx =  find_lowest_value($map,"x")-1;
	$highx = find_highest_value($map,"x")+1;
	foreach($bigxmap as $row)
	{
		echo "\n<tr>";
		for($_r = $lowx;$_r < $highx;$_r++)
		{
			echo "<td>";
			if(isset($row[$_r]))
			{
				$_trav = explode(",",$row[$_r]["travel"]);
				$no_ext = substr($row[$_r]["file"],0,strlen($row[$_r]["file"])-4);
				if($_SESSION["userinfo"]["x"] == $row[$_r]["x"] && $_SESSION["userinfo"]["y"] == $row[$_r]["y"])
					echo "<img border=0 alt=' ' src='".$_mappath."icons/you.png' />\n";
				elseif(file_exists($_mappath."icons/$no_ext"."ns.png")&&(in_array("n",$_trav) || in_array("s",$_trav)))
					echo "<img border=0 alt=' ' src='".$_mappath."icons/".$no_ext."ns.png' />\n";
				elseif(file_exists($_mappath."icons/$no_ext"."ew.png"))
					echo "<img border=0 alt=' ' src='".$_mappath."icons/".$no_ext."ew.png' />\n";
				else
					echo "<img border=0 alt=' ' src='".$_mappath."icons/$no_ext.png' />\n";
			}
			echo "</td>";
		}
		echo "</tr>\n";
	}


?>
	</table>

	<!--
	Map loaded in <?$_timeend = microtime(); echo number_format(((substr($_timeend,0,9)) + (substr($_timeend,-10)) - (substr($_time,0,9)) - (substr($_time,-10))),4);?> seconds.
	-->
<?
}//end show_map()
