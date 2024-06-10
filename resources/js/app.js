import {createApp} from 'vue';
import App from './App.vue';
import router from '../opcen/router';
import VueGoogleMaps from '@fawmi/vue-google-maps';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import Notifications from '@kyvg/vue3-notification';
import pinia from '@/middleware/pinia';
import acl from '@/middleware/acl';
import * as ConfirmDialog from 'vuejs-confirm-dialog';
import axios from 'axios';
import Pusher from 'pusher-js';
import LaravelEcho from "laravel-echo-vue3"

const options = {
  broadcaster: 'pusher',
  key: import.meta.env.VITE_PUSHER_APP_KEY,
  cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'ap1',
  wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
  wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
  wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
  forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
  enabledTransports: ['ws', 'wss'],
  authEndpoint: '/broadcasting/auth'
}

const pendingRequests = {};

const removePendingRequest = (url, abort = false) => {
  // check if pendingRequests contains our request URL
  if (url && pendingRequests[url]) {
      // if we want to abort ongoing call, abort it
      if (abort) {
          pendingRequests[url].abort();
      }
      // remove the request URL from pending requests
      delete pendingRequests[url];
  }
};

axios.interceptors.request.use((config) => {
  // we only want to cancel requests if config.cancelPreviousRequests is true
  // if the config.url exists
  // if the config doesn't contain AbortController.signal already, in this case we let the developer handle it himself
  if (config?.cancelPreviousRequests && config?.url && !config.signal) {
      // remove request URL from pending requests and also abort ongoing call with same URL
      removePendingRequest(config.url, true);

      const abortController = new AbortController(); //create new AbortController
      config.signal = abortController.signal; // assign it's signal into request config
      pendingRequests[config.url] = abortController; // store AbortController in the pending requests map
  }

  return config;
});

axios.interceptors.response.use(
  (response) => {
      // remove pending request without aborting the call, call is finished
      removePendingRequest(response.request.responseURL);
      return response;
  },
  (error) => {
      // remove pending request without aborting the call, call is finished with error
      removePendingRequest(error.config?.url);
      return error;
  }
);

createApp(App)
    .use(router)
    .use(pinia)
    .use(acl)
    .use(ConfirmDialog)
    .use(VueGoogleMaps, {
      load: {
        key: import.meta.env.VITE_GOOGLE_API_KEY,
        libraries: "places",
      },
    })
    .use(Notifications)
    // .use(LaravelEcho, {
    //     ...options,
    //     client: new Pusher(options.key, options)
    // })
    .component('font-awesome-icon', FontAwesomeIcon)
    .mount("#app")