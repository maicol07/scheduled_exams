// noinspection JSUnusedGlobalSymbols

import {esbuildFlowPlugin, flowPlugin} from '@bunchtogether/vite-plugin-flow';
import {defineConfig} from 'laravel-vite';

export default defineConfig({
    assetsInclude: ['js', 'jpg', 'png', 'webp', 'txt'],
    esbuild: {
        target: 'es2021',
        jsxFactory: 'm',
        jsxFragment: 'm.Fragment'
    },
    optimizeDeps: {
        esbuildOptions: {
            plugins: [esbuildFlowPlugin(/\.(flow|jsx?)$/, path => (/\.jsx$/.test(path) ? 'jsx' : 'js'), {
                all: true,
                pretty: true,
                ignoreUninitializedFields: false
            })]
        }
    },
    plugins: [
        flowPlugin({
            include: /\.(flow|jsx?)$/,
            exclude: /node_modules/,
            flow: {
                all: true,
                pretty: true,
                ignoreUninitializedFields: false
            }
        })
    ]
});
