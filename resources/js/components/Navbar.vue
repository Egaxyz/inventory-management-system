<template>
    <nav
        class="bg-slate-500 shadow-md px-7 py-3 flex items-center justify-between"
    >
        <!-- Logo -->
        <router-link to="/" class="text-xl font-bold text-white">
            Peminjaman Barang
        </router-link>

        <!-- Menu -->
        <div class="space-x-6 flex items-center">
            <router-link
                to="/"
                class="text-white hover:text-gray-600 hover:bg-white rounded-md p-1 font-medium"
                :class="{ 'text-white    font-semibold': $route.path === '/' }"
            >
                Dashboard
            </router-link>

            <router-link
                to="/produk"
                class="text-white hover:text-gray-600 hover:bg-white rounded-md p-1 font-medium"
                :class="{
                    'text-white font-semibold': $route.path === '/produk',
                }"
                v-if="user.role == 'admin'"
            >
                Produk
            </router-link>
            <router-link
                to="/supplier"
                class="text-white hover:text-gray-600 hover:bg-white rounded-md p-1 font-medium"
                :class="{
                    'text-white font-semibold': $route.path === '/supplier',
                }"
                v-if="user.role == 'admin'"
            >
                Supplier
            </router-link>

            <router-link
                to="/type"
                class="text-white hover:text-gray-600 hover:bg-white rounded-md p-1 font-medium"
                :class="{
                    'text-white font-semibold': $route.path === '/type',
                }"
                v-if="user.role == 'admin'"
            >
                Type
            </router-link>
            <router-link
                to="/user"
                class="text-white hover:text-gray-600 hover:bg-white rounded-md p-1 font-medium"
                :class="{
                    'text-white font-semibold': $route.path === '/user',
                }"
                v-if="user.role == 'admin'"
            >
                User
            </router-link>
            <div
                class="relative inline-block dropdown-container"
                v-if="user.role == 'staff'"
            >
                <button
                    class="text-white hover:text-gray-600 hover:bg-white rounded-md p-1 font-medium flex items-center gap-1"
                    @click="isOpen = !isOpen"
                >
                    <span
                        :class="{
                            'text-white font-semibold':
                                $route.path.startsWith('/peminjaman'),
                        }"
                    >
                        Kontrol Barang
                    </span>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4 mt-px"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 9l-7 7-7-7"
                        />
                    </svg>
                </button>

                <div
                    v-if="isOpen"
                    class="absolute bg-white border border-gray-200 rounded-lg shadow-lg mt-2 w-40 z-50"
                >
                    <router-link
                        to="/peminjaman"
                        class="block px-4 py-2 text-gray-600 hover:bg-gray-400 hover:rounded-lg hover:text-white"
                        @click="isOpen = false"
                    >
                        Peminjaman
                    </router-link>
                    <router-link
                        to="/barang-masuk"
                        class="block px-4 py-2 text-gray-600 hover:bg-gray-400 hover:rounded-lg hover:text-white"
                        @click="isOpen = false"
                    >
                        Barang Masuk
                    </router-link>
                </div>
            </div>

            <button
                @click="logout"
                class="hover:bg-white hover:text-gray-600 text-white px-4 py-1 rounded-lg font-medium transition"
            >
                Logout
            </button>
        </div>
    </nav>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();
const isOpen = ref(false);

const user = ref(JSON.parse(localStorage.getItem("user")));

const handleClickOutside = (event) => {
    const dropdown = document.querySelector(".dropdown-container");
    if (dropdown && !dropdown.contains(event.target)) {
        isOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener("click", handleClickOutside);
});
const logout = async () => {
    try {
        const token = localStorage.getItem("token");

        if (token) {
            await axios.post(
                "http://localhost:8000/api/logout",
                {},
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                }
            );
        }

        // Hapus token & data user
        localStorage.removeItem("token");
        localStorage.removeItem("user");

        // Hapus header Authorization default axios
        delete axios.defaults.headers.common["Authorization"];

        // Arahkan ke halaman login
        router.push("/login");
    } catch (error) {
        console.error("Gagal logout:", error);
    }
};
</script>
