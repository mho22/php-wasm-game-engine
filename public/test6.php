<?php

$buffer = str_repeat( "\0", 3 );

$table = [];

for( $v = 0; $v < 256; $v++ ) $table[ $v ] = chr( intdiv( $v, 2 ) );

while( true )
{
    $buffer[ 0 ] = $table[ rand( 0, 255 ) ];
    $buffer[ 1 ] = $table[ rand( 0, 255 ) ];
    $buffer[ 2 ] = $table[ rand( 0, 255 ) ];

    echo $buffer;

    usleep( 0 );
}
