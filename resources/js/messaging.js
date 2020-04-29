//import firebase from 'https://www.gstatic.com/firebasejs/7.13.0/firebase-app.js';
//import 'https://www.gstatic.com/firebasejs/7.13.0/firebase-messaging.js';

if ('serviceWorker' in navigator && 'PushManager' in window) {
    window.addEventListener('load', function(e) {
        navigator.serviceWorker.register('/sw.js', { scope: '/' }).then(function (registration) {
            console.log('Service worker successfully registered on scope', registration.scope);

            Notification.requestPermission(function (status) {
                if (status == "granted")
                    initializeFirebaseMessaging();
            });
        }).catch(function (error) {
            console.log('Service worker failed to register: ', error);
        });
    });
}

const initializeFirebaseMessaging = function () {
    const firebase = require('firebase/app');
    require('firebase/messaging');

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
    // web conf private key: ZjzK5INUxSOHamIXh01ul7VR_7YCgPxixKHG_MxtfjQ

    firebase.initializeApp(firebaseConfig);

    if (firebase.messaging.isSupported()) {
        const messaging = firebase.messaging();

        messaging.usePublicVapidKey("BMGZi7KkzFlsaSmTemeyva9KU-iAHxCPhFIScrzGBOE9q2VIers7dPajIdA9WDyPVcPEwJ97bm7KUrysxh_GeZQ");

        messaging.getToken().then((currentToken) => {
            if (currentToken) {
                //sendTokenToServer(currentToken);
                //updateUIForPushEnabled(currentToken);
                console.log('firebase token: ', currentToken)
            } else {
                // Show permission request.
                console.log('No Instance ID token available. Request permission to generate one.');
                // Show permission UI.
                //updateUIForPushPermissionRequired();
                //setTokenSentToServer(false);
            }
        }).catch((err) => {
            console.log('An error occurred while retrieving token. ', err);
            //showToken('Error retrieving Instance ID token. ', err);
            //setTokenSentToServer(false);
        });

        messaging.onTokenRefresh(() => {
            messaging.getToken().then((refreshedToken) => {
                console.log('Token refreshed: ', refreshedToken);
                // Indicate that the new Instance ID token has not yet been sent to the
                // app server.
                //setTokenSentToServer(false);
                // Send Instance ID token to app server.
                //sendTokenToServer(refreshedToken);
                // ...
            }).catch((err) => {
                console.log('Unable to retrieve refreshed token ', err);
                //showToken('Unable to retrieve refreshed token ', err);
            });
        });

        messaging.onMessage((payload) => {
            console.log('Message received. ', payload);

            let options = buildNotificationOptions(payload);
            options.requireInteraction= true;
            let notifTitle = payload.data.title ? payload.data.title : payload.notification.title;

            let notif = new Notification(notifTitle , options);
            notif.addEventListener("close", function (event) {
                event.preventDefault();
                processNotification(event.target, event);
            } );
        });
    }
}

function processNotification(notification, event) {
    console.log('notification clicked', notification);
}

function buildNotificationOptions(payload) {
    var options = {};
    /* Object */
    options.data = payload.data;
    if (typeof payload.data.body != 'undefined')
        options.body = payload.data.body;
    if (typeof payload.notification.body != 'undefined')
        options.body = payload.notification.body;

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