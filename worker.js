import { PHP } from '@php-wasm/universal';
import { loadWebRuntime } from "@php-wasm/web";

( async () =>
{
	const file = 'test4.php';

    const php = new PHP( await loadWebRuntime( '8.4' ) );

    php.writeFile( file, await ( await fetch( file ) ).text() );

    const response = await php.runStream({ scriptPath : file });

	let count = 0;

	setInterval( () => { console.clear(); console.log( `${count} fps\r`);count = 0; }, 1000);

	response.stdout.pipeTo(new WritableStream({ async write( frame )
	{
		count++;

		postMessage( frame, [ frame.buffer ] );
	}}));
})();
