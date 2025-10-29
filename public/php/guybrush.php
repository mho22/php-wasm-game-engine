<?php

$windowWidth = getenv( 'width' );
$windowHeight = getenv( 'height' );

$buffer = str_repeat( "\0", $windowWidth * $windowHeight * 3 );

$table = array_map(fn($v) => chr(intdiv($v, 2)), range(0, 255));


$file = file_get_contents( 'assets/guybrush_profile.png' );

$image = imagecreatefromstring( $file );

$width = imagesx( $image );
$height = imagesy( $image );

$offsetX = intdiv( $windowWidth - $width, 2 );
$offsetY = intdiv( $windowHeight - $height, 2 );

while( true )
{
	for( $y = 0; $y < $height; $y++ )
	{
		$rowOffset = ( ( $y + $offsetY ) * $windowWidth + $offsetX ) * 3;

		for( $x = 0; $x < $width; $x++ )
		{
			$colorIndex = imagecolorat( $image, $x, $y );
			$colors = imagecolorsforindex( $image, $colorIndex );

			if( $colors[ 'red' ] === 0 && $colors[ 'green' ] === 0 && $colors[ 'blue' ] === 0 ) continue;
			if( $colors[ 'red' ] === 0 && $colors[ 'green' ] === 136 && $colors[ 'blue' ] === 68 ) continue;

			$index = $rowOffset + $x * 3;

			$buffer[ $index ] = $table[ $colors[ 'red' ] ];
			$buffer[ $index + 1 ] = $table[ $colors[ 'green' ] ];
			$buffer[ $index + 2 ] = $table[ $colors[ 'blue' ] ];
		}
	}

	echo $buffer;

	usleep( 0 );
}
