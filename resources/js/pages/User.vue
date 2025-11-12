<script setup>
import Navbar from "../components/Navbar.vue";
import { ref, onMounted } from "vue";
import axios from "axios";

const users = ref([]);

onMounted(async () => {
    try {
        const response = await axios.get("http://localhost:8000/api/user");
        users.value = response.data;
    } catch (error) {
        console.error("Gagal memuat data:", error);
    }
});
</script>

<template>
    <Navbar />
    <div class="p-6">
        <h2 class="text-2xl font-bold mb-4">Daftar Pengguna</h2>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Nama Pengguna</th>
                    <th class="border px-4 py-2">Email</th>
                    <th class="border px-4 py-2">Peran</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="user in users"
                    :key="user.id"
                    class="hover:bg-gray-50"
                >
                    <td class="border px-4 py-2">{{ user.id }}</td>
                    <td class="border px-4 py-2">{{ user.name }}</td>
                    <td class="border px-4 py-2">{{ user.email }}</td>
                    <td class="border px-4 py-2">{{ user.role || "User" }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
