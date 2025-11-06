<?php

$width = getenv( 'width' );
$height = getenv( 'height' );
$size = $width * $height * 3;

$buffer = str_repeat( "\0", $size );

$table = array_map( fn( $v ) => chr( intdiv( $v, 2 ) ), range( 0, 255 ) );

while( true )
{
    for( $i = 0; $i < $size; $i += 3 )
    {
        $buffer[ $i ] = $table[ rand( 0, 255 ) ];
        $buffer[ $i + 1 ] = $table[ rand( 0, 255 ) ];
        $buffer[ $i + 2 ] = $table[ rand( 0, 255 ) ];
    }

    echo $buffer;

    usleep( 0 );
}
