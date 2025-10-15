import { PHP } from '@php-wasm/universal';
import { loadWebRuntime } from "@php-wasm/web";

( async () =>
{
	const file = 'test6.php';

    const php = new PHP( await loadWebRuntime( '8.4' ) );

    php.writeFile( file, await ( await fetch( file ) ).text() );

    const response = await php.runStream({ scriptPath : file });

	const width = 320;
	const height = 200;

	let count = 0;
	let buffer = [];

	setInterval( () => { console.clear(); console.log( `${count} fps\r`);count = 0; }, 1000);

	response.stdout.pipeTo(new WritableStream({ async write( frame )
	{
		buffer = Int8Array.from( [ ...buffer, ...frame ] );

		console.log( buffer.length, width * height * 3 );

		if( buffer.length >= width * height * 3 )
		{
			count++;

			postMessage( buffer, [ buffer ] );

			buffer = [];
		}
	}}));
})();
