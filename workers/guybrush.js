import { PHP } from '@php-wasm/universal';
import { loadWebRuntime } from "@php-wasm/web";

let php = null;
let initialized = false;

async function initPHP( width, height )
{
    if( ! initialized )
	{
		initialized = true;

		php = new PHP( await loadWebRuntime( '8.4', { emscriptenOptions: { ENV: { width, height } } } ) );
	}
}


self.onmessage = async event =>
{
	const { width, height } = event.data;

	await initPHP( width, height );

	const asset = '../assets/guybrush_profile.png';

	php.mkdir( 'assets' );

	php.writeFile( asset, new Uint8Array( await ( await fetch( asset ) ).arrayBuffer() ) );

	const file = '../php/guybrush.php';

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
