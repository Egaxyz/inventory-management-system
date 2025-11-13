import { createRouter, createWebHistory } from "vue-router";
import Dashboard from "../pages/Dashboard.vue";
import Login from "../pages/Login.vue";
import User from "../pages/User.vue";
import Type from "../pages/Type.vue";

const routes = [
    {
        path: "/",
        name: "dashboard",
        component: Dashboard,
        meta: { requiresAuth: true },
    },
    {
        path: "/login",
        name: "login",
        component: Login,
    },
    {
        path: "/user",
        name: "user",
        component: User,
        meta: { requiresAuth: true },
    },
    {
        path: "/type",
        name: "type",
        component: () => Type,
        meta: { requiresAuth: true },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem("token");

    if (to.meta.requiresAuth && !token) {
        next("/login");
    } else if (to.path === "/login" && token) {
        next("/");
    } else {
        next();
    }
});

window.addEventListener("pageshow", (event) => {
    if (event.persisted) {
        const token = localStorage.getItem("token");
        if (!token) {
            router.push("/login");
        }
    }
});
export default router;
