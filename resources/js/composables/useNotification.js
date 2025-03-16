import { initializeApp } from "firebase/app";
import { getMessaging, getToken, onMessage } from "firebase/messaging";
import axios from "axios";
import { onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";

export default function useNotification() {
    const page = usePage();
    const { languages, api_token, auth } = page.props;

    const firebaseConfig = {
        apiKey: "AIzaSyAge4ohEvnG1q8i9IKM-e5tCszrLJQONj4",
        authDomain: "khin-collections.firebaseapp.com",
        projectId: "khin-collections",
        storageBucket: "khin-collections.appspot.com",
        messagingSenderId: "1072323501949",
        appId: "1:1072323501949:web:c43f65b757cbe63c8f3614",
    };

    const app = initializeApp(firebaseConfig);
    const messaging = getMessaging(app);

    function getNotificationPermission() {
        return Notification.requestPermission()
            .then((permission) => {
                console.log(`Notification permission status: ${permission}`);
                return permission;
            })
            .catch((error) => {
                console.error(
                    "Error requesting notification permission:",
                    error
                );
            });
    }

    // function storeFckToken(token) {
    // axios
    // .post(
    // route("token.save"),
    // {
    // token: token,
    // auth_id: auth.user.id,
    // },
    // {
    // headers: { "API-TOKEN": api_token },
    // }
    // )
    // .catch((error) => {
    // console.error("Error storing FCM token:", error);
    // });
    // }

    const vapidKey = import.meta.env.VITE_FIREBASE_PUSH_KEY;

    onMounted(() => {
        getNotificationPermission()
            .then(() => {
                return getToken(messaging, { vapidKey });
            })
            .then((currentToken) => {
                if (currentToken) {
                    console.log("Current token:", currentToken);
                }
            })
            .catch((error) => {
                console.error("Error getting FCM token:", error);
            });

        onMessage(messaging, ({ notification, data }) => {
            console.log(notification);

            if (notification) {
                const noti = new Notification(notification.title, {
                    body: notification.body,
                    icon: notification.icon,
                });

                noti.onclick = (e) => {
                    e.preventDefault();
                    window.open(data.admin_link, "_blank");
                };
            }
        });
    });

    return { getNotificationPermission };
}
