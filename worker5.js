( async () =>
{
	const width = 1920;
	const height = 1080;
	const pixels = width * height * 3;

	const buffer = new Uint8Array( pixels );

	onmessage = async () =>
	{
		for( let i = 0; i < buffer.length; i += 3 )
		{
			buffer[ i ] = Math.random() * 255;
			buffer[ i + 1 ] = Math.random() * 255;
			buffer[ i + 2 ] = Math.random() * 255;
		}

		postMessage( buffer );
	}
})();
