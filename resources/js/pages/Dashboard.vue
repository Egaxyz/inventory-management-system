<script setup>
import { ref } from "vue";
import Navbar from "../components/Navbar.vue";

const user = ref(JSON.parse(sessionStorage.getItem("user")));
</script>

<template>
    <div class="min-h-screen bg-gray-100 text-gray-800">
        <Navbar />
        <main class="p-6">
            <router-view />
        </main>

        <div v-if="user.role === 'admin'" class="p-4 bg-blue-100">
            Menu khusus Admin
        </div>
        <div v-if="user.role === 'staff'" class="p-4 bg-blue-100">
            Menu khusus Staff
        </div>
    </div>
</template>
