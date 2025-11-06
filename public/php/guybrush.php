<?php

$windowWidth = getenv( 'width' );
$windowHeight = getenv( 'height' );

$init = false;
$palette = [];
$buffer = "";
$sprite = [];
$text = [];
$background = [];
$frames = [];
$time = null;

$guybrush = [];


function initialize()
{
	global $init;

	if( ! $init )
	{
		screen();
		sprite();
		text();
		background();
		frames();
		setup();

		$init = true;
	}
}

function screen()
{
	global $windowWidth, $windowHeight, $buffer, $palette;

	$palette = array_map( fn( $v ) => chr( intdiv( $v, 2 ) ), range( 0, 255 ) );

	$buffer = str_repeat( "\0", $windowWidth * $windowHeight * 3 );
}

function sprite()
{
	global $sprite, $palette;

	$file = file_get_contents( 'assets/guybrush_sprite.png' );

	$image = imagecreatefromstring( $file );

	$width = imagesx( $image );
	$height = imagesy( $image );
	$pixels = [];

	for( $y = 0; $y < $height; $y++ )
	{
		$rowOffset = $y * $width * 3;

		for( $x = 0; $x < $width; $x++ )
		{
			$colorIndex = imagecolorat( $image, $x, $y );
			$colors = imagecolorsforindex( $image, $colorIndex );

			$index = $rowOffset + $x * 3;

			if( $colors[ 'red' ] === 0 && $colors[ 'green' ] === 136 && $colors[ 'blue' ] === 68 )
			{
				$pixels[ $index ] = null;
				$pixels[ $index + 1 ] = null;
				$pixels[ $index + 2 ] =  null;

				continue;
			}

			$pixels[ $index ] = $palette[ $colors[ 'red' ] ];
			$pixels[ $index + 1 ] = $palette[ $colors[ 'green' ] ];
			$pixels[ $index + 2 ] =  $palette[ $colors[ 'blue' ] ];
		}
	}

	$sprite = [
		"width" => $width,
		"height" => $height,
		"pixels" => $pixels
	];
}

function text()
{
	global $text, $palette;

	$file = file_get_contents( 'assets/guybrush_text.png' );

	$image = imagecreatefromstring( $file );

	$width = imagesx( $image );
	$height = imagesy( $image );
	$pixels = [];

	for( $y = 0; $y < $height; $y++ )
	{
		$rowOffset = $y * $width * 3;

		for( $x = 0; $x < $width; $x++ )
		{
			$colorIndex = imagecolorat( $image, $x, $y );
			$colors = imagecolorsforindex( $image, $colorIndex );

			$index = $rowOffset + $x * 3;

			if( $colors[ 'red' ] === 0 && $colors[ 'green' ] === 136 && $colors[ 'blue' ] === 68 )
			{
				$pixels[ $index ] = null;
				$pixels[ $index + 1 ] = null;
				$pixels[ $index + 2 ] =  null;

				continue;
			}

			$pixels[ $index ] = $palette[ $colors[ 'red' ] ];
			$pixels[ $index + 1 ] = $palette[ $colors[ 'green' ] ];
			$pixels[ $index + 2 ] =  $palette[ $colors[ 'blue' ] ];
		}
	}

	$text = [
		"width" => $width,
		"height" => $height,
		"pixels" => $pixels
	];
}


function background()
{
	global $background, $palette;

	$file = file_get_contents( 'assets/guybrush_background.png' );

	$image = imagecreatefromstring( $file );

	$width = imagesx( $image );
	$height = imagesy( $image );
	$pixels = [];

	for( $y = 0; $y < $height; $y++ )
	{
		$rowOffset = $y * $width * 3;

		for( $x = 0; $x < $width; $x++ )
		{
			$colorIndex = imagecolorat( $image, $x, $y );
			$colors = imagecolorsforindex( $image, $colorIndex );

			$index = $rowOffset + $x * 3;

			if( $colors[ 'red' ] === 0 && $colors[ 'green' ] === 136 && $colors[ 'blue' ] === 68 )
			{
				$pixels[ $index ] = null;
				$pixels[ $index + 1 ] = null;
				$pixels[ $index + 2 ] =  null;

				continue;
			}

			$pixels[ $index ] = $palette[ $colors[ 'red' ] ];
			$pixels[ $index + 1 ] = $palette[ $colors[ 'green' ] ];
			$pixels[ $index + 2 ] =  $palette[ $colors[ 'blue' ] ];
		}
	}

	$background = [
		"width" => $width,
		"height" => $height,
		"pixels" => $pixels
	];
}

