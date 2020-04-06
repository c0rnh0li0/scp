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

firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

messaging.usePublicVapidKey("BLR2mpamo0soXSNPk5x__Hm58ustcM2kD-Ei_zqhxAFgmdAGrBpZfTSiI3DLbEZZ16AO7KS4xssZwEwJfLqY04A");

/*if ('serviceWorker' in navigator && 'PushManager' in window) {
    window.addEventListener('load', function(e) {

        navigator.serviceWorker.register('/service-worker.js')
            .then(function(registration) {
                console.log('register SW', registration);

                if (registration.installing) {
                    console.log('Service Worker is Installing', registration);
                }
                else if (registration.waiting) {
                    console.log('Service Worker is Waiting', registration);
                    // Service Worker is Waiting
                }
                else if (registration.active) {
                    console.log('Service Worker is Active', registration);
                    // Service Worker is Active
                }

                console.log('Service Worker is registered', registration);
                //swRegistration = swReg;
            })
            .catch(function(error) {
                console.error('Service Worker Error', error);
            });

        // Request permission to push message to the client
        Notification.requestPermission(function (status) {
            // register for push notifications
            navigator.serviceWorker.register('/firebase-messaging-sw.js').then(function(registration) {
                console.log('firebase-messaging-sw.js registration successful with scope: ', registration.scope);

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

            }, function(err) {
                // registration failed :(
                console.log('firebase-messaging-sw.js registration failed: ', err);
            });
        });
    });
}*/

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
    // ...
});