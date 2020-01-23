// FILE PATH: /resources/js/firebase.js

import * as firebase from 'firebase/app';
require('firebase/messaging');

const initializedFirebaseApp = firebase.initializeApp({
    messagingSenderId: '#####'
});
let messaging = null;

if (firebase.messaging.isSupported()) {
    messaging = initializedFirebaseApp.messaging();

    messaging.usePublicVapidKey(
        '#####'
    );

    messaging.onMessage((payload) => {
        const notificationTitle = payload.notification.title;
        const notificationOptions = {
            body: payload.notification.body,
            icon: '/android-chrome-144x144.png',
        };

        if (!('Notification' in window)) {
            console.log('This browser does not support system notifications');
        } else if (Notification.permission === 'granted') {
            let notification = new Notification(notificationTitle,notificationOptions);
            notification.onclick = function(event) {
                event.preventDefault();
                window.open(payload.notification.click_action , '_blank');
                notification.close();
            };
        }
    });
}

export { messaging };