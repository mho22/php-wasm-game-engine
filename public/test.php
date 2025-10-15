<?php

$starttime = microtime( true );

for( $x = 0; $x <= 50000000; $x++ )
{
	// echo $x;

	// usleep( 1 );
}

$endtime = microtime( true );

$timediff = $endtime - $starttime;

echo $timediff;
