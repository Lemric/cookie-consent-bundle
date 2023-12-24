import babel from '@rollup/plugin-babel';
import terser from '@rollup/plugin-terser';

const config = {
    input: 'Resources/assets/js/cookie-consent.mjs',
    output: {
        file: 'Resources/public/js/cookie-consent.min.js',
        format: 'esm',
        compact: true,
        plugins: [terser()]
    },
    plugins: [babel({ babelHelpers: 'bundled' })]
};

export default config;