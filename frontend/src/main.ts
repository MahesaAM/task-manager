import "./assets/main.css";

import { createApp } from "vue";
import { createPinia } from "pinia";
import Echo from "laravel-echo";
import Pusher from "pusher-js";

import App from "./App.vue";
import router from "./router";

const app = createApp(App);

app.use(createPinia());
app.use(router);

// Configure Laravel Echo for real-time broadcasting with Reverb
window.Pusher = Pusher;

window.Echo = new Echo({
  broadcaster: "reverb",
  key: "mwyyyv1rqnzlwmivt2eh",
  wsHost: "127.0.0.1",
  wsPort: 8080,
  wssPort: 8080,
  forceTLS: false,
  enabledTransports: ["ws", "wss"],
  authEndpoint: "http://127.0.0.1:8000/broadcasting/auth",
  auth: {
    headers: {
      Authorization: `Bearer ${localStorage.getItem("token")}`,
    },
  },
});

app.mount("#app");
