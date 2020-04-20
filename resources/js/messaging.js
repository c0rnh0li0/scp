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

if (firebase.messaging.isSupported()) {
    const messaging = firebase.messaging();

    if ('serviceWorker' in navigator && 'PushManager' in window) {
        window.addEventListener('load', function(e) {
            navigator.serviceWorker.register('/sw.js', { scope: '/' }).then(function (registration) {
                console.log('Service worker successfully registered on scope', registration.scope);

                /*let newWorker = registration.installing;

                newWorker.addEventListener('statechange', () => {
                    console.log('sw state changed:', newWorker.scriptURL, newWorker.state);
                    switch (newWorker.state) {
                        case 'installed':
                            console.log('sw installed:', newWorker.scriptURL, newWorker);
                            if (navigator.serviceWorker.controller) {
                                console.log('sw installed and has controller');
                                // Show update bar
                                //showUpdateBar();
                            }
                            else {
                                console.log('sw installed and NO controller');
                            }
                            break;
                        default:
                            console.log('sw state: ' + newWorker.state);
                    }
                });

                newWorker.addEventListener('error', () => {
                    console.log('sw reg error:', arguments);
                });*/
            }).catch(function (error) {
                console.log('Service worker failed to register: ', error);
            });

            // Request permission to push message to the client
            /*Notification.requestPermission(function (status) {
                // register for push notifications
                navigator.serviceWorker.register('/firebase-messaging-sw.js')
                    .then(function(registration) {
                        console.log('firebase-messaging-sw.js registration successful with scope: ', registration.scope);

                        let newWorker = registration.installing;
                        newWorker.addEventListener('statechange', () => {
                            console.log('firebase-messaging-sw state changed:', newWorker.scriptURL, newWorker.state);
                            switch (newWorker.state) {
                                case 'installed':
                                    console.log('firebase-messaging-sw installed:', newWorker.scriptURL, newWorker);
                                    if (navigator.serviceWorker.controller) {
                                        console.log('firebase-messaging-sw installed and has controller');
                                        // Show update bar
                                        //showUpdateBar();
                                    }
                                    else {
                                        console.log('firebase-messaging-sw installed and NO controller');
                                    }
                                    break;
                                default:
                                    console.log('firebase-messaging-sw state: ' + newWorker.state);
                            }
                        });

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
                    }
                );
            });*/
        });
    }

    messaging.usePublicVapidKey("BLR2mpamo0soXSNPk5x__Hm58ustcM2kD-Ei_zqhxAFgmdAGrBpZfTSiI3DLbEZZ16AO7KS4xssZwEwJfLqY04A");

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
}