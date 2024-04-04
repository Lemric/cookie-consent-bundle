import babel from '@rollup/plugin-babel';

const config = {
    input: 'Resources/assets/js/cookie-consent.mjs',
    output: {
        file: 'Resources/public/js/cookie-consent.min.js',
        format: 'esm'
    },
    plugins: [babel({ babelHelpers: 'bundled' })]
};

export default config;