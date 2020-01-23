module.exports = {
    // Path to be offline cache
    'globDirectory': 'public/',
    'globPatterns': [
        '/**/*.{css,ico,ttf,woff,svg,png,jpg,php,js,xml,webmanifest,json,txt,gif,md,html,scss,eot,woff2,swf,otf}',
        'offline.html',
        'mix-manifest.json',
    ],
    'swDest': 'public/offline.js',
    'globIgnores': [],
    'swSrc': 'resources/js/service-worker.js'
};