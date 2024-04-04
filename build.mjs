import {globSync} from "glob";
import fs from "fs";
import autoprefixer from "autoprefixer";
import * as sass from "sass";
import postcss from "postcss";
import path from "path";
import {WebSocket} from 'ws';

// read all scss files
const scssFiles = globSync('Resources/assets/scss/*.scss', {ignore: 'node_modules/**'});

console.log('Building assets started');

for (const file of scssFiles) {
    // transform sass to css
    const result = sass.compile(file, {style: 'compressed'});

    // get filename without extension
    const filename = path.parse(file).name;

    // early return if no css is available
    if (!result.css) {
        continue;
    }

    // handle css by postcss and autoprefixer
    await postcss([autoprefixer])
        .process(result.css, {
            from: `Resources/public/css/${filename}.css`,
            to: `Resources/public/css/${filename}.min.css`
        })
        .then(result => {
            // write css file to file system
            fs.writeFileSync(`Resources/public/css/${filename}.min.css`, result.css);
        })
        .finally(async () => {
            // send websocket message to websocket server
            const client = new WebSocket('ws://localhost:8999');

            // client.on('message', msg => console.log('Message from server:', msg));

            // Wait for the client to connect using async/await
            await new Promise(resolve => client.once('open', resolve));

            // Prints "Hello!" twice, once for each client.
            client.send('reload');

            client.close();
        });
}
