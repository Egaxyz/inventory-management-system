import { createRouter, createWebHistory } from "vue-router";
import Dashboard from "../pages/Dashboard.vue";
import Login from "../pages/Login.vue";
import User from "../pages/User.vue";
import Type from "../pages/Type.vue";
import TypeForm from "../pages/FormPage/TypeForm.vue";
import EditTypeForm from "../pages/FormPage/EditTypeForm.vue";
import Supplier from "../pages/Supplier.vue";
import SupplierForm from "../pages/FormPage/SupplierForm.vue";
import EditSupplierForm from "../pages/FormPage/EditSupplierForm.vue";
import ProductForm from "../pages/FormPage/ProductForm.vue";
import Product from "../pages/Product.vue";
import EditProductForm from "../pages/FormPage/EditProductForm.vue";
import Peminjaman from "../pages/Peminjaman.vue";
import PeminjamanForm from "../pages/FormPage/PeminjamanForm.vue";
import EditPeminjamanForm from "../pages/FormPage/EditPeminjamanForm.vue";
import BarangMasuk from "../pages/BarangMasuk.vue";
import BarangMasukForm from "../pages/FormPage/BarangMasukForm.vue";
import EditBarangMasuk from "../pages/FormPage/EditBarangMasuk.vue";

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
        meta: { requiresAuth: true, role: "admin" },
    },
    {
        path: "/supplier",
        name: "supplier",
        component: Supplier,
        meta: { requiresAuth: true, role: "admin" },
    },
    {
        path: "/supplier/tambah",
        name: "supplierForm",
        component: SupplierForm,
        meta: { requiresAuth: true, role: "admin" },
    },
    {
        path: "/supplier/edit/:id",
        name: "supplierFormEdit",
        component: EditSupplierForm,
        meta: { requiresAuth: true, role: "admin" },
    },
    {
        path: "/type",
        name: "type",
        component: Type,
        meta: { requiresAuth: true, role: "admin" },
    },
    {
        path: "/type/tambah",
        name: "typeForm",
        component: TypeForm,
        meta: { requiresAuth: true, role: "admin" },
    },
    {
        path: "/type/edit/:id",
        name: "typeFormEdit",
        component: EditTypeForm,
        meta: { requiresAuth: true, role: "admin" },
    },
    {
        path: "/produk",
        name: "produk",
        component: Product,
        meta: { requiresAuth: true, role: "admin" },
    },
    {
        path: "/produk/tambah",
        name: "produkForm",
        component: ProductForm,
        meta: { requiresAuth: true, role: "admin" },
    },
    {
        path: "/produk/edit/:id",
        name: "produkFormEdit",
        component: EditProductForm,
        meta: { requiresAuth: true, role: "admin" },
    },
    {
        path: "/peminjaman",
        name: "peminjaman",
        component: Peminjaman,
        meta: { requiresAuth: true, role: "staff" },
    },
    {
        path: "/peminjaman/tambah",
        name: "peminjamanForm",
        component: PeminjamanForm,
        meta: { requiresAuth: true, role: "staff" },
    },
    {
        path: "/peminjaman/edit/:id",
        name: "peminjamanFormEdit",
        component: EditPeminjamanForm,
        meta: { requiresAuth: true, role: "staff" },
    },
    {
        path: "/barang-masuk",
        name: "barang-masuk",
        component: BarangMasuk,
        meta: { requiresAuth: true, role: "staff" },
    },
    {
        path: "/barang-masuk/tambah",
        name: "barangMasukForm",
        component: BarangMasukForm,
        meta: { requiresAuth: true, role: "staff" },
    },
    {
        path: "/barang-masuk/edit/:id",
        name: "barangMasukFormEdit",
        component: EditBarangMasuk,
        meta: { requiresAuth: true, role: "staff" },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const token = sessionStorage.getItem("token");
    const user = JSON.parse(sessionStorage.getItem("user"));

    if (to.meta.role && user.role != to.meta.role) {
        return next({ name: "forbidden" });
    }

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
        const token = sessionStorage.getItem("token");
        if (!token) {
            router.push("/login");
        }
    }
});
export default router;
