import "./assets/main.css";

import { createApp } from "vue";
import { createPinia } from "pinia";
import Echo from "laravel-echo";
import io from "socket.io-client";
import axios from "axios";

import App from "./App.vue";
import router from "./router";

const app = createApp(App);

app.use(createPinia());
app.use(router);

// Configure Laravel Echo for real-time broadcasting with Socket.IO
const token = localStorage.getItem("token");

window.Echo = new Echo({
  broadcaster: "socket.io",
  host: "http://localhost:6001",
  client: io,
  auth: {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  },
  authEndpoint: "http://localhost:8000/broadcasting/auth",
  csrfToken: null,
  encrypted: false,
  forceTLS: false,
  disableStats: true,
  enabledTransports: ["ws", "wss", "polling", "flashsocket"],
});

app.mount("#app");