function frames()
{
	global $frames, $sprite;

	$frames[ 'idle-right' ] = [ extractFrame( $sprite , 326, 0, 366, 52 ) ];
	$frames[ 'idle-left' ] = [ extractFrame( $sprite , 324, 0, 364, 52, true ) ];

	$frames[ 'idle-back-right' ] = [ extractFrame( $sprite , 642, 0, 680, 52 ) ];
	$frames[ 'idle-back-left' ] = [ extractFrame( $sprite , 642, 0, 680, 52, true ) ];

	$frames[ 'idle-walk-right' ] = [ extractFrame( $sprite , 10, 0, 50, 52 ) ];
	$frames[ 'idle-walk-left' ] = [ extractFrame( $sprite , 10, 0, 50, 52, true ) ];

	$frames[ 'walk-right' ] = [
		extractFrame( $sprite , 56, 0, 96, 52 ),
		extractFrame( $sprite , 100, 0, 138, 52 ),
		extractFrame( $sprite , 146, 0, 186, 52 ),
		extractFrame( $sprite , 192, 0, 232, 52 ),
		extractFrame( $sprite , 234, 0, 274, 52 ),
		extractFrame( $sprite , 282, 0, 320, 52 )
	];

	$frames[ 'walk-left' ] = [
		extractFrame( $sprite , 56, 0, 96, 52, true ),
		extractFrame( $sprite , 100, 0, 138, 52, true ),
		extractFrame( $sprite , 146, 0, 186, 52, true ),
		extractFrame( $sprite , 192, 0, 232, 52, true ),
		extractFrame( $sprite , 234, 0, 274, 52, true ),
		extractFrame( $sprite , 282, 0, 320, 52, true )
	];
}

function setup()
{
	global $guybrush, $frames, $windowHeight, $windowWidth;

	$guybrush = [
		'x' => ( $windowWidth / 2 - 60 ) - $frames[ 'idle-walk-right' ][ 0 ][ 'width' ] / 2,
		'y' => $windowHeight / 2 - $frames[ 'idle-walk-right' ][ 0 ][ 'height' ] / 2,
		'frame' => 0,
		'action' => 'idle-walk-right'
	];
}

function extractFrame( $sprite, $startX, $startY, $endX, $endY, $flip = false )
{
	$frame = [];
	$width = $endX - $startX;
	$height = $endY - $startY;

	for( $y = $startY; $y < $endY; $y++ )
	{
		for( $x = $startX; $x < $endX; $x++ )
		{
			$position = $flip ? ( $endX - 1 - ( $x - $startX ) ) : $x;


			$index = ( $y * $sprite[ 'width' ] + $position ) * 3;

			$frame[] = $sprite[ 'pixels' ][ $index ] ?? null;
			$frame[] = $sprite[ 'pixels' ][ $index + 1 ] ?? null;
			$frame[] = $sprite[ 'pixels' ][ $index + 2 ] ?? null;
		}
	}

	return [
		'width' => $width,
		'height' => $height,
		'pixels' => $frame
	];
}

function update()
{
	global $guybrush, $time;

	$file = @file_get_contents( '/request/stdin' );

	if( $file )
	{
		$content = json_decode( $file, true );

		if( $content[ 'action' ] === 'down' )
		{
			switch( $content[ 'code' ] )
			{
				case 'ArrowRight' : if( $guybrush[ 'x' ] < 392 ) $guybrush[ 'x' ] += 8; $guybrush[ 'frame' ] = ( $guybrush[ 'frame' ] + 1 ) % 6; $guybrush[ 'action' ] =	'walk-right'; break;
				case 'ArrowLeft' : if( $guybrush[ 'x' ] > 190 ) $guybrush[ 'x' ] -= 8; $guybrush[ 'frame' ] = ( $guybrush[ 'frame' ] + 1 ) % 6; $guybrush[ 'action' ] = 'walk-left'; break;
				case 'ArrowDown' : if( $guybrush[ 'action' ] == 'idle-walk-left' || $guybrush[ 'action' ] == 'idle-back-right' ) $guybrush[ 'action' ] = 'idle-left';  if( $guybrush[ 'action' ] == 'idle-walk-right' || $guybrush[ 'action' ] == 'idle-back-left'  ) $guybrush[ 'action' ] = 'idle-right'; break;
				case 'ArrowUp' : if( $guybrush[ 'action' ] == 'idle-walk-left' || $guybrush[ 'action' ] == 'idle-left' ) $guybrush[ 'action' ] = 'idle-back-right';  if( $guybrush[ 'action' ] == 'idle-walk-right' || $guybrush[ 'action' ] == 'idle-right' ) $guybrush[ 'action' ] = 'idle-back-left'; break;

				case 'Space' : if( $guybrush[ 'action' ] == 'idle-walk-right' || $guybrush[ 'action' ] == 'idle-back-left' ) $guybrush[ 'action' ] = 'idle-right'; if( $guybrush[ 'action' ] == 'idle-walk-left' || $guybrush[ 'action' ] == 'idle-back-right' ) $guybrush[ 'action' ] = 'idle-left'; $time = microtime( true ) + 4; break;
			}
		}
		else
		{
			if( $guybrush[ 'action' ] == 'walk-right' ) $guybrush[ 'action' ] = 'idle-walk-right';
			if( $guybrush[ 'action' ] == 'walk-left' ) $guybrush[ 'action' ] = 'idle-walk-left';

			$guybrush[ 'frame' ] = 0;
		}
	}
}


