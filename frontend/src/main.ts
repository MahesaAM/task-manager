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

// Configure Laravel Echo with Socket.IO
const token = localStorage.getItem("token");

window.io = io;

window.Echo = new Echo({
  broadcaster: "socket.io",
  host: "http://localhost:6001",
  auth: {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  },
  csrfToken: null,
  bearerToken: token,
});

app.mount("#app");
