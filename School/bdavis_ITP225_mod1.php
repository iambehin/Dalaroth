<!DOCTYPE html>
<html>
    <body>
	<?php
		$widths = array(0 => 20, 30, 20, 40, 50, 30, 50, 30);
		$heights = array(0 => 9, 9, 9, 9, 10, 10, 10, 10);
		$rates = array('PROFESSIONAL' => 17.50, 'REGULAR' => 12.50);
		$types = array('FLAT' => 24.00, 'SATIN' => 31.50, 'GLOSS' => 27.75);

		DEFINE('COVERAGE', 310);
		DEFINE('TIME', 8);

		$index = 0;
		$totalTime = 0;
		$totalGal = 0;
		foreach ($widths as $key => $width) {
			$height = $heights[$index];
			$area = $width*$height;
			$totalTime += $area/COVERAGE*TIME;
			$totalGal += $area/COVERAGE;
			$index++;
			echo "Wall $index width: $width height: $height	area: $area gallons: ".round($area/COVERAGE,2)." hours: ".round($area/COVERAGE*TIME,2)."<br>";
		}
		echo "<br>The total time to paint the area is ".ceil($totalTime)." hours, and it will take ".ceil($totalGal)." gallons of paint.<br>";

		foreach ($rates as $labor => $rate) {
			foreach ($types as $type => $cost) {
				$laborCost = $totalTime * $rate;
				$paintCost = $totalGal * $cost;
				echo "$labor: Labor cost will be $".round($laborCost, 2)." and the paint will cost \$".round($paintCost, 2)." if you use $type paint.<br>TOTAL: $".round($laborCost + $paintCost, 2)."<br>";
			}
		}
		?>
    </body>
</html>
