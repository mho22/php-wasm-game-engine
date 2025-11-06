import { PHP } from '@php-wasm/universal';
import { loadWebRuntime } from "@php-wasm/web";

let php = null;
let initialized = false;

self.onmessage = async event =>
{
	const { width, height } = event.data;

    if( ! initialized )
	{
		initialized = true;

		php = new PHP( await loadWebRuntime( '8.4', { emscriptenOptions: { ENV: { width, height } } } ) );
	}

	const asset = '../assets/guybrush_face.png';

	php.mkdir( 'assets' );

	php.writeFile( asset, new Uint8Array( await ( await fetch( asset ) ).arrayBuffer() ) );

	const file = '../php/image.php';

	php.mkdir( 'php' );

	php.writeFile( file, await ( await fetch( file ) ).text() );

	const response = await php.runStream( { scriptPath : file } );

	let count = 0;

	setInterval( () => { console.clear(); console.log( `${count} fps\r` );count = 0; }, 1000 );

	response.stdout.pipeTo(new WritableStream( { async write( frame )
	{
		count++;

		postMessage( frame, [ frame.buffer ] );
	} } ) );
};
