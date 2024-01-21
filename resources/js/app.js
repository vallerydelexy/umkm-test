import "./bootstrap";
import { createApp } from "vue";
import { createRouter, createWebHistory } from "vue-router/auto";
import "../css/app.css";
import VueGoogleMaps from "@fawmi/vue-google-maps";
const router = createRouter({
    history: createWebHistory(),
});
import { pinia } from "./store/pinia";

const app = createApp();
app.use(router);
app.use(pinia);
app.use(VueGoogleMaps, {
    load: {
        key: "AIzaSyBfehywaNbdgRo89_j37YZo_A5gxYODRPA",
    },
});
app.mount("#app");
