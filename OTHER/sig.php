<?php

    header('Content-type: image/x-png');

$sigPaths = array("wallsig.png","bloodsig.png","comicsig.png", "erasehomo.png", "gay.png");


$img = imagecreatefrompng($sigPaths[array_rand($sigPaths)]);

imageAlphaBlending($img, false);
imageSaveAlpha($img, true);

imagepng($img);
?> 