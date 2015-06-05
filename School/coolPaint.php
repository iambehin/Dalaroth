<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Pete's Paint Paradise</title>
        <link rel="stylesheet" href="petes.css" />
    </head>
    <body>
        <div id='canvas'>
            <header>
                <h1>Welcome to Pete's Paint Paradise</h1>
                <h3>Where we have a plethora of paints to please your peepers!</h3>
            </header>


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

            echo "<p>";
            // display status for each wall.
            foreach ($widths as $key => $value) {
                $wd = $value;
                $ht = $heights[$index];
                $area = $wd * $ht;
                $gal = $area / COVERAGE;
                $labor = ($area / COVERAGE) * TIME;
                $index++;
                $totalGal += $gal;
                $totalTime += $labor;
                echo "Wall $index width: $wd height: $ht 
                    area: $area gallons: ".number_format($gal,2)." hours: ".number_format($labor,2)."<br />";
            }
            echo "</p>";
            $totalTime = ceil($totalTime);
            $totalGal = ceil($totalGal);
            echo "<p>The total time to paint the area is $totalTime hours, and it will take $totalGal gallons of paint.</p>";

            foreach ($rates as $labor => $rate) {
                foreach ($types as $type => $cost) {
                    $laborCost = $totalTime * $rate;
                    $paintCost = $totalGal * $cost;
                    echo "<p>" . strtoupper($labor) . ": Labor cost will be $"
                    . number_format($laborCost, 2)
                    . " and the paint will cost $"
                    . number_format($paintCost, 2) . " if you use $type paint.<br />
                        TOTAL: $".  number_format($laborCost + $paintCost, 2)."</p>";
                }
            }
            ?>
        </div>
    </body>
</html>
