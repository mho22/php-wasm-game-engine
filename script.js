const worker = new Worker( 'worker.js', { type : 'module' } );

const canvas = document.getElementById( 'canvas' );
const context = canvas.getContext( '2d' );

context.imageSmoothEnabled = false;

canvas.width = 320;
canvas.height = 200;

const rgba = new Uint8ClampedArray( canvas.width * canvas.height * 4 );

worker.onmessage = event =>
{
	const rgb = event.data;

    for( let i = 0, j = 0; i < rgb.length; i += 3, j += 4 )
	{
        rgba[ j ] = rgb[ i ] * 2;
        rgba[ j + 1 ] = rgb[ i + 1 ] * 2;
        rgba[ j + 2 ] = rgb[ i + 2 ] * 2;
        rgba[ j + 3 ] = 255;
    }

    const imageData = new ImageData( rgba, canvas.width, canvas.height );

    createImageBitmap( imageData ).then( bitmap => context.drawImage( bitmap, 0, 0, canvas.width, canvas.height ) );
}
