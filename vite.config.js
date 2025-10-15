import { defineConfig } from 'vite';

export default defineConfig({
	optimizeDeps : {
		esbuildOptions : {
			loader : {
				'.dat' : 'text'
			}
		}
	},
	assetsInclude : [ "**/*.dat", "**/*.wasm" ]
} );
