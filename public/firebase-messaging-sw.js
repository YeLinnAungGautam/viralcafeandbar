importScripts(
    "https://www.gstatic.com/firebasejs/10.0.0/firebase-app-compat.js"
);
importScripts(
    "https://www.gstatic.com/firebasejs/10.0.0/firebase-messaging-compat.js"
);

// Initialize Firebase
firebase.initializeApp({
    apiKey: "AIzaSyAge4ohEvnG1q8i9IKM-e5tCszrLJQONj4",
    authDomain: "khin-collections.firebaseapp.com",
    projectId: "khin-collections",
    storageBucket: "khin-collections.appspot.com",
    messagingSenderId: "1072323501949",
    appId: "1:1072323501949:web:c43f65b757cbe63c8f3614",
});

const messaging = firebase.messaging();

// Handle background messages
messaging.onBackgroundMessage((payload) => {
    console.log("here....");

    console.log(
        "[firebase-messaging-sw.js] Received background message ",
        payload
    );

    const notificationTitle = payload.notification.title || "Default Title";
    const notificationOptions = {
        body: payload.notification.body || "Default body text",
        icon: payload.notification.icon || "/default-icon.png",
        sound: payload.notification.sound || "/default-sound.mp3",
    };

    self.registration.showNotification(notificationTitle, notificationOptions);
});
