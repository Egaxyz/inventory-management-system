import "./bootstrap";
import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import "../css/app.css";
import axios from "axios";

axios.interceptors.request.use((config) => {
    const token = sessionStorage.getItem("token");
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

createApp(App).use(router).mount("#app");
