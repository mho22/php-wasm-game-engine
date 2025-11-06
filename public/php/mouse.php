<?php

$width = getenv( 'width' );
$height = getenv( 'height' );
$size = $width * $height * 3;

$buffer = str_repeat( "\0", $size );

$table = array_map( fn( $v ) => chr( intdiv( $v, 2 ) ), range( 0, 255 ) );

$position = $size / 2 + ( $width * 3 / 2 );

$leap = 6;

while( true )
{
	$file = file_get_contents( '/request/stdin' );

	if( $file )
	{
		switch( $file )
		{
			case 'ArrowUp' : $position = $position - ( $width * $leap ); break;
			case 'ArrowRight' : $position = $position + $leap; break;
			case 'ArrowDown' : $position = $position + ( $width * $leap ); break;
			case 'ArrowLeft' : $position = $position - $leap; break;
		}

		file_put_contents( '/request/stdin', '' );
	}

	for( $i = 0; $i < $size; $i += 3 )
	{
		$buffer[ $i ] = $table[ 0 ];
		$buffer[ $i + 1 ] = $table[ 0 ];
		$buffer[ $i + 2 ] = $table[ 0 ];

		if( $i == $position ) $buffer[ $i ] = $table[ 255 ];
	}

    echo $buffer;

    usleep( 0 );
}