function render()
{
	global $buffer, $frames, $text, $background, $time, $windowHeight, $windowWidth, $guybrush;

	$buffer = str_repeat( "\0", $windowWidth * $windowHeight * 3 );

	$backgroundWidth = $background[ 'width' ];
	$backgroundHeight = $background[ 'height' ];
	$backgroundPixels = $background[ 'pixels' ];


	for( $y = 0; $y < $backgroundHeight; $y++ )
	{
		$destY = ( ( $windowHeight / 2 - 30 ) - ( $backgroundHeight - 1 ) / 2 ) + $y;
		if( $destY < 0 || $destY >= $windowHeight ) continue;

		for( $x = 0; $x < $backgroundWidth; $x++ )
		{
			$destX = ( $windowWidth / 2 - $backgroundWidth / 2 ) + $x;
			if( $destX < 0 || $destX >= $windowWidth ) continue;

			$textIndex = ( $y * $backgroundWidth + $x ) * 3;
			$bufferIndex = ( $destY * $windowWidth + $destX ) * 3;

			if( $backgroundPixels[ $textIndex ] !== null ) $buffer[ $bufferIndex ] = $backgroundPixels[ $textIndex ];
			if( $backgroundPixels[ $textIndex + 1 ] !== null ) $buffer[ $bufferIndex + 1 ] = $backgroundPixels[ $textIndex + 1 ];
			if( $backgroundPixels[ $textIndex + 2 ] !== null ) $buffer[ $bufferIndex + 2 ] = $backgroundPixels[ $textIndex + 2 ];
		}
	}

	$frame = $frames[ $guybrush[ 'action' ] ][ $guybrush[ 'frame' ] ];
	$xPos = $guybrush[ 'x' ];
	$yPos = $guybrush[ 'y' ];

	$frameWidth = $frame[ 'width' ];
	$frameHeight = $frame[ 'height' ];
	$pixels = $frame[ 'pixels' ];

	for( $y = 0; $y < $frameHeight; $y++ )
	{
		$destY = $yPos + $y;

		if( $destY < 0 || $destY >= $windowHeight ) continue;

		for( $x = 0; $x < $frameWidth; $x++ )
		{
			$destX = $xPos + $x;

			if( $destX < 0 || $destX >= $windowWidth ) continue;

			$frameIndex = ( $y * $frameWidth + $x ) * 3;
			$bufferIndex = ( $destY * $windowWidth + $destX ) * 3;

			if( $pixels[ $frameIndex ] ) $buffer[ $bufferIndex ] = $pixels[ $frameIndex ];
			if( $pixels[ $frameIndex + 1 ] ) $buffer[ $bufferIndex + 1 ] = $pixels[ $frameIndex + 1 ];
			if( $pixels[ $frameIndex + 1 ] ) $buffer[ $bufferIndex + 2 ] = $pixels[ $frameIndex + 2 ];
		}
	}

	if( microtime( true ) < $time )
	{
		$textWidth = $text[ 'width' ];
		$textHeight = $text[ 'height' ];
		$textPixels = $text[ 'pixels' ];

		$textX = ( $xPos + ( $frameWidth / 2 ) ) - ( ( $textWidth + 1 ) / 2 );
		$textY = $yPos - 40;


		for( $y = 0; $y < $textHeight; $y++ )
		{
			$destY = $textY + $y;
			if( $destY < 0 || $destY >= $windowHeight ) continue;

			for( $x = 0; $x < $textWidth; $x++ )
			{
				$destX = $textX + $x;
				if( $destX < 0 || $destX >= $windowWidth ) continue;

				$textIndex = ( $y * $textWidth + $x ) * 3;
				$bufferIndex = ( $destY * $windowWidth + $destX ) * 3;

				if( $textPixels[ $textIndex ] !== null ) $buffer[ $bufferIndex ] = $textPixels[ $textIndex ];
				if( $textPixels[ $textIndex + 1 ] !== null ) $buffer[ $bufferIndex + 1 ] = $textPixels[ $textIndex + 1 ];
				if( $textPixels[ $textIndex + 2 ] !== null ) $buffer[ $bufferIndex + 2 ] = $textPixels[ $textIndex + 2 ];
			}
		}
	}

	echo $buffer;

	usleep( 50_000 );
}

while( true )
{
	initialize();

	update();

	render();
}
