module.exports = {
    "globDirectory": "public/",
    "globPatterns": [
        "**/*.{png,css,ico,jpg,svg,js,json,webp,ttf,woff,woff2,eot,webmanifest}"
    ],
    // manifestTransforms: [
    //     // Basic transformation to remove a certain URL:
    //     (originalManifest) => {
    //         const manifest = originalManifest.map((entry) => {
    //             // entry.url = `https://cdn.example.com/${entry.url}`;
    //             console.log(entry.url);
    //             return entry;
    //         });
    //         // Optionally, set warning messages.
    //         const warnings = [];
    //         return {
    //             manifest,
    //             warnings
    //         };
    //     }
    // ],
    maximumFileSizeToCacheInBytes: 15 * 1024 * 1024,
    // globIgnores: [
    //     '**/mix-manifest.json'
    // ],
    "swDest": "public/service-worker.js",
    "swSrc": "resources/js/service-worker.js"
};
