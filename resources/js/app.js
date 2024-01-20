import "./bootstrap";
import { createApp } from "vue";
import { createRouter, createWebHistory } from "vue-router/auto";
import "../css/app.css";

const router = createRouter({
    history: createWebHistory(),
});

const app = createApp();
app.use(router);
app.mount("#app");
