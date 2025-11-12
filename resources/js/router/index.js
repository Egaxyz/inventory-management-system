import { createRouter, createWebHistory } from "vue-router";
import Dashboard from "../pages/Dashboard.vue";
import Login from "../pages/Login.vue";
import User from "../pages/User.vue";

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

// ⬇️ Tambahkan ini di bawah, untuk mendeteksi navigasi "back"
window.addEventListener("pageshow", (event) => {
    // Kalau user klik tombol back dan halaman diambil dari cache
    if (event.persisted) {
        const token = localStorage.getItem("token");
        if (!token) {
            // Kalau token sudah tidak ada, langsung paksa ke login
            router.push("/login");
        }
    }
});
export default router;
