import { PHP } from '@php-wasm/universal';
import { loadWebRuntime } from "@php-wasm/web";

let php = null;
let initialized = false;

self.onmessage = async event =>
{
	if( event.data.state )
	{
		if( event.data.state == 'init' )
		{
			const { width, height } = event.data;

			if( ! initialized )
			{
				initialized = true;

				php = new PHP( await loadWebRuntime( '8.4', { emscriptenOptions: { ENV: { width, height } } } ) );
			}

			const sprite = '../assets/guybrush_sprite.png';
			const text = '../assets/guybrush_text.png';
			const background = '../assets/guybrush_background.png';

			php.mkdir( 'assets' );

			php.writeFile( sprite, new Uint8Array( await ( await fetch( sprite ) ).arrayBuffer() ) );
			php.writeFile( text, new Uint8Array( await ( await fetch( text ) ).arrayBuffer() ) );
			php.writeFile( background, new Uint8Array( await ( await fetch( background ) ).arrayBuffer() ) );

			const file = '../php/guybrush.php';

			php.mkdir( 'php' );

			php.writeFile( file, await ( await fetch( file ) ).text() );

			const response = await php.runStream( { scriptPath : file } );

			let count = 0;

			setInterval( () => { console.clear(); console.log( `${count} fps\r` );count = 0; }, 1000 );

			response.stdout.pipeTo(new WritableStream( { async write( frame )
			{
				// console.log( new TextDecoder().decode( frame ).replace( /<[^>]+>/g, '' ) );

				count++;

				postMessage( frame, [ frame.buffer ] );
			} } ) );
		}

		if( event.data.state == 'update' )
		{
			php.writeFile( '/request/stdin', JSON.stringify( { 'action' : event.data.action, 'code' : event.data.code } ) );
		}
	}
};
