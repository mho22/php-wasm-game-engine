import { PHP } from '@php-wasm/universal';
import { loadWebRuntime } from "@php-wasm/web";

( async () =>
{
	const file = 'test5.php';

    const php = new PHP( await loadWebRuntime( '8.4' ) );

    php.writeFile( file, await ( await fetch( file ) ).text() );

	onmessage = async () =>
	{
		const response = await php.runStream({ scriptPath : file });

		response.stdout.pipeTo(new WritableStream({ async write( frame )
		{
			postMessage( frame, [ frame.buffer ] );
		}}));
	}

})();
