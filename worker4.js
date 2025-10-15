import { PHP } from '@php-wasm/universal';
import { loadWebRuntime } from "@php-wasm/web";

( async () =>
{
	const file = 'test5.php';

    const php = new PHP( await loadWebRuntime( '8.4' ) );

    php.writeFile( file, await ( await fetch( file ) ).text() );

	let count = 0;

	setInterval( () => { console.clear(); console.log( `${count} fps\r`);count = 0; }, 1000);

	async function draw()
	{
		const response = await php.runStream({ scriptPath : file });

		const frame = await response.stdoutBytes;

		postMessage( frame, [ frame.buffer ] );

		count++;

		requestAnimationFrame( draw );
	}

	requestAnimationFrame( draw );
})();
