/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)

import './styles/app.scss';
import Webpush from 'webpush-client'
import $ from 'jquery'


$('.allow-push').on('click', () => {
    console.log('sheesh')
    Webpush.create({
        serviceWorkerPath: '/js/webpush-sw.js', // Public path to the service worker
        serverKey: window.push_notify_key, // https://developers.google.com/web/fundamentals/push-notifications/web-push-protocol#application_server_keys
        subscribeUrl: '/webpush/', // Optionnal - your application URL to store webpush subscriptions
    })
        .then(WebPushClient => {
                // do stuff with WebPushClient
                console.log(WebPushClient.isSupported()
                )
                console.log(WebPushClient.getPermissionState());
                WebPushClient.subscribe().then(res => {
                    console.log(res)
                });
            }
        )

    ;
})
