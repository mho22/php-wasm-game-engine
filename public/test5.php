<?php

$width = 300;
$height = 400;
$size = $width * $height * 3;

$buffer = str_repeat( "\0", $size );

$table = [];

for( $v = 0; $v < 256; $v++ ) $table[ $v ] = chr( intdiv( $v, 2 ) );

for( $i = 0; $i < $size; $i += 3 )
{
	// $buffer[ $i ] = $table[ rand( 0, 255 ) ];
	// $buffer[ $i + 1 ] = $table[ rand( 0, 255 ) ];
	// $buffer[ $i + 2 ] = $table[ rand( 0, 255 ) ];

	$buffer[ $i ] = $table[ 0 ];
	$buffer[ $i + 1 ] = $table[ 0 ];
	$buffer[ $i + 2 ] = $table[ 255 ];
}

echo $buffer;
