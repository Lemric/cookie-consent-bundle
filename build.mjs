import {globSync} from "glob";
import fs from "fs";
import autoprefixer from "autoprefixer";
import * as sass from "sass";
import postcss from "postcss";
import path from "path";

// read all scss files
const scssFiles = globSync('Resources/assets/scss/*.scss', { ignore: 'node_modules/**' });

scssFiles.forEach((file) => {
    const result = sass.compile(file, {style: 'compressed'});

    const filename = path.parse(file).name;

    if (!result.css) {
        return;
    }

    postcss([autoprefixer/*, postcssNested*/])
        .process(result.css, { from: result.css, to: `Resources/public/css/${filename}.css` })
        .then(result => {
            fs.writeFileSync(`Resources/public/css/${filename}.css`, result.css)
            if ( result.map ) {
                fs.writeFileSync(`Resources/public/css/${filename}.css.map`, result.map.toString())
            }
        });
});
