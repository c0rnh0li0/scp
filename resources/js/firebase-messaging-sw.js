importScripts('https://www.gstatic.com/firebasejs/7.13.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.13.0/firebase-messaging.js');

const firebaseConfig = {
    apiKey: "AIzaSyDrKxNprd3CrfaSzH_u__tPzpUNd-aRpmU",
    authDomain: "skopje-city-pass.firebaseapp.com",
    databaseURL: "https://skopje-city-pass.firebaseio.com",
    projectId: "skopje-city-pass",
    storageBucket: "skopje-city-pass.appspot.com",
    messagingSenderId: "332397838213",
    appId: "1:332397838213:web:487455021fbd85cee88eec",
    measurementId: "G-KL1YXRDH6Q"
};

firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function(payload) {
    const notificationOptions = buildNotificationOptions(payload);
    return self.registration.showNotification(payload.data.title, notificationOptions);
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    var promise = new Promise(function(resolve) {
        setTimeout(resolve, 3000);
    }).then(function() {
        return processNotification(event.notification);
    });
    event.waitUntil(promise);
});
self.addEventListener('notificationclose', function(event) {
    console.log("notification close in sw", event);
});
function processNotification(notification) {
    var sAction = notification.data.action;
    var sUrl = notification.data.url;

    return clients.matchAll({
        includeUncontrolled: true,
        type: 'window'
    }).then(function(clis) {
        var client = clis.find(function(c) {
            c.visibilityState === 'visible';
        });
        if (client !== undefined) {
            client.navigate(sUrl);
            return client.focus();
        } else {
            return clients.openWindow(sUrl);
        }
    });
}

function buildNotificationOptions(payload) {
    var options = {};
    /* Object */
    options.data = payload.data;
    if (typeof payload.data.body != 'undefined')
        options.body = payload.data.body;
    /* String */
    if (typeof payload.data.icon != 'undefined')
        options.icon = payload.data.icon;
    /* String */
    if (typeof payload.data.badge != 'undefined')
        options.badge = payload.data.badge;
    /* String */
    if (typeof payload.data.image != 'undefined')
        options.image = payload.data.image;
    /* String */
    if (typeof payload.data.tag != 'undefined')
        options.tag = payload.data.tag;
    /* Array of integers */
    if (typeof payload.data.vibrate != 'undefined')
        options.vibrate = payload.data.vibrate;
    /* String */
    if (typeof payload.data.sound != 'undefined')
        options.sound = payload.data.sound;
    /* Boolean */
    if (typeof payload.data.requireInteraction != 'undefined')
        options.requireInteraction = (typeof payload.data.requireInteraction != 'undefined' ? payload.data.requireInteraction : false);
    /* Boolean */
    if (typeof payload.data.renotify != 'undefined')
        options.renotify = (typeof payload.data.renotify != 'undefined' ? payload.data.renotify : false);
    /* Boolean */
    if (typeof payload.data.silent != 'undefined')
        options.silent = (typeof payload.data.silent != 'undefined' ? payload.data.silent : false);
    return options;
}