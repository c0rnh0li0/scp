const manifestJSON = require('./public/manifest.json')

module.exports = {
    pwa: {
        name: 'Skopje City Pass',
        themeColor: '#4DBA87',
        msTileColor: '#000000',
        appleMobileWebAppCapable: 'yes',
        appleMobileWebAppStatusBarStyle: 'white',

        // configure the workbox plugin
        workboxPluginMode: 'InjectManifest',
        workboxOptions: {
            swSrc: "resources/js/service-worker.js"
        }
    }
}