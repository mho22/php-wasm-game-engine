<?php
// Each frame is a 300x400 RGB array
$width = 300;
$height = 400;
$frameCount = 0;

while ($frameCount < 120) { // run 60 frames

	// $starttime = microtime( true );

    $frame = '';
    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            // generate RGB as bytes
            $r = ($x+$frameCount) % 256;
            $g = ($y+$frameCount) % 256;
            $b = ($x+$y+$frameCount) % 256;
            $frame .= chr($r) . chr($g) . chr($b);
        }
    }

    echo $frame;  // output entire frame in one write

    $frameCount++;

	// $endtime = microtime( true );

	// $timediff = $endtime - $starttime;

	// echo $timediff;
}
