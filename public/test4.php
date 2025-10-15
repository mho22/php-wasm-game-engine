<?php

$width = 320;
$height = 200;
$size = $width * $height * 3;
$frame = 120;

$offset = 0;

$buffer = str_repeat( "\0", $size );

$table = [];

for( $v = 0; $v < 256; $v++ ) $table[ $v ] = chr( intdiv( $v, 2 ) );

while( true )
{
    for( $i = 0; $i < $size; $i += 3 )
    {
        // $buffer[ $i ] = $table[ ( ( $i * 3 + $offset ) + 127 ) & 255 ];
        // $buffer[ $i + 1 ] = $table[ ( ( $i * 5 + $offset ) + 127 ) & 255 ];
        // $buffer[ $i + 2 ] = $table[ ( ( $i * 7 + $offset ) + 127 ) & 255 ];

        // $buffer[ $i ] = $table[ rand( 0, 255 ) ];
        // $buffer[ $i + 1 ] = $table[ rand( 0, 255 ) ];
        // $buffer[ $i + 2 ] = $table[ rand( 0, 255 ) ];

        $buffer[ $i ] = $table[ 0 ];
        $buffer[ $i + 1 ] = $table[ 0 ];
        $buffer[ $i + 2 ] = $table[ 255 ];
    }

    echo $buffer;

    $offset++;

    usleep( 0 );
}
