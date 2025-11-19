<template>
    <div
        class="min-h-screen flex items-center justify-center bg-cover bg-center relative"
        style="
            background-image: url('https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1920&q=80');
        "
    >
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

        <!-- Box Login -->
        <div
            class="relative z-10 bg-white/90 backdrop-blur-md rounded-2xl shadow-2xl p-8 w-full max-w-sm"
        >
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">
                Login ke Sistem
            </h2>

            <form @submit.prevent="login">
                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1"
                        >Email</label
                    >
                    <input
                        v-model="email"
                        type="email"
                        placeholder="Masukkan email"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-slate-500 focus:outline-none"
                    />
                </div>

                <!-- Password + Toggle Mata -->
                <div class="mb-6 relative">
                    <label class="block text-gray-700 font-medium mb-1"
                        >Password</label
                    >
                    <input
                        :type="showPassword ? 'text' : 'password'"
                        v-model="password"
                        placeholder="Masukkan password"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-10 focus:ring-2 focus:ring-slate-500 focus:outline-none"
                    />
                    <button
                        type="button"
                        @click="showPassword = !showPassword"
                        class="absolute right-3 top-9 text-gray-500 hover:text-slate-600 transition"
                    >
                        <svg
                            v-if="!showPassword"
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-8 h-5"
                            fill="none"
                            viewBox="0 0 15 20"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                            />
                        </svg>

                        <svg
                            v-else
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.957 9.957 0 012.24-3.772M9.88 9.88a3 3 0 104.24 4.24M3 3l18 18"
                            />
                        </svg>
                    </button>
                </div>

                <!-- Tombol -->
                <button
                    :disabled="loading"
                    type="submit"
                    class="w-full bg-slate-500 hover:bg-white hover:text-slate-600 text-white font-semibold py-2 rounded-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
                >
                    <svg
                        v-if="loading"
                        class="animate-spin h-5 w-5 mr-2 text-white"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle
                            class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"
                        ></circle>
                        <path
                            class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                        ></path>
                    </svg>
                    <span>{{ loading ? "Loading..." : "Login" }}</span>
                </button>

                <!-- Pesan error -->
                <p
                    v-if="errorMessage"
                    class="text-black text-sm mt-3 text-center"
                >
                    {{ errorMessage }}
                </p>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

const router = useRouter();
const loading = ref(false);
const email = ref("");
const password = ref("");
const showPassword = ref(false);
const errorMessage = ref("");

// 🔐 Fungsi Login
const login = async () => {
    errorMessage.value = "";

    try {
        loading.value = true;
        await axios.get("http://localhost:8000/sanctum/csrf-cookie");
        const response = await axios.post("http://localhost:8000/api/login", {
            email: email.value,
            password: password.value,
        });

        if (response.data.user) {
            sessionStorage.setItem("token", response.data.token);
            sessionStorage.setItem("user", JSON.stringify(response.data.user));
            router.push("/");
            console.log(response.data);
        } else {
            errorMessage.value =
                response.data?.message ||
                "Login gagal, periksa kembali data Anda.";
        }
    } catch (error) {
        console.error(error.response);
        errorMessage.value =
            error.response?.data?.message ||
            "Login gagal, periksa kembali data Anda.";
    } finally {
        loading.value = false;
    }
};
</script>
